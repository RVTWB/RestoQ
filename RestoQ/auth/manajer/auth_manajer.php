<?php
session_start();

// Koneksi database
$conn = new mysqli("localhost", "root", "", "restoq");
if ($conn->connect_error) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Koneksi database gagal']);
        exit();
    }
    die("Koneksi database gagal: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Fungsi: Cek login hanya untuk akses halaman, bukan untuk AJAX
function require_manajer_login() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'manajer') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Akses ditolak']);
            exit();
        }
        header('Location: ../../login.php?error=access_denied');
        exit();
    }
}

// === Fungsi: Ambil Ringkasan Harian (dengan dukungan DATETIME) ===
function getDailySummary($conn) {
    // Tentukan zona waktu (sesuaikan dengan lokasi Anda)
    date_default_timezone_set('Asia/Jakarta'); // Ubah jika perlu

    $today_start = date('Y-m-d 00:00:00'); // Awal hari
    $today_end   = date('Y-m-d 23:59:59'); // Akhir hari

    $sql = "SELECT 
                COALESCE(SUM(total_bayar), 0) as total_pendapatan, 
                COUNT(*) as jumlah_transaksi 
            FROM transaksi 
            WHERE tanggal_transaksi >= ? AND tanggal_transaksi <= ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Prepare error (getDailySummary): " . $conn->error);
        return ['error' => 'Query gagal disiapkan'];
    }

    $stmt->bind_param("ss", $today_start, $today_end);
    if (!$stmt->execute()) {
        error_log("Execute error (getDailySummary): " . $stmt->error);
        return ['error' => 'Eksekusi query gagal'];
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return [
        'total_pendapatan' => (float)$row['total_pendapatan'],
        'jumlah_transaksi' => (int)$row['jumlah_transaksi']
    ];
}

// === Fungsi: Ambil Data Pendapatan untuk Grafik (tetap seperti sebelumnya) ===
function getRevenueData($conn, $period) {
    $labels = [];
    $values = [];

    if ($period === 'week') {
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime($date));
            $start = $date . ' 00:00:00';
            $end   = $date . ' 23:59:59';

            $sql = "SELECT COALESCE(SUM(total_bayar), 0) as total 
                    FROM transaksi 
                    WHERE tanggal_transaksi >= ? AND tanggal_transaksi <= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $start, $end);
            $stmt->execute();
            $result = $stmt->get_result();
            $values[] = (float)$result->fetch_assoc()['total'];
            $stmt->close();
        }
    } elseif ($period === 'month') {
        $currentYear = date('Y');
        for ($month = 1; $month <= 12; $month++) {
            $monthFormatted = sprintf('%02d', $month);
            $yearMonth = $currentYear . '-' . $monthFormatted;
            $labels[] = date('M Y', mktime(0, 0, 0, $month, 1, $currentYear));

            $start = $yearMonth . '-01 00:00:00';
            $end   = date('Y-m-t 23:59:59', strtotime($start)); // Akhir bulan

            $sql = "SELECT COALESCE(SUM(total_bayar), 0) as total 
                    FROM transaksi 
                    WHERE tanggal_transaksi >= ? AND tanggal_transaksi <= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $start, $end);
            $stmt->execute();
            $result = $stmt->get_result();
            $values[] = (float)$result->fetch_assoc()['total'];
            $stmt->close();
        }
    } else {
        for ($i = 4; $i >= 0; $i--) {
            $year = date('Y', strtotime("-$i years"));
            $labels[] = $year;

            $start = $year . '-01-01 00:00:00';
            $end   = $year . '-12-31 23:59:59';

            $sql = "SELECT COALESCE(SUM(total_bayar), 0) as total 
                    FROM transaksi 
                    WHERE tanggal_transaksi >= ? AND tanggal_transaksi <= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $start, $end);
            $stmt->execute();
            $result = $stmt->get_result();
            $values[] = (float)$result->fetch_assoc()['total'];
            $stmt->close();
        }
    }

    return ['labels' => $labels, 'values' => $values];
}

// === Tangani Permintaan AJAX (POST) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (!isset($_POST['action'])) {
        echo json_encode(['error' => 'Aksi tidak valid']);
        $conn->close();
        exit();
    }

    require_manajer_login();

    $action = $_POST['action'];
    try {
        switch ($action) {
            case 'getDailySummary':
                $data = getDailySummary($conn);
                echo json_encode($data);
                break;

            case 'getRevenueData':
                $period = $_POST['period'] ?? 'week';
                $data = getRevenueData($conn, $period);
                echo json_encode($data);
                break;

            default:
                echo json_encode(['error' => 'Aksi tidak dikenali']);
        }
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        echo json_encode(['error' => 'Terjadi kesalahan internal']);
    }

    $conn->close();
    exit();
}

// Jika bukan POST (akses langsung), pastikan login
require_manajer_login();
?>