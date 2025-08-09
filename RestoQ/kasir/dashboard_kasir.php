<?php
require_once '../auth/kasir/auth_kasir.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
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
            width: 240px;
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            margin-left: 240px;
            padding: 1.5rem;
            flex-grow: 1;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.8rem;
            padding: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
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
        .btn-warning {
            background-color: #eab308;
            color: #ffffff;
        }
        .btn-warning:hover {
            background-color: #ca8a04;
            transform: translateY(-1px);
        }
        .table-responsive {
            overflow-x: auto;
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
            cursor: pointer;
        }
        .close-button:hover {
            color: black;
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
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="text-xl font-bold mb-8 text-center">Dashboard Kasir</h1>
            <nav class="flex-grow">
                <a href="#" class="active" onclick="showSection('monitoring')">Monitoring</a>
                <a href="#" onclick="showSection('transactions')">Daftar Transaksi</a>
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
            <header class="card flex justify-between items-center mb-6 p-4">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-gray-800" id="main-dashboard-title">Monitoring</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                        <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'K'; ?>
                    </div>
                    <span class="text-gray-700 font-medium text-sm">
                        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Kasir'; ?>
                    </span>
                </div>
            </header>
            <!-- Monitoring Section -->
            <section id="monitoring" class="dashboard-section">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Monitoring Meja Siap Bayar</h3>
                    <button class="btn btn-primary" onclick="refreshMonitoring()">Refresh Data</button>
                </div>
                <div class="card table-responsive">
                    <table class="min-w-full bg-white rounded-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 text-left">No. Meja</th>
                                <th class="py-2 px-4 text-left">ID Pesanan</th>
                                <th class="py-2 px-4 text-left">Tanggal Pesanan</th>
                                <th class="py-2 px-4 text-left">Total</th>
                                <th class="py-2 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="monitoring-table-body">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                    <div id="no-monitoring-data" class="text-center py-4 text-gray-500 hidden">
                        Tidak ada meja yang belum membayar
                    </div>
                </div>
            </section>
            <!-- Transactions Section -->
            <section id="transactions" class="dashboard-section hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Daftar Transaksi Hari Ini</h3>
                    <button class="btn btn-primary" onclick="refreshTransactions()">Refresh Data</button>
                </div>
                <div class="card table-responsive">
                    <table class="min-w-full bg-white rounded-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 text-left">No. Meja</th>
                                <th class="py-2 px-4 text-left">ID Pesanan</th>
                                <th class="py-2 px-4 text-left">Total</th>
                                <th class="py-2 px-4 text-left">Metode Bayar</th>
                                <th class="py-2 px-4 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-table-body">
                            <!-- Data akan diisi oleh JavaScript -->
                        </tbody>
                    </table>
                    <div id="no-transactions-data" class="text-center py-4 text-gray-500 hidden">
                        Tidak ada transaksi hari ini
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- Toast Notification -->
    <div class="toast-container" id="toast-container"></div>
    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closePaymentModal()">&times;</span>
            <h3 class="text-xl font-bold mb-4">Pilih Metode Pembayaran</h3>
            <input type="hidden" id="paymentOrderId">
            <div class="flex flex-col space-y-3">
                <button class="btn btn-primary" onclick="processPayment('tunai')">Bayar Tunai</button>
                <button class="btn btn-secondary" onclick="processPayment('kartu')">Bayar Kartu</button>
                <button class="btn btn-warning" onclick="processPayment('qris')">Bayar QRIS</button>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeDeleteModal()">&times;</span>
            <h3 class="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
            <p class="mb-6">Apakah Anda yakin ingin menghapus transaksi ini?</p>
            <input type="hidden" id="deleteOrderId">
            <div class="flex justify-end space-x-3">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                <button class="btn btn-danger" onclick="confirmDelete()">Ya, Hapus</button>
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
        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type} mb-2`;
            toast.textContent = message;
            toastContainer.appendChild(toast);

            // Hapus toast setelah 3.5 detik (3 detik tampil + 0.5 detik fade out)
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

        // Function to show the selected dashboard section
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.dashboard-section');
            sections.forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
            const navLinks = document.querySelectorAll('.sidebar a');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            let targetLink;
            if (sectionId === 'monitoring') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('monitoring')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Monitoring';
                loadUnpaidTables();
            } else if (sectionId === 'transactions') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('transactions')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Daftar Transaksi';
                loadTodayTransactions();
            }
            if (targetLink) targetLink.classList.add('active');
        }

        // Load unpaid tables
        function loadUnpaidTables() {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getUnpaidTables'
            })
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('monitoring-table-body');
                const noData = document.getElementById('no-monitoring-data');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    noData.classList.remove('hidden');
                    return;
                }
                noData.classList.add('hidden');
                data.forEach(t => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">Meja ${t.nomor_meja}</td>
                        <td class="py-2 px-4 border-b">#${t.id_pesanan}</td>
                        <td class="py-2 px-4 border-b">${new Date(t.tanggal_pesanan).toLocaleString('id-ID')}</td>
                        <td class="py-2 px-4 border-b">Rp ${parseInt(t.total).toLocaleString('id-ID')}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="btn btn-primary" onclick="openPaymentModal(${t.id_pesanan})">Lunas</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(() => showToast('Gagal memuat data monitoring', 'error'));
        }

        // Load today transactions
        function loadTodayTransactions() {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getTodayTransactions'
            })
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('transactions-table-body');
                const noData = document.getElementById('no-transactions-data');
                tbody.innerHTML = '';
                if (data.length === 0) {
                    noData.classList.remove('hidden');
                    return;
                }
                noData.classList.add('hidden');
                data.forEach(t => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="py-2 px-4 border-b">Meja ${t.nomor_meja}</td>
                        <td class="py-2 px-4 border-b">#${t.id_pesanan}</td>
                        <td class="py-2 px-4 border-b">Rp ${parseInt(t.total_bayar).toLocaleString('id-ID')}</td>
                        <td class="py-2 px-4 border-b">${t.metode_pembayaran.toUpperCase()}</td>
                        <td class="py-2 px-4 border-b">
                            <button class="btn btn-warning mr-2" onclick="printReceipt(${t.id_pesanan})">Cetak Struk</button>
                            <button class="btn btn-danger" onclick="openDeleteModal(${t.id_pesanan})">Hapus</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(() => showToast('Gagal memuat data transaksi', 'error'));
        }

        // Payment functions
        function openPaymentModal(orderId) {
            document.getElementById('paymentOrderId').value = orderId;
            document.getElementById('paymentModal').style.display = 'flex';
        }
        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }
        function processPayment(method) {
            const orderId = document.getElementById('paymentOrderId').value;
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=updatePaymentStatus&orderId=${orderId}&paymentMethod=${method}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closePaymentModal();
                    loadUnpaidTables();
                    loadTodayTransactions();
                    showToast(`Pembayaran ${method === 'tunai' ? 'Tunai' : method === 'kartu' ? 'Kartu' : 'QRIS'} berhasil!`);
                } else {
                    showToast('Gagal memproses pembayaran', 'error');
                }
            })
            .catch(() => showToast('Gagal memproses pembayaran', 'error'));
        }

        // Print receipt
        function printReceipt(orderId) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=printReceipt&orderId=${orderId}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                } else {
                    showToast('Gagal mencetak struk', 'error');
                }
            })
            .catch(() => showToast('Gagal mencetak struk', 'error'));
        }

        // Delete functions
        function openDeleteModal(orderId) {
            document.getElementById('deleteOrderId').value = orderId;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        function confirmDelete() {
            const orderId = document.getElementById('deleteOrderId').value;
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=deleteTransaction&orderId=${orderId}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    loadTodayTransactions();
                    loadUnpaidTables();
                    showToast('Transaksi berhasil dihapus');
                } else {
                    showToast('Gagal menghapus transaksi', 'error');
                }
            })
            .catch(() => showToast('Gagal menghapus transaksi', 'error'));
        }

        // Refresh functions
        function refreshMonitoring() {
            loadUnpaidTables();
            showToast('Data monitoring diperbarui');
        }
        function refreshTransactions() {
            loadTodayTransactions();
            showToast('Data transaksi diperbarui');
        }

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
                    showToast('Berhasil logout', 'success');
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

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target === document.getElementById('paymentModal')) {
                closePaymentModal();
            }
            if (event.target === document.getElementById('deleteModal')) {
                closeDeleteModal();
            }
            if (event.target === document.getElementById('logoutModal')) {
                closeLogoutModal();
            }
        };

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', () => {
            showSection('monitoring');
        });
    </script>
</body>
</html>