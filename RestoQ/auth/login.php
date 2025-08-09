<?php
session_start();
require_once '../config/db.php';

// Cek jika form dikirim
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: ../login.php?error=empty");
        exit();
    }
    
    // Koneksi ke database
    $conn = getConnection();
    
    // Cek kredensial dari database
    $stmt = $conn->prepare("SELECT id_user, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Verifikasi password langsung (tanpa enkripsi)
        if ($password === $user['password']) {
            // Set session dengan benar
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            
            // Debug: Tampilkan session yang diset
            echo "Session set:<br>";
            echo "user_id: " . $_SESSION['user_id'] . "<br>";
            echo "username: " . $_SESSION['username'] . "<br>";
            echo "role: " . $_SESSION['role'] . "<br>";
            echo "logged_in: " . $_SESSION['logged_in'] . "<br>";
            echo "Redirecting...";
            // exit();
            
            // Redirect berdasarkan role
            switch ($user['role']) {
                case 'pelayan':
                    header("Location: ../pelayan/dashboard_pelayan.php");
                    break;
                case 'koki':
                    header("Location: ../koki/dashboard_koki.php");
                    break;
                case 'kasir':
                    header("Location: ../kasir/dashboard_kasir.php");
                    break;
                case 'manajer':
                    header("Location: ../manajer/dashboard_manajer.php");
                    break;
                case 'pelanggan':
                    header("Location: ../pelanggan/dashboard_pelanggan.php");
                    break;
                default:
                    header("Location: ../login.php?error=invalid_role");
            }
            exit();
        } else {
            // Password salah
            header("Location: ../login.php?error=invalid_password");
            exit();
        }
    } else {
        // Username tidak ditemukan
        header("Location: ../login.php?error=user_not_found");
        exit();
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Jika tidak melalui form, redirect ke login
    header("Location: ../login.php");
    exit();
}
?>