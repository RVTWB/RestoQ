<?php
// Memuat autentikasi dan logika
require_once '../auth/koki/auth_koki.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Koki</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com "></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .menu-tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .menu-tab {
            padding: 0.5rem 1rem;
            cursor: pointer;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            margin-right: 0.5rem;
        }

        .menu-tab.active {
            border-bottom-color: #22c55e;
            color: #22c55e;
        }

        .menu-section {
            height: 60vh;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .menu-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            background: white;
        }

        .menu-item:hover {
            border-color: #9ca3af;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            grid-column: 1 / -1;
        }
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
        .summary-card {
            text-align: center;
        }
        .summary-card .value {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1f2937;
        }
        .summary-card .label {
            font-size: 0.9rem;
            color: #6b7280;
        }
        .order-card {
            padding: 1rem;
            margin-bottom: 0.75rem;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.3rem 0;
            font-size: 0.9rem;
        }
        .order-item input[type="checkbox"] {
            margin-right: 0.6rem;
            transform: scale(1.1);
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
        .btn-disabled {
            background-color: #9ca3af;
            color: #ffffff;
            cursor: not-allowed;
        }
        .order-status-badge {
            padding: 0.2rem 0.6rem;
            border-radius: 0.4rem;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending {
            background-color: #fcd34d;
            color: #92400e;
        }
        .status-confirmed {
            background-color: #60a5fa;
            color: #1e40af;
        }
        .status-completed {
            background-color: #34d399;
            color: #065f46;
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
            max-width: 450px;
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
        .history-filter-select {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3e%3cpath d='M7 7l3-3 3 3m0 6l-3 3-3-3' stroke='%236B7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.25em 1.25em;
        }
        .history-filter-select:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.5);
        }
        .scrollable-content {
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
        }
        .scrollable-content::-webkit-scrollbar {
            width: 8px;
        }
        .scrollable-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .scrollable-content::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .scrollable-content::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .menu-category-title {
            font-weight: 600;
            margin-top: 0.5rem;
            margin-bottom: 0.2rem;
            color: #4b5563;
            font-size: 0.88rem;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .menu-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.8rem;
        }
        .order-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        
        .order-card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .order-id {
            font-weight: 600;
            color: #1f2937;
        }
        
        .order-time {
            font-size: 0.8rem;
            color: #6b7280;
        }
        
        .order-items {
            font-size: 0.9rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        
        .order-action-btn {
            padding: 0.4rem 0.8rem;
            border-radius: 0.3rem;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-confirm {
            background-color: #22c55e;
            color: white;
        }
        
        .btn-confirm:hover {
            background-color: #16a34a;
        }
        
        .btn-complete {
            background-color: #3b82f6;
            color: white;
        }
        
        .btn-complete:hover {
            background-color: #2563eb;
        }
        
        .history-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .history-total {
            font-weight: 600;
            color: #1f2937;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
        }
        
        /* Custom notification style */
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 90%;
            animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-in 2.5s forwards;
        }
        
        .notification.success {
            background-color: #22c55e;
            color: white;
        }
        
        .notification.error {
            background-color: #ef4444;
            color: white;
        }
        
        .notification.warning {
            background-color: #f59e0b;
            color: white;
        }
        
        @keyframes slideIn {
            from {
                top: -50px;
                opacity: 0;
            }
            to {
                top: 20px;
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
        
        /* Custom confirmation modal */
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .confirmation-content {
            background-color: white;
            padding: 24px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
        .confirmation-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .confirmation-message {
            margin-bottom: 24px;
            color: #4b5563;
        }
        
        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        
        .confirmation-button {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .confirmation-button.cancel {
            background-color: #e5e7eb;
            color: #1f2937;
        }
        
        .confirmation-button.cancel:hover {
            background-color: #d1d5db;
        }
        
        .confirmation-button.confirm {
            background-color: #ef4444;
            color: white;
        }
        
        .confirmation-button.confirm:hover {
            background-color: #dc2626;
        }
    </style>

</head>
<body>
    <!-- Notification container -->
    <div id="notification-container"></div>
    
    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-content">
            <div class="confirmation-title">Konfirmasi Logout</div>
            <div class="confirmation-message">Apakah Anda yakin ingin keluar dari sistem?</div>
            <div class="confirmation-buttons">
                <button class="confirmation-button cancel" onclick="hideConfirmationModal()">Batal</button>
                <button class="confirmation-button confirm" onclick="performLogout()">Ya, Logout</button>
            </div>
        </div>
    </div>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="text-xl font-bold mb-6 text-center">Dashboard Koki</h1>
            <nav>
                <a href="#" class="active" onclick="showSection('order-queue')">Antrian Pesanan</a>
                <a href="#" onclick="showSection('menu')">Daftar Menu</a>
            </nav>
            <div class="mt-auto pt-4 border-t border-gray-200">
                <button onclick="showConfirmationModal()" class="flex items-center text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg transition w-full text-left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header Dashboard -->
            <header class="card flex justify-between items-center mb-10 p-4">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold text-gray-800" id="main-dashboard-title">Antrian Pesanan</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                        <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'K'; ?>
                    </div>
                    <span class="text-gray-700 font-medium text-sm">
                        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Koki'; ?>
                    </span>
                </div>
            </header>

            <!-- Order Queue Section -->
            <section id="order-queue" class="dashboard-section">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    <!-- Card: Pesanan Menunggu Konfirmasi -->
                    <div class="card order-card col-span-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Pesanan Menunggu Konfirmasi (<span id="pending-count">0</span> Antrian)</h3>
                        <div id="pending-orders" class="space-y-3 scrollable-content">
                            <p class="text-gray-500 text-center">Memuat pesanan...</p>
                        </div>
                    </div>
                    
                    <!-- Card: Pesanan Sedang Diproses -->
                    <div class="card order-card col-span-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Pesanan Sedang Diproses (<span id="in-progress-count">0</span> Antrian)</h3>
                        <div id="in-progress-orders" class="space-y-3 scrollable-content">
                            <p class="text-gray-500 text-center">Memuat pesanan...</p>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Cards for Order Queue -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="card summary-card">
                        <div class="value" id="total-orders-count">0</div>
                        <div class="label">Total Pesanan Masuk</div>
                    </div>
                    <div class="card summary-card">
                        <div class="value" id="in-queue-count">0</div>
                        <div class="label">Dalam Antrian</div>
                    </div>
                    <div class="card summary-card">
                        <div class="value" id="completed-orders-total-count">0</div>
                        <div class="label">Pesanan Selesai</div>
                    </div>
                </div>
            </section>

            <!-- Menu Section -->
            <section id="menu" class="dashboard-section hidden">
                <div class="card">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">Daftar Menu</h3>
                    </div>
                    <div class="mb-4">
                        <input type="text" id="search-menu" class="w-full p-2 border border-gray-300 rounded-md" 
                               placeholder="Cari menu..." onkeyup="searchMenu()">
                    </div>
                    
                    <div class="menu-section">
                        <div class="menu-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="menu-grid">
                            <p class="text-gray-500 text-center col-span-full">Memuat menu...</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal for Order Details -->
    <div id="orderDetailsModal" class="modal hidden">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h3 class="text-xl font-bold mb-3" id="modal-order-id">Detail Pesanan #</h3>
            <p class="text-gray-600 text-sm mb-1">Waktu Pesanan: <span id="modal-order-time"></span></p>
            <h4 class="text-lg font-semibold mb-2">Daftar Menu:</h4>
            <div id="modal-menu-list" class="space-y-1 mb-3 scrollable-content">
                <!-- Menu items will be loaded here -->
            </div>
            <div class="flex justify-end space-x-3" id="modal-actions">
                <!-- Buttons will be loaded here based on order status -->
            </div>
        </div>
    </div>

    <script>
        // Function to show notification
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            container.appendChild(notification);
            
            // Remove notification after animation completes
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Function to show confirmation modal
        function showConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        // Function to hide confirmation modal
        function hideConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        // Function to perform logout
        function performLogout() {
            window.location.href = '../logout.php';
        }

        // Function to show the selected dashboard section
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.dashboard-section');
            sections.forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
            
            // Update active link in sidebar
            const navLinks = document.querySelectorAll('.sidebar a');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            
            // Find the link based on its sectionId
            let targetLink;
            if (sectionId === 'order-queue') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('order-queue')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Antrian Pesanan';
                loadOrderQueue();
            } else if (sectionId === 'order-history') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('order-history')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Riwayat Pesanan';
                loadOrderHistory();
            } else if (sectionId === 'menu') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('menu')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Daftar Menu';
                loadMenuItems();
            }
            if (sectionId === 'order-history') {
             loadOrderHistory();
             }
            if (targetLink) {
                targetLink.classList.add('active');
            }
        }

        // Load order queue data
        function loadOrderQueue() {
            // Load pending orders
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: 'action=getPendingOrders'
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('pending-orders');
                if (data.error) {
                    container.innerHTML = `<p class="text-red-500 text-center">${data.error}</p>`;
                    return;
                }
                
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center">Tidak ada pesanan menunggu konfirmasi</p>';
                    document.getElementById('pending-count').textContent = '0';
                    return;
                }
                
                let html = '';
                data.forEach(order => {
                    const orderTime = new Date(order.tanggal_pesanan).toLocaleString('id-ID');
                    
                    html += `
                    <div class="order-card" data-id="${order.id_pesanan}">
                        <div class="order-card-header">
                            <span class="order-id">Pesanan #${order.id_pesanan}</span>
                            <span class="order-time">${orderTime}</span>
                        </div>
                        <div class="order-items">${order.items}</div>
                        <div class="flex justify-end">
                            <button class="order-action-btn btn-confirm" 
                                    onclick="updateOrderStatus(${order.id_pesanan}, 'diproses')">
                                Konfirmasi
                            </button>
                        </div>
                    </div>`;
                });
                
                container.innerHTML = html;
                document.getElementById('pending-count').textContent = data.length;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('pending-orders').innerHTML = '<p class="text-red-500 text-center">Gagal memuat pesanan</p>';
            });
            
            // Load in-progress orders
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: 'action=getInProgressOrders'
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('in-progress-orders');
                if (data.error) {
                    container.innerHTML = `<p class="text-red-500 text-center">${data.error}</p>`;
                    return;
                }
                
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center">Tidak ada pesanan sedang diproses</p>';
                    document.getElementById('in-progress-count').textContent = '0';
                    return;
                }
                
                let html = '';
                data.forEach(order => {
                    const orderTime = new Date(order.tanggal_pesanan).toLocaleString('id-ID');
                    
                    html += `
                    <div class="order-card" data-id="${order.id_pesanan}">
                        <div class="order-card-header">
                            <span class="order-id">Pesanan #${order.id_pesanan}</span>
                            <span class="order-time">${orderTime}</span>
                        </div>
                        <div class="order-items">${order.items}</div>
                        <div class="flex justify-end">
                            <button class="order-action-btn btn-complete" 
                                    onclick="updateOrderStatus(${order.id_pesanan}, 'selesai')">
                                Selesai
                            </button>
                        </div>
                    </div>`;
                });
                
                container.innerHTML = html;
                document.getElementById('in-progress-count').textContent = data.length;
                
                // Update summary counts
                const totalPending = parseInt(document.getElementById('pending-count').textContent) || 0;
                const totalInProgress = data.length;
                document.getElementById('total-orders-count').textContent = totalPending + totalInProgress;
                document.getElementById('in-queue-count').textContent = totalPending;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('in-progress-orders').innerHTML = '<p class="text-red-500 text-center">Gagal memuat pesanan</p>';
            });
        }

        // Load order history data
        function loadOrderHistory() {
            fetch('../auth/koki/auth_koki.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=getOrderHistory'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                const historyContainer = document.getElementById('history-orders');
                const noHistoryMessage = document.getElementById('no-history-message');
                
                if (data.length === 0) {
                    noHistoryMessage.style.display = '';
                    historyContainer.innerHTML = '';
                    document.getElementById('completed-history-count').textContent = '0';
                    return;
                }

                noHistoryMessage.style.display = 'none';
                document.getElementById('completed-history-count').textContent = data.length;
                
                let html = '';
                data.forEach(order => {
                    const orderDate = new Date(order.tanggal_pesanan);
                    const formattedDate = orderDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    html += `
                        <div class="card order-card">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold">Pesanan #${order.id_pesanan}</h4>
                                <span class="order-status-badge status-completed">Selesai</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">${formattedDate}</p>
                            <p class="text-sm text-gray-700 mb-2">${order.items}</p>
                            <p class="text-sm font-semibold">Total: Rp${order.total ? order.total.toLocaleString('id-ID') : '0'}</p>
                            <div class="flex justify-end mt-2">
                                <button class="btn btn-sm btn-secondary" 
                                        onclick="showOrderDetails(${order.id_pesanan}, '${formattedDate}', '${order.items.replace(/'/g, "\\'")}', ${order.total || 0})">
                                    Detail
                                </button>
                            </div>
                        </div>
                    `;
                });

                historyContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Update order status
        function updateOrderStatus(orderId, newStatus) {
            const confirmationMessage = `Apakah Anda yakin ingin mengubah status pesanan #${orderId} menjadi ${newStatus}?`;
            
            // Create confirmation modal dynamically
            const modalContent = `
                <div class="confirmation-title">Konfirmasi</div>
                <div class="confirmation-message">${confirmationMessage}</div>
                <div class="confirmation-buttons">
                    <button class="confirmation-button cancel" onclick="hideCustomConfirmationModal()">Batal</button>
                    <button class="confirmation-button confirm" onclick="proceedWithStatusUpdate(${orderId}, '${newStatus}')">Ya, Lanjutkan</button>
                </div>
            `;
            
            const modal = document.createElement('div');
            modal.className = 'confirmation-modal';
            modal.id = 'customConfirmationModal';
            modal.innerHTML = `
                <div class="confirmation-content">
                    ${modalContent}
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.style.display = 'flex';
            
            // Add functions to window object
            window.hideCustomConfirmationModal = function() {
                modal.style.display = 'none';
                setTimeout(() => {
                    modal.remove();
                }, 300);
            };
            
            window.proceedWithStatusUpdate = function(orderId, newStatus) {
                hideCustomConfirmationModal();
                
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                    body: `action=updateOrderStatus&order_id=${orderId}&new_status=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(`Status pesanan #${orderId} berhasil diubah`);
                        loadOrderQueue();
                    } else {
                        showNotification(data.error || 'Gagal mengubah status pesanan', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Gagal mengubah status pesanan', 'error');
                });
            };
        }

        // Load menu items
        function loadMenuItems() {
            fetch('dashboard_koki.php?load_menu=1')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('menu-grid').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('menu-grid').innerHTML = '<div class="text-center text-red-500 p-4">Gagal memuat menu</div>';
                });
        }

        // Toggle menu status
        function toggleMenuStatus(id, currentStatus) {
            const newStatus = currentStatus === 'tersedia' ? 'tidak tersedia' : 'tersedia';
            const confirmationMessage = `Apakah Anda yakin ingin mengubah status menu menjadi ${newStatus === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia'}?`;
            
            // Create confirmation modal dynamically
            const modalContent = `
                <div class="confirmation-title">Konfirmasi</div>
                <div class="confirmation-message">${confirmationMessage}</div>
                <div class="confirmation-buttons">
                    <button class="confirmation-button cancel" onclick="hideCustomConfirmationModal()">Batal</button>
                    <button class="confirmation-button confirm" onclick="proceedWithMenuStatusChange(${id}, '${newStatus}')">Ya, Lanjutkan</button>
                </div>
            `;
            
            const modal = document.createElement('div');
            modal.className = 'confirmation-modal';
            modal.id = 'customConfirmationModal';
            modal.innerHTML = `
                <div class="confirmation-content">
                    ${modalContent}
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.style.display = 'flex';
            
            // Add functions to window object
            window.hideCustomConfirmationModal = function() {
                modal.style.display = 'none';
                setTimeout(() => {
                    modal.remove();
                }, 300);
            };
            
            window.proceedWithMenuStatusChange = function(id, newStatus) {
                hideCustomConfirmationModal();
                
                fetch('', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                    body: `action=toggleMenuStatus&menu_id=${id}&new_status=${newStatus}`
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showNotification(`Status menu berhasil diubah menjadi ${newStatus === 'tersedia' ? 'Tersedia' : 'Tidak Tersedia'}`);
                        loadMenuItems();
                    } else {
                        showNotification('Gagal mengubah status menu', 'error');
                    }
                })
                .catch(e => {
                    console.error('Error:', e);
                    showNotification('Gagal mengubah status menu', 'error');
                });
            };
        }

        // Search menu
        function searchMenu() {
            const searchTerm = document.getElementById('search-menu').value.toLowerCase();
            const items = document.querySelectorAll('.menu-item');
            
            let hasVisibleItems = false;
            
            items.forEach(item => {
                const menuName = item.querySelector('h4').textContent.toLowerCase();
                if (menuName.includes(searchTerm)) {
                    item.style.display = '';
                    hasVisibleItems = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (!hasVisibleItems) {
                document.querySelector('.empty-state')?.remove();
                const emptyMsg = document.createElement('div');
                emptyMsg.className = 'empty-state';
                emptyMsg.innerHTML = '<p>Tidak ada menu yang cocok</p>';
                document.getElementById('menu-grid').appendChild(emptyMsg);
            }
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {
            loadOrderQueue();
        });
    </script>
</body>
</html>