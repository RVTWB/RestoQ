<?php
session_start();

// Hancurkan semua data sesi
$_SESSION = array();

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();

// Tangani permintaan dari AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'logout') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
    exit();
}

// Jika akses langsung, redirect ke login
header('Location: login.php');
exit();
?>