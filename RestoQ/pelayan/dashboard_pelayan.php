<?php
require_once '../auth/pelayan/auth_pelayan.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelayan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            font-size: 0.95rem;
        }
        .sidebar {
            width: 220px;
            background-color: #ffffff;
            color: #1f2937;
            padding: 1.25rem;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 1rem;
            border-bottom-right-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            display: block;
            padding: 0.6rem 0.8rem;
            margin-bottom: 0.4rem;
            border-radius: 0.4rem;
            color: #374151;
            transition: background-color 0.2s ease;
            font-size: 0.9rem;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }
        .main-content {
            margin-left: 220px;
            padding: 1.5rem;
            flex-grow: 1;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.8rem;
            padding: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .table-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .table-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .status-available {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-occupied {
            background-color: #fef3c7;
            color: #92400e;
        }
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            font-size: 0.85rem;
        }
        .btn-primary {
            background-color: #22c55e;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #16a34a;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #f97316;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #ea580c;
            transform: translateY(-1px);
        }
        .btn-danger {
            background-color: #ef4444;
            color: #ffffff;
        }
        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 1.5rem;
            border-radius: 0.8rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            position: absolute;
            top: 8px;
            right: 15px;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2000;
            pointer-events: none;
        }
        .toast {
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateY(-20px);
            animation: slideDown 0.4s forwards;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .toast.success {
            background-color: #10b981;
        }
        .toast.error {
            background-color: #ef4444;
        }
        @keyframes slideDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Logout Confirmation Modal */
        .logout-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .logout-modal-content {
            background-color: #fff;
            padding: 2rem;
            border-radius: 0.8rem;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="toast-container" id="toast-container"></div>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="text-xl font-bold mb-6 text-center">Dashboard Pelayan</h1>
            <nav>
                <a href="#" class="active">Status Meja</a>
            </nav>
            <!-- Logout Button -->
            <div class="mt-auto pt-4 border-t border-gray-200">
                <button id="logoutBtn" class="flex items-center text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg transition w-full text-left">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header Dashboard -->
            <header class="card flex justify-between items-center mb-10 p-4">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Status Meja</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                        <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'P'; ?>
                    </div>
                    <span class="text-gray-700 font-medium text-sm">
                        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pelayan'; ?>
                    </span>
                </div>
            </header>
            <!-- Tables Section -->
            <section id="tables">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Status Meja</h3>
                    <button class="btn btn-primary" onclick="refreshTables()">Refresh Status</button>
                </div>
                <div id="tables-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <p class="text-gray-500 col-span-full text-center">Memuat data meja...</p>
                </div>
            </section>
        </div>
    </div>
    <!-- Table Detail Modal -->
    <div id="tableDetailModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeTableDetailModal()">&times;</span>
            <h3 class="text-xl font-bold mb-4">Detail Meja <span id="modal-table-number"></span></h3>
            <div class="mb-4">
                <p class="text-gray-600 mb-2">Kapasitas: <span id="modal-table-capacity" class="font-semibold"></span> orang</p>
                <p class="text-gray-600 mb-2">Status: <span id="modal-table-status" class="font-semibold"></span></p>
                <div class="flex space-x-2 mt-4">
                    <button id="btn-occupy" class="btn btn-secondary" onclick="updateTableStatus('terpakai')">Tandai Terpakai</button>
                    <button id="btn-finish" class="btn btn-primary" onclick="updateTableStatus('tersedia')">Selesai</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Logout</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="flex justify-center space-x-4">
                <button class="btn btn-secondary" onclick="closeLogoutModal()">Batal</button>
                <button class="btn btn-danger" onclick="confirmLogout()">Ya, Logout</button>
            </div>
        </div>
    </div>
    <script>
        let currentTableId = null;

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type} mb-2`;
            toast.textContent = message;
            toastContainer.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (toast.parentElement === toastContainer) {
                        toast.remove();
                    }
                }, 300);
            }, 3000);
        }

        function handleFetchError(error, containerId) {
            console.error('Fetch Error:', error);
            const container = document.getElementById(containerId);
            if (container) {
                container.innerHTML = '<p class="text-red-500 col-span-full text-center">Gagal memuat data.</p>';
            }
            if (error.message && error.message.includes('403')) {
                showToast('Akses ditolak. Silakan login kembali.', 'error');
                setTimeout(() => {
                    window.location.href = '../../login.php';
                }, 1000);
            }
        }

        function loadTables() {
            const container = document.getElementById('tables-container');
            container.innerHTML = '<p class="text-gray-500 col-span-full text-center"><span class="spinner"></span> Memuat data meja...</p>';
            fetch('../auth/pelayan/auth_pelayan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getTableStatus'
            })
            .then(r => r.ok ? r.json() : Promise.reject(r))
            .then(data => {
                if (data.error) throw new Error(data.error);
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 col-span-full text-center">Tidak ada meja.</p>';
                    return;
                }
                data.forEach(t => {
                    const statusClass = t.status_label === 'tersedia' ? 'status-available' :
                                      t.status_label === 'terpakai' ? 'status-occupied' : 'status-served';
                    const card = document.createElement('div');
                    card.className = 'table-card card cursor-pointer';
                    card.innerHTML = `
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="text-lg font-bold">Meja ${t.nomor_meja}</h4>
                            <span class="px-2 py-1 rounded text-xs font-semibold ${statusClass}">${t.status_label.toUpperCase()}</span>
                        </div>
                        <p class="text-gray-600 text-sm">Kapasitas: ${t.kapasitas} orang</p>
                        <p class="text-gray-600 text-sm">ID: ${t.id_meja}</p>
                    `;
                    card.onclick = () => openTableDetailModal(t.id_meja, t.nomor_meja, t.kapasitas, t.status_label);
                    container.appendChild(card);
                });
            })
            .catch(e => handleFetchError(e, 'tables-container'));
        }

        function openTableDetailModal(id, num, cap, status) {
            currentTableId = id;
            document.getElementById('modal-table-number').textContent = num;
            document.getElementById('modal-table-capacity').textContent = cap;
            document.getElementById('modal-table-status').textContent = status.toUpperCase();
            const statusClass = status === 'tersedia' ? 'status-available' :
                              status === 'terpakai' ? 'status-occupied' : 'status-served';
            document.getElementById('modal-table-status').className = `font-semibold px-2 py-1 rounded text-sm ${statusClass}`;
            document.getElementById('btn-occupy').style.display = status === 'tersedia' ? 'block' : 'none';
            document.getElementById('btn-finish').style.display = status === 'terpakai' ? 'block' : 'none';
            document.getElementById('tableDetailModal').style.display = 'flex';
        }

        function closeTableDetailModal() {
            document.getElementById('tableDetailModal').style.display = 'none';
        }

        function updateTableStatus(status) {
            if (!currentTableId) return;
            const btns = [document.getElementById('btn-occupy'), document.getElementById('btn-finish')];
            btns.forEach(b => { if (b) b.disabled = true; });

            fetch('../auth/pelayan/auth_pelayan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=updateTableStatus&tableId=${currentTableId}&status=${status}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeTableDetailModal();
                    loadTables();
                    showToast('Status meja berhasil diperbarui');
                } else {
                    showToast('Gagal: ' + (data.error || 'tidak diketahui'), 'error');
                }
            })
            .catch(e => {
                console.error('Error:', e);
                showToast('Terjadi kesalahan saat memperbarui status.', 'error');
            })
            .finally(() => {
                btns.forEach(b => { if (b) b.disabled = false; });
            });
        }

        function refreshTables() {
            loadTables();
            showToast('Data meja diperbarui');
        }

        window.onclick = e => {
            if (e.target === document.getElementById('tableDetailModal')) closeTableDetailModal();
            if (e.target === document.getElementById('logoutModal')) closeLogoutModal();
        };

        // Logout functions
        document.getElementById('logoutBtn').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'flex';
        });
        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
        function confirmLogout() {
            fetch('/RESTOQ/logout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=logout'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast('Berhasil logout');
                    setTimeout(() => {
                        window.location.href = '/RESTOQ/login.php';
                    }, 1000);
                } else {
                    showToast('Logout gagal', 'error');
                }
            })
            .catch(error => {
                console.error('Error logout:', error);
                showToast('Terjadi kesalahan saat logout', 'error');
                setTimeout(() => {
                    window.location.href = '/RESTOQ/login.php';
                }, 1500);
            });
        }

        document.addEventListener('DOMContentLoaded', loadTables);
    </script>
</body>
</html>