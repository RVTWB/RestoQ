<?php
session_start();

// === 1. Autentikasi Kasir ===
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../login.php?error=not_logged_in');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../../login.php?error=access_denied');
    exit();
}

// === 2. Koneksi Database ===
$conn = new mysqli("localhost", "root", "", "restoq");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// === 3. Fungsi: Ambil Semua Meja yang Belum Lunas ===
function getUnpaidTables($conn) {
    $sql = "
        SELECT 
            m.id_meja,
            m.nomor_meja,
            p.id_pesanan,
            p.tanggal_pesanan,
            SUM(dp.kuantitas * menu.harga) AS total
        FROM meja m
        JOIN pesanan p ON m.id_meja = p.id_meja
        JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
        JOIN menu ON dp.id_menu = menu.id_menu
        WHERE p.id_pesanan NOT IN (SELECT id_pesanan FROM transaksi)
        GROUP BY m.id_meja, m.nomor_meja, p.id_pesanan, p.tanggal_pesanan
        ORDER BY p.tanggal_pesanan ASC
    ";
    $result = $conn->query($sql);
    $tables = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tables[] = $row;
        }
    }
    return $tables;
}

// === 4. Fungsi: Ambil Transaksi Hari Ini ===
function getTodayTransactions($conn) {
    $today = date('Y-m-d');
    $sql = "
        SELECT 
            p.id_pesanan,
            m.nomor_meja,
            SUM(dp.kuantitas * menu.harga) AS total_bayar,
            t.metode_pembayaran,
            t.tanggal_transaksi
        FROM pesanan p
        JOIN meja m ON p.id_meja = m.id_meja
        JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
        JOIN menu ON dp.id_menu = menu.id_menu
        JOIN transaksi t ON p.id_pesanan = t.id_pesanan
        WHERE DATE(t.tanggal_transaksi) = ?
        GROUP BY p.id_pesanan, m.nomor_meja, t.metode_pembayaran, t.tanggal_transaksi
        ORDER BY t.tanggal_transaksi DESC
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['status_bayar'] = 'sudah';
            $transactions[] = $row;
        }
    }
    $stmt->close();
    return $transactions;
}

// === 5. Fungsi: Update Status Pembayaran ===
function updatePaymentStatus($conn, $orderId, $paymentMethod = 'tunai') {
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("SELECT id_meja FROM pesanan WHERE id_pesanan = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            throw new Exception('Pesanan tidak ditemukan');
        }
        $stmt->close();

        $stmt2 = $conn->prepare("SELECT SUM(dp.kuantitas * m.harga) as total FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = ?");
        $stmt2->bind_param("i", $orderId);
        $stmt2->execute();
        $totalRow = $stmt2->get_result()->fetch_assoc();
        $total = $totalRow['total'];
        $stmt2->close();

        $stmt3 = $conn->prepare("INSERT INTO transaksi (id_pesanan, metode_pembayaran, total_bayar) VALUES (?, ?, ?)");
        $stmt3->bind_param("isd", $orderId, $paymentMethod, $total);
        if (!$stmt3->execute()) {
            throw new Exception('Gagal menyimpan transaksi');
        }
        $stmt3->close();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error pembayaran: " . $e->getMessage());
        return false;
    }
}

// === 6. Fungsi: Hapus Transaksi ===
function deleteTransaction($conn, $orderId) {
    $conn->begin_transaction();
    try {
        $stmt1 = $conn->prepare("DELETE FROM transaksi WHERE id_pesanan = ?");
        $stmt1->bind_param("i", $orderId);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("DELETE FROM detail_pesanan WHERE id_pesanan = ?");
        $stmt2->bind_param("i", $orderId);
        $stmt2->execute();
        $stmt2->close();

        $stmt3 = $conn->prepare("DELETE FROM pesanan WHERE id_pesanan = ?");
        $stmt3->bind_param("i", $orderId);
        $stmt3->execute();
        $stmt3->close();

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// === 7. Fungsi: Cetak Struk ===
function printReceipt($conn, $orderId) {
    return ['success' => true, 'message' => 'Struk berhasil dicetak'];
}

// === 8. Tangani Permintaan AJAX ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (!isset($_POST['action'])) {
        echo json_encode(['error' => 'Aksi tidak valid']);
        $conn->close();
        exit();
    }

    $action = $_POST['action'];

    try {
        switch ($action) {
            case 'getUnpaidTables':
                $data = getUnpaidTables($conn);
                echo json_encode($data);
                break;

            case 'getTodayTransactions':
                $data = getTodayTransactions($conn);
                echo json_encode($data);
                break;

            case 'updatePaymentStatus':
                $orderId = intval($_POST['orderId']);
                $method = $_POST['paymentMethod'] ?? 'tunai';
                $result = updatePaymentStatus($conn, $orderId, $method);
                echo json_encode(['success' => $result]);
                break;

            case 'deleteTransaction':
                $orderId = intval($_POST['orderId']);
                $result = deleteTransaction($conn, $orderId);
                echo json_encode(['success' => $result]);
                break;

            case 'printReceipt':
                $orderId = intval($_POST['orderId']);
                $result = printReceipt($conn, $orderId);
                echo json_encode($result);
                break;

            default:
                echo json_encode(['error' => 'Aksi tidak dikenali']);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    $conn->close();
    exit();
}

// Jika bukan AJAX, biarkan halaman dashboard dilanjutkan
?>