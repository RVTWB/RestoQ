<?php
function getConnection() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'restoq';
    
    $conn = new mysqli($host, $username, $password, $database);
    
    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    return $conn;
}
?>