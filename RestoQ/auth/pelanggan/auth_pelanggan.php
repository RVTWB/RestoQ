<?php
// Mulai session
session_start();

// === 1. Autentikasi Pelanggan ===
function require_pelanggan_login() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'pelanggan') {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Anda harus login sebagai pelanggan.']);
            exit();
        } else {
            header('Location: /RESTOQ/login.php');
            exit();
        }
    }
}
require_pelanggan_login();

// === 2. Koneksi Database ===
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'restoq';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]);
        exit();
    } else {
        die('Koneksi database gagal: ' . $conn->connect_error);
    }
}
$conn->set_charset("utf8mb4");

// === 3. Inisialisasi Meja dari Session ===
$tableNumber = null;
$tableId = null;

if (isset($_SESSION['table_id'])) {
    $tableId = intval($_SESSION['table_id']);
    $stmt = $conn->prepare("SELECT nomor_meja FROM meja WHERE id_meja = ?");
    if ($stmt) {
        $stmt->bind_param("i", $tableId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            // Meja tidak ada → hapus session
            unset($_SESSION['table_id']);
            $tableId = null;
        } else {
            $row = $result->fetch_assoc();
            $tableNumber = $row['nomor_meja'];
        }
        $stmt->close();
    }
}

// === 4. Tangani Permintaan AJAX ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');

    if (!isset($_POST['action'])) {
        echo json_encode(['error' => 'Aksi tidak valid']);
        $conn->close();
        exit();
    }

    $action = $_POST['action'];

    try {
        switch ($action) {
            case 'getMenuItems':
                $stmt = $conn->prepare("SELECT id_menu, nama_menu, harga, kategori FROM menu WHERE status = 'tersedia'");
                $stmt->execute();
                $result = $stmt->get_result();
                $menus = [];
                while ($row = $result->fetch_assoc()) {
                    $menus[] = $row;
                }
                $stmt->close();
                echo json_encode($menus);
                break;

            case 'getAvailableTables':
                // Jika sudah ada meja di session, tidak tampilkan meja lain
                if (isset($_SESSION['table_id'])) {
                    echo json_encode([]);
                    break;
                }

                $stmt = $conn->prepare("SELECT id_meja, nomor_meja FROM meja WHERE status = 'tersedia' ORDER BY nomor_meja");
                $stmt->execute();
                $result = $stmt->get_result();
                $tables = [];
                while ($row = $result->fetch_assoc()) {
                    $tables[] = $row;
                }
                $stmt->close();
                echo json_encode($tables);
                break;

            case 'selectTable':
                if (!isset($_POST['table_id'])) {
                    echo json_encode(['error' => 'ID meja tidak diberikan']);
                    exit();
                }

                // Cegah memilih meja jika sudah ada
                if (isset($_SESSION['table_id'])) {
                    echo json_encode(['error' => 'Anda sudah memilih meja']);
                    exit();
                }

                $selectedTableId = intval($_POST['table_id']);
                if ($selectedTableId <= 0) {
                    echo json_encode(['error' => 'ID meja tidak valid']);
                    exit();
                }

                $conn->begin_transaction();
                try {
                    // Langsung update status meja hanya jika masih tersedia
                    $updateStmt = $conn->prepare("UPDATE meja SET status = 'terpakai' WHERE id_meja = ? AND status = 'tersedia'");
                    $updateStmt->bind_param("i", $selectedTableId);
                    $updateStmt->execute();

                    if ($updateStmt->affected_rows === 0) {
                        throw new Exception('Meja sudah tidak tersedia atau tidak valid');
                    }

                    $updateStmt->close();

                    // Simpan ke session
                    $_SESSION['table_id'] = $selectedTableId;

                    $conn->commit();

                    echo json_encode([
                        'success' => true,
                        'message' => 'Meja berhasil dipilih',
                        'table_id' => $selectedTableId
                    ]);
                } catch (Exception $e) {
                    $conn->rollback();
                    echo json_encode(['error' => $e->getMessage()]);
                }
                break;

            case 'createOrder':
                if (!isset($_POST['items']) || !isset($_POST['table_id'])) {
                    echo json_encode(['error' => 'Data pesanan atau meja tidak lengkap']);
                    exit();
                }

                $selectedTableId = intval($_POST['table_id']);
                $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : null;

                // Validasi: sesi meja harus cocok
                if (!isset($_SESSION['table_id']) || $_SESSION['table_id'] != $selectedTableId) {
                    echo json_encode(['error' => 'Meja tidak valid']);
                    exit();
                }

                $items = json_decode($_POST['items'], true);
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($items) || empty($items)) {
                    echo json_encode(['error' => 'Format data pesanan tidak valid']);
                    exit();
                }

                $conn->begin_transaction();
                try {
                    if ($orderId) {
                        $stmt = $conn->prepare("SELECT id_pesanan FROM pesanan WHERE id_pesanan = ? AND id_meja = ? AND status = 'pending'");
                        $stmt->bind_param("ii", $orderId, $selectedTableId);
                        $stmt->execute();
                        if ($stmt->get_result()->num_rows === 0) {
                            throw new Exception('Pesanan tidak bisa diperbarui');
                        }
                        $stmt->close();
                    } else {
                        $stmt = $conn->prepare("INSERT INTO pesanan (id_meja, status, tanggal_pesanan) VALUES (?, 'pending', NOW())");
                        $stmt->bind_param("i", $selectedTableId);
                        if (!$stmt->execute()) {
                            throw new Exception('Gagal membuat pesanan baru');
                        }
                        $orderId = $conn->insert_id;
                        $stmt->close();
                    }

                    foreach ($items as $item) {
                        if (!isset($item['id_menu']) || !isset($item['kuantitas']) || $item['kuantitas'] < 1) {
                            throw new Exception('Jumlah pesanan tidak valid');
                        }

                        $catatan = isset($item['catatan']) ? substr(trim($item['catatan']), 0, 255) : '';
                        $stmt = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, kuantitas, catatan) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("iiis", $orderId, $item['id_menu'], $item['kuantitas'], $catatan);
                        if (!$stmt->execute()) {
                            throw new Exception('Gagal menyimpan item: ' . $conn->error);
                        }
                        $stmt->close();
                    }

                    $conn->commit();

                    echo json_encode([
                        'success' => true,
                        'message' => 'Pesanan berhasil dibuat',
                        'order_id' => $orderId,
                        'table_id' => $selectedTableId
                    ]);
                } catch (Exception $e) {
                    $conn->rollback();
                    echo json_encode(['error' => $e->getMessage()]);
                }
                break;

            case 'getOrders':
                if (!isset($_SESSION['table_id'])) {
                    echo json_encode(['error' => 'Meja belum dipilih']);
                    exit();
                }
                $tableId = intval($_SESSION['table_id']);
                $stmt = $conn->prepare("
                    SELECT 
                        p.id_pesanan, 
                        p.tanggal_pesanan, 
                        p.status as status_pesanan,
                        dp.id_menu, 
                        m.nama_menu, 
                        dp.kuantitas, 
                        m.harga, 
                        COALESCE(dp.catatan, '') as catatan, 
                        m.kategori
                    FROM pesanan p
                    JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
                    JOIN menu m ON dp.id_menu = m.id_menu
                    WHERE p.id_meja = ?
                    ORDER BY p.tanggal_pesanan DESC, p.id_pesanan DESC
                ");
                $stmt->bind_param("i", $tableId);
                $stmt->execute();
                $result = $stmt->get_result();
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
                $stmt->close();
                echo json_encode($orders);
                break;

            case 'getOrdersForRating':
                if (!isset($_SESSION['table_id'])) {
                    echo json_encode(['error' => 'Meja belum dipilih']);
                    exit();
                }
                $tableId = intval($_SESSION['table_id']);
                $stmt = $conn->prepare("
                    SELECT 
                        p.id_pesanan, 
                        p.tanggal_pesanan,
                        SUM(dp.kuantitas * m.harga) as total
                    FROM pesanan p
                    JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
                    JOIN menu m ON dp.id_menu = m.id_menu
                    WHERE p.id_meja = ?
                    GROUP BY p.id_pesanan, p.tanggal_pesanan
                    ORDER BY p.tanggal_pesanan DESC
                    LIMIT 1
                ");
                $stmt->bind_param("i", $tableId);
                $stmt->execute();
                $result = $stmt->get_result();
                $orders = [];
                while ($row = $result->fetch_assoc()) {
                    $orders[] = $row;
                }
                $stmt->close();
                echo json_encode($orders);
                break;

            case 'getCurrentTable':
                if (isset($_SESSION['table_id'])) {
                    echo json_encode(['table_id' => $_SESSION['table_id']]);
                } else {
                    echo json_encode(['table_id' => null]);
                }
                break;

            case 'getTableById':
                if (!isset($_POST['id'])) {
                    echo json_encode(['error' => 'ID meja tidak diberikan']);
                    exit();
                }
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("SELECT id_meja, nomor_meja FROM meja WHERE id_meja = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    echo json_encode($result->fetch_assoc());
                } else {
                    echo json_encode(['error' => 'Meja tidak ditemukan']);
                }
                $stmt->close();
                break;

            case 'getRating':
                if (!isset($_POST['order_id']) || !isset($_SESSION['table_id'])) {
                    echo json_encode(['rating' => 0, 'komentar' => '']);
                    break;
                }
                $orderId = intval($_POST['order_id']);
                $tableId = intval($_SESSION['table_id']);

                $stmt = $conn->prepare("SELECT rating, komentar FROM rating WHERE id_meja = ? AND id_pesanan = ? ORDER BY id_rating DESC LIMIT 1");
                $stmt->bind_param("ii", $tableId, $orderId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo json_encode($result->fetch_assoc());
                } else {
                    echo json_encode(['rating' => 0, 'komentar' => '']);
                }
                $stmt->close();
                break;

            case 'saveRating':
                if (!isset($_POST['rating']) || !isset($_POST['order_id'])) {
                    echo json_encode(['error' => 'Rating atau ID pesanan tidak diberikan']);
                    exit();
                }

                $rating = intval($_POST['rating']);
                $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
                $orderId = intval($_POST['order_id']);

                if ($rating < 1 || $rating > 5) {
                    echo json_encode(['error' => 'Rating harus antara 1 sampai 5']);
                    exit();
                }

                if (!isset($_SESSION['table_id'])) {
                    echo json_encode(['error' => 'Meja tidak dipilih']);
                    exit();
                }
                $tableId = intval($_SESSION['table_id']);

                // Cek apakah sudah pernah memberi rating untuk pesanan ini
                $checkStmt = $conn->prepare("SELECT 1 FROM rating WHERE id_meja = ? AND id_pesanan = ? LIMIT 1");
                $checkStmt->bind_param("ii", $tableId, $orderId);
                $checkStmt->execute();
                if ($checkStmt->get_result()->num_rows > 0) {
                    $checkStmt->close();
                    echo json_encode(['error' => 'Anda sudah memberikan rating untuk pesanan ini.']);
                    exit();
                }
                $checkStmt->close();

                $timestamp = date('Y-m-d H:i:s');
                $insertStmt = $conn->prepare("INSERT INTO rating (id_meja, id_pesanan, rating, komentar, tanggal) VALUES (?, ?, ?, ?, ?)");
                $insertStmt->bind_param("iiiis", $tableId, $orderId, $rating, $comment, $timestamp);

                if ($insertStmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Terima kasih atas rating Anda!'
                    ]);
                } else {
                    echo json_encode(['error' => 'Gagal menyimpan rating']);
                }
                $insertStmt->close();
                break;

            default:
                echo json_encode(['error' => 'Aksi tidak dikenali']);
        }
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error di auth_pelanggan.php: " . $e->getMessage());
        echo json_encode(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
    }

    $conn->close();
    exit();
}

// Jika bukan AJAX, biarkan dashboard pelanggan melanjutkan
?>