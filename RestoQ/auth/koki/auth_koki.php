<?php
session_start();
// Cek login dan role
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'koki') {
    header('Location: ../login.php?error=access_denied');
    exit();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $conn = mysqli_connect("localhost", "root", "", "restoq");
    if (!$conn) {
        echo json_encode(['error' => 'Koneksi ke database gagal']);
        exit;
    }

    $action = $_POST['action'];
    $response = [];

    try {
        switch ($action) {
            case 'toggleMenuStatus':
                if (!isset($_POST['menu_id']) || !isset($_POST['new_status'])) {
                    throw new Exception('Data tidak lengkap');
                }
                
                $menuId = (int)$_POST['menu_id'];
                $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);
                
                $query = "UPDATE menu SET status = '$newStatus' WHERE id_menu = $menuId";
                if (mysqli_query($conn, $query)) {
                    $response['success'] = true;
                } else {
                    throw new Exception('Gagal mengubah status menu');
                }
                break;

            case 'getAllMenuItems':
                $query = "SELECT * FROM menu ORDER BY kategori, nama_menu";
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    throw new Exception('Gagal mengambil data menu');
                }
                
                $menuItems = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $menuItems[] = $row;
                }
                $response = $menuItems;
                break;

            case 'getPendingOrders':
                $query = "SELECT p.id_pesanan, p.tanggal_pesanan, 
                         GROUP_CONCAT(CONCAT(m.nama_menu, ' (', dp.kuantitas, ')') SEPARATOR ', ') as items
                         FROM pesanan p
                         JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
                         JOIN menu m ON dp.id_menu = m.id_menu
                         WHERE p.status = 'pending'
                         GROUP BY p.id_pesanan
                         ORDER BY p.tanggal_pesanan ASC";
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    throw new Exception('Gagal mengambil pesanan pending');
                }
                
                $orders = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $orders[] = $row;
                }
                $response = $orders;
                break;

            case 'getInProgressOrders':
                $query = "SELECT p.id_pesanan, p.tanggal_pesanan, 
                         GROUP_CONCAT(CONCAT(m.nama_menu, ' (', dp.kuantitas, ')') SEPARATOR ', ') as items
                         FROM pesanan p
                         JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
                         JOIN menu m ON dp.id_menu = m.id_menu
                         WHERE p.status = 'diproses'
                         GROUP BY p.id_pesanan
                         ORDER BY p.tanggal_pesanan ASC";
                $result = mysqli_query($conn, $query);
                if (!$result) {
                    throw new Exception('Gagal mengambil pesanan diproses');
                }
                
                $orders = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $orders[] = $row;
                }
                $response = $orders;
                break;

            case 'updateOrderStatus':
                if (!isset($_POST['order_id']) || !isset($_POST['new_status'])) {
                    throw new Exception('Data tidak lengkap');
                }
                
                $orderId = (int)$_POST['order_id'];
                $newStatus = mysqli_real_escape_string($conn, $_POST['new_status']);
                
                $query = "UPDATE pesanan SET status = '$newStatus' WHERE id_pesanan = $orderId";
                if (mysqli_query($conn, $query)) {
                    $response['success'] = true;
                } else {
                    throw new Exception('Gagal mengubah status pesanan');
                }
                break;

            default:
                throw new Exception('Aksi tidak dikenali');
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if (isset($_GET['load_menu']) && $_GET['load_menu'] == '1') {
    $conn = mysqli_connect("localhost", "root", "", "restoq");
    if (!$conn) {
        echo '<div class="text-center text-red-500 p-4">Koneksi ke database gagal.</div>';
        exit;
    }

    $sql = "SELECT id_menu, nama_menu, harga, kategori, deskripsi, status FROM menu ORDER BY kategori, nama_menu";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $statusClass = $row['status'] === 'tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            $statusText = $row['status'] === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia';
            $buttonClass = $row['status'] === 'tersedia' ? 'btn-danger' : 'btn-primary';
            $buttonText = $row['status'] === 'tersedia' ? 'Buat Tidak Tersedia' : 'Buat Tersedia';
            
            echo '
            <div class="menu-item bg-white rounded-lg shadow-md overflow-hidden" data-id="'.$row['id_menu'].'" 
                 data-category="'.$row['kategori'].'" data-status="'.$row['status'].'">
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-800">'.htmlspecialchars($row['nama_menu']).'</h4>
                            <p class="text-gray-600">Rp '.number_format($row['harga'], 0, ',', '.').'</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full '.$statusClass.'">
                            '.$statusText.'
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mb-3">'.($row['deskripsi'] ? htmlspecialchars($row['deskripsi']) : 'Tidak ada deskripsi').'</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">'.$row['kategori'].'</span>
                        <button onclick="toggleMenuStatus('.$row['id_menu'].', \''.$row['status'].'\')" 
                                class="btn btn-sm '.$buttonClass.'">
                            '.$buttonText.'
                        </button>
                    </div>
                </div>
            </div>';
        }
    } else {
        echo '<div class="text-center text-gray-500 p-4">Tidak ada menu tersedia</div>';
    }
    exit;
}
?>