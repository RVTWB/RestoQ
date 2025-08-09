<?php
session_start();

// Cek login dan role
if (!isset($_SESSION['logged_in'])) {
    header('Location: ../../login.php?error=not_logged_in');
    exit();
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelayan') {
    header('Location: ../../login.php?error=access_denied');
    exit();
}

// Koneksi database
$conn = new mysqli("localhost", "root", "", "restoq");
if ($conn->connect_error) {
    error_log("Koneksi gagal: " . $conn->connect_error);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi: Ambil daftar meja
function getTables($conn) {
    $sql = "SELECT id_meja, nomor_meja, kapasitas, status FROM meja ORDER BY nomor_meja";
    $result = $conn->query($sql);

    if (!$result) {
        error_log("Query gagal: " . $conn->error);
        return [];
    }

    $tables = [];
    while ($row = $result->fetch_assoc()) {
        $row['status_label'] = $row['status'];
        $tables[] = $row;
    }
    return $tables;
}

// Fungsi: Update status meja
function updateTableStatus($conn, $tableId, $status) {
    if (!in_array($status, ['tersedia', 'terpakai'])) {
        error_log("Status tidak valid: $status");
        return false;
    }

    $stmt = $conn->prepare("UPDATE meja SET status = ? WHERE id_meja = ?");
    $stmt->bind_param("si", $status, $tableId);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

// Tangani permintaan AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    if ($action === 'getTableStatus') {
        echo json_encode(getTables($conn));
    } elseif ($action === 'updateTableStatus') {
        $tableId = $_POST['tableId'] ?? 0;
        $status = $_POST['status'] ?? '';
        $result = updateTableStatus($conn, (int)$tableId, $status);
        echo json_encode(['success' => $result]);
    } else {
        echo json_encode(['error' => 'Invalid action']);
    }
    $conn->close();
    exit;
}

// Jika bukan POST, biarkan HTML menangani tampilan
?>