<?php
require_once '../auth/pelanggan/auth_pelanggan.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan</title>
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
            cursor: pointer;
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .card {
            background-color: #ffffff;
            border-radius: 0.8rem;
            padding: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #e5e7eb;
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            padding: 0.5rem 0;
            margin-top: 1rem;
            border-top: 2px solid #000;
        }
        .menu-item {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            background: white;
            cursor: pointer;
        }
        .menu-item:hover {
            border-color: #9ca3af;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .order-form {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .cart-total {
            font-weight: bold;
            margin-top: 1rem;
            padding-top: 0.5rem;
            border-top: 2px solid #000;
        }
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
        .cart-section {
            height: 50vh;
            overflow-y: auto;
        }
        .menu-cart-container {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            flex: 1;
        }
        .menu-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .cart-sidebar {
            width: 350px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }
        .empty-state svg {
            margin: 0 auto 1rem;
            width: 4rem;
            height: 4rem;
            color: #d1d5db;
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
        /* Star Rating */
        .star-rating {
            display: flex;
            gap: 0.2rem;
            justify-content: center;
            margin: 1rem 0;
            direction: ltr;
        }
        .star-rating input[type="radio"] {
            display: none;
        }
        .star-rating label {
            cursor: pointer;
            font-size: 2rem;
            color: #d1d5db;
            transition: all 0.2s ease-in-out;
        }
        .star-rating.hover-1 label:nth-child(-n+2),
        .star-rating.hover-2 label:nth-child(-n+4),
        .star-rating.hover-3 label:nth-child(-n+6),
        .star-rating.hover-4 label:nth-child(-n+8),
        .star-rating.hover-5 label:nth-child(-n+10) {
            color: #fbbf24;
            text-shadow: 0 0 5px rgba(251, 191, 36, 0.5);
            transform: scale(1.1);
        }
        .star-rating.selected-1 label:nth-child(-n+2),
        .star-rating.selected-2 label:nth-child(-n+4),
        .star-rating.selected-3 label:nth-child(-n+6),
        .star-rating.selected-4 label:nth-child(-n+8),
        .star-rating.selected-5 label:nth-child(-n+10) {
            color: #fbbf24;
            text-shadow: 0 0 5px rgba(251, 191, 36, 0.5);
            transform: scale(1.1);
        }
        .rating-section {
            background: linear-gradient(135deg, #f3f4f6 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e5e7eb;
        }
        .rating-form {
            max-width: 500px;
            margin: 0 auto;
        }
        .order-select {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            transition: border-color 0.2s ease;
            font-size: 0.95rem;
            cursor: pointer;
        }
        .order-select:focus, .order-select:active {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .order-select:disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }
        .rating-emoji {
            text-align: center;
            font-size: 3rem;
            margin: 1rem 0;
            height: 4rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .rating-text {
            text-align: center;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
        }
        /* Confirmation Modal */
        .confirmation-modal {
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
        .confirmation-modal-content {
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
            <h1 class="text-xl font-bold mb-8 text-center">Dashboard Pelanggan</h1>
            <nav class="flex-grow">
                <a href="#" class="active" onclick="showSection('create-order')">Buat Pesanan</a>
                <a href="#" onclick="showSection('orders')">Lihat Pesanan</a>
                <a href="#" onclick="showSection('rating')">Beri Rating</a>
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
                    <h2 class="text-2xl font-bold text-gray-800" id="main-dashboard-title">Buat Pesanan</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                        <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'P'; ?>
                    </div>
                    <span class="text-gray-700 font-medium text-sm">
                        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pelanggan'; ?>
                    </span>
                </div>
            </header>

            <!-- Create Order Section -->
            <section id="create-order" class="dashboard-section">
                <div class="card">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Buat Pesanan</h3>
                    <div class="menu-cart-container">
                        <!-- Menu Section -->
                        <div class="menu-content">
                            <div class="menu-tabs">
                                <div class="menu-tab active" onclick="changeMenuTab('food')">Makanan</div>
                                <div class="menu-tab" onclick="changeMenuTab('drink')">Minuman</div>
                                <div class="menu-tab" onclick="changeMenuTab('snack')">Snack</div>
                            </div>
                            <div class="menu-section">
                                <div id="food-items" class="menu-tab-content">
                                    <div class="menu-grid" id="food-menu-grid">
                                        <p class="text-gray-500 text-center">Memuat menu makanan...</p>
                                    </div>
                                </div>
                                <div id="drink-items" class="menu-tab-content hidden">
                                    <div class="menu-grid" id="drink-menu-grid">
                                        <p class="text-gray-500 text-center">Memuat menu minuman...</p>
                                    </div>
                                </div>
                                <div id="snack-items" class="menu-tab-content hidden">
                                    <div class="menu-grid" id="snack-menu-grid">
                                        <p class="text-gray-500 text-center">Memuat menu snack...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Keranjang Section -->
                        <div class="cart-sidebar">
                            <div class="card flex-1 flex flex-col">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-semibold text-lg">Keranjang Pesanan</h4>
                                    <button class="btn btn-danger text-sm" onclick="showClearCartConfirmation()" id="clear-cart-btn" disabled>
                                        Kosongkan
                                    </button>
                                </div>
                                <div class="cart-section" id="cart-items">
                                    <div class="empty-state">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p>Keranjang kosong</p>
                                        <p class="text-sm mt-1">Tambahkan item dari menu</p>
                                    </div>
                                </div>
                                <div id="cart-total" class="hidden mt-2">
                                    <div class="order-total">
                                        <div>Total</div>
                                        <div>Rp <span id="total-amount">0</span></div>
                                    </div>
                                    <div class="mt-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-1" for="table-select">No. Meja</label>
                                        <select id="table-select" class="order-select">
                                            <option value="">-- Pilih Meja --</option>
                                        </select>
                                        <small class="text-gray-500 text-xs">Meja hanya bisa dipilih sekali per sesi.</small>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4">
                                    <button id="place-order-btn" class="btn btn-primary w-full hidden" onclick="placeOrder()">
                                        Pesan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Orders Section -->
            <section id="orders" class="dashboard-section hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Daftar Pesanan Anda</h3>
                    <button class="btn btn-primary" onclick="refreshOrders()">Refresh Pesanan</button>
                </div>
                <div class="card">
                    <div id="orders-content">
                        <div class="empty-state">
                            <p>Memuat daftar pesanan...</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Rating Section -->
            <section id="rating" class="dashboard-section hidden">
                <div class="rating-section">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Beri Rating Pengalaman Anda</h3>
                    <div class="rating-form">
                        <form id="rating-form">
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-3 text-center">Berikan Rating (1-5 Bintang)</label>
                                <div class="rating-emoji" id="rating-emoji">😐</div>
                                <div class="star-rating" id="star-rating">
                                    <input type="radio" id="star1" name="rating" value="1" />
                                    <label for="star1" title="Sangat Kurang">★</label>
                                    <input type="radio" id="star2" name="rating" value="2" />
                                    <label for="star2" title="Kurang">★</label>
                                    <input type="radio" id="star3" name="rating" value="3" />
                                    <label for="star3" title="Cukup">★</label>
                                    <input type="radio" id="star4" name="rating" value="4" />
                                    <label for="star4" title="Puas">★</label>
                                    <input type="radio" id="star5" name="rating" value="5" />
                                    <label for="star5" title="Sangat Puas">★</label>
                                </div>
                                <div class="rating-text" id="rating-text">Pilih rating Anda</div>
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="comment">
                                    Komentar & Saran <span class="text-gray-400 font-normal">(Opsional)</span>
                                </label>
                                <textarea id="comment" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" rows="4" placeholder="Ceritakan pengalaman Anda..."></textarea>
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary px-8 py-3 text-lg font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5" type="submit" id="submit-rating-btn">
                                    💬 Kirim Rating & Feedback
                                </button>
                            </div>
                        </form>
                        <div id="rating-message" class="mt-4 p-4 rounded-lg text-center font-medium hidden"></div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container" id="toast-container"></div>

    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Logout</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="flex justify-center space-x-4">
                <button class="btn btn-secondary" onclick="closeLogoutModal()">Batal</button>
                <button class="btn btn-danger" onclick="confirmLogout()">Ya, Logout</button>
            </div>
        </div>
    </div>

    <!-- Clear Cart Confirmation Modal -->
    <div id="clearCartModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Kosongkan Keranjang</h3>
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin mengosongkan keranjang belanja?</p>
            <div class="flex justify-center space-x-4">
                <button class="btn btn-secondary" onclick="closeClearCartModal()">Batal</button>
                <button class="btn btn-danger" onclick="clearCart()">Ya, Kosongkan</button>
            </div>
        </div>
    </div>

    <!-- Rating Confirmation Modal -->
    <div id="ratingConfirmModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Rating</h3>
            <p class="text-gray-600 mb-6">Anda hanya bisa memberikan rating satu kali. Apakah Anda yakin ingin mengirimkan rating ini?</p>
            <div class="flex justify-center space-x-4">
                <button class="btn btn-secondary" onclick="closeRatingConfirmModal()">Batal</button>
                <button class="btn btn-primary" onclick="submitRating()">Kirim</button>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let menuItems = { food: [], drink: [], snack: [] };
        let currentOrderId = null;
        let currentTableId = null;
        let currentRatedOrderId = null;

        // Toast Notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            toastContainer.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Load current table from session
        function loadCurrentTable() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=getCurrentTable'
            })
            .then(r => r.json())
            .then(data => {
                if (data.table_id) {
                    currentTableId = data.table_id;
                    loadTableSelection();
                } else {
                    currentTableId = null;
                    loadTableSelection();
                }
            })
            .catch(e => {
                console.error('Gagal cek meja saat load:', e);
                currentTableId = null;
                loadTableSelection();
            });
        }

        // Load table selection
        function loadTableSelection() {
            const select = document.getElementById('table-select');
            if (currentTableId) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'action=getTableById&id=' + currentTableId
                })
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        showToast('Meja tidak ditemukan', 'error');
                        currentTableId = null;
                        loadTableSelection();
                        return;
                    }
                    select.innerHTML = `<option value="${data.id_meja}">Meja ${data.nomor_meja}</option>`;
                    select.disabled = true;
                })
                .catch(e => {
                    console.error('Gagal muat detail meja:', e);
                    select.innerHTML = '<option value="">-- Gagal muat --</option>';
                    select.disabled = true;
                });
            } else {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'action=getAvailableTables'
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        select.innerHTML = '<option value="">-- Tidak ada meja tersedia --</option>';
                        select.disabled = true;
                        return;
                    }
                    select.innerHTML = '<option value="">-- Pilih Meja --</option>';
                    data.forEach(table => {
                        const option = document.createElement('option');
                        option.value = table.id_meja;
                        option.textContent = `Meja ${table.nomor_meja}`;
                        select.appendChild(option);
                    });
                    select.disabled = false;
                    select.onchange = function () {
                        const selectedId = parseInt(this.value);
                        if (selectedId) {
                            select.disabled = true;
                            fetch('', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: `action=selectTable&table_id=${selectedId}`
                            })
                            .then(r => r.json())
                            .then(result => {
                                if (result.success) {
                                    currentTableId = result.table_id;
                                    showToast('Meja berhasil dipilih');
                                    loadTableSelection();
                                } else {
                                    showToast(result.error, 'error');
                                    this.value = '';
                                    this.disabled = false;
                                }
                            })
                            .catch(() => {
                                showToast('Gagal menyimpan meja', 'error');
                                this.value = '';
                                this.disabled = false;
                            });
                        }
                    };
                })
                .catch(e => {
                    console.error('Gagal muat meja:', e);
                    select.innerHTML = '<option value="">-- Gagal muat --</option>';
                    select.disabled = true;
                });
            }
        }

        // Show Section
        function showSection(sectionId) {
            document.querySelectorAll('.dashboard-section').forEach(s => s.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');
            document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
            const linkMap = {
                'create-order': () => {
                    document.querySelector(`.sidebar a[onclick="showSection('create-order')"]`).classList.add('active');
                    document.getElementById('main-dashboard-title').textContent = 'Buat Pesanan';
                    loadMenuItems();
                    loadTableSelection();
                },
                'orders': () => {
                    document.querySelector(`.sidebar a[onclick="showSection('orders')"]`).classList.add('active');
                    document.getElementById('main-dashboard-title').textContent = 'Lihat Pesanan';
                    loadOrders();
                },
                'rating': () => {
                    document.querySelector(`.sidebar a[onclick="showSection('rating')"]`).classList.add('active');
                    document.getElementById('main-dashboard-title').textContent = 'Beri Rating';
                    loadCurrentOrderForRating();
                }
            };
            if (linkMap[sectionId]) linkMap[sectionId]();
        }

        // Change Menu Tab
        function changeMenuTab(category) {
            ['food', 'drink', 'snack'].forEach(cat => {
                document.getElementById(`${cat}-items`).classList.add('hidden');
                document.querySelector(`.menu-tab[onclick="changeMenuTab('${cat}')"]`).classList.remove('active');
            });
            document.getElementById(`${category}-items`).classList.remove('hidden');
            document.querySelector(`.menu-tab[onclick="changeMenuTab('${category}')"]`).classList.add('active');
        }

        // Load Menu Items
        function loadMenuItems() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=getMenuItems'
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    ['food', 'drink', 'snack'].forEach(cat => {
                        document.getElementById(`${cat}-menu-grid`).innerHTML = `<div class="empty-state"><p>${data.error}</p></div>`;
                    });
                    return;
                }
                menuItems.food = data.filter(m => m.kategori === 'makanan');
                menuItems.drink = data.filter(m => m.kategori === 'minuman');
                menuItems.snack = data.filter(m => m.kategori === 'snack');

                ['food', 'drink', 'snack'].forEach(renderMenuItems);
            })
            .catch(() => {
                ['food', 'drink', 'snack'].forEach(cat => {
                    document.getElementById(`${cat}-menu-grid`).innerHTML = `<div class="empty-state"><p>Gagal memuat menu</p></div>`;
                });
            });
        }

        // Render Menu Items
        function renderMenuItems(category) {
            const container = document.getElementById(`${category}-menu-grid`);
            const items = menuItems[category];
            if (!items.length) {
                container.innerHTML = `<div class="empty-state"><p>Tidak ada ${category} tersedia.</p></div>`;
                return;
            }
            const fragment = document.createDocumentFragment();
            items.forEach(item => {
                const div = document.createElement('div');
                div.className = 'menu-item';
                div.innerHTML = `
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold">${item.nama_menu}</h4>
                            <p class="text-gray-600">Rp ${item.harga.toLocaleString('id-ID')}</p>
                        </div>
                        <button class="btn btn-primary text-sm">Pesan</button>
                    </div>
                    <div id="order-form-${category}-${item.id_menu}" class="order-form hidden">
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm font-bold mb-1">Jumlah</label>
                            <input type="number" id="quantity-${category}-${item.id_menu}" class="shadow border rounded w-20 py-1 px-2 text-gray-700" min="1" value="1">
                        </div>
                        <div class="mb-3">
                            <label class="block text-gray-700 text-sm font-bold mb-1">Catatan</label>
                            <textarea id="note-${category}-${item.id_menu}" class="shadow border rounded w-full py-1 px-2 text-gray-700" rows="2" placeholder="Contoh: Pedas, Tanpa bawang"></textarea>
                        </div>
                        <div class="flex justify-between">
                            <button class="btn btn-danger text-sm cancel-btn">Batal</button>
                            <button class="btn btn-primary text-sm add-btn">Tambah</button>
                        </div>
                    </div>
                `;
                fragment.appendChild(div);
            });
            container.innerHTML = '';
            container.appendChild(fragment);

            items.forEach(item => {
                const form = document.getElementById(`order-form-${category}-${item.id_menu}`);
                form.parentElement.addEventListener('click', () => {
                    document.querySelectorAll('.order-form').forEach(f => f.classList.add('hidden'));
                    form.classList.toggle('hidden');
                });
                form.querySelector('.add-btn').addEventListener('click', () => {
                    const qty = parseInt(document.getElementById(`quantity-${category}-${item.id_menu}`).value);
                    const note = document.getElementById(`note-${category}-${item.id_menu}`).value.trim();
                    if (qty < 1) return showToast('Jumlah minimal 1', 'error');
                    const idx = cart.findIndex(c => c.id_menu === item.id_menu && c.catatan === note);
                    if (idx >= 0) cart[idx].kuantitas += qty;
                    else cart.push({ ...item, kuantitas: qty, catatan: note });
                    form.classList.add('hidden');
                    updateCartDisplay();
                    showToast(`${item.nama_menu} ditambahkan ke keranjang`);
                });
                form.querySelector('.cancel-btn').addEventListener('click', () => {
                    form.classList.add('hidden');
                });
            });
        }

        // Update Cart Display
        function updateCartDisplay() {
            const cartEl = document.getElementById('cart-items');
            const totalEl = document.getElementById('cart-total');
            const btn = document.getElementById('place-order-btn');
            const clearBtn = document.getElementById('clear-cart-btn');
            if (cart.length === 0) {
                cartEl.innerHTML = `<div class="empty-state"><p>Keranjang kosong</p></div>`;
                totalEl.classList.add('hidden');
                btn.classList.add('hidden');
                clearBtn.disabled = true;
                return;
            }
            let total = 0;
            const grouped = { makanan: [], minuman: [], snack: [] };
            cart.forEach(item => {
                const sub = item.harga * item.kuantitas;
                total += sub;
                grouped[item.kategori].push({ ...item, subtotal: sub });
            });
            let html = '';
            Object.keys(grouped).forEach(cat => {
                if (grouped[cat].length) {
                    html += `<div class="font-medium text-gray-700 mb-2">${cat.charAt(0).toUpperCase() + cat.slice(1)}</div>`;
                    grouped[cat].forEach((item, index) => {
                        const cartIdx = cart.findIndex(c => c.id_menu === item.id_menu && c.catatan === item.catatan);
                        html += `
                            <div class="cart-item">
                                <div>
                                    <div class="font-medium">${item.nama_menu}</div>
                                    <div class="text-sm text-gray-600">${item.kuantitas} x Rp ${item.harga.toLocaleString('id-ID')}</div>
                                    ${item.catatan ? `<div class="text-xs text-gray-500">Catatan: ${item.catatan}</div>` : ''}
                                </div>
                                <div class="flex items-center">
                                    <input type="number" class="w-16 border rounded py-1 px-2 mr-2" min="1" value="${item.kuantitas}" onchange="updateCartItem(${cartIdx}, parseInt(this.value))">
                                    <button class="text-red-500 hover:text-red-700" onclick="removeFromCart(${cartIdx})">🗑️</button>
                                </div>
                            </div>`;
                    });
                }
            });
            cartEl.innerHTML = html;
            document.getElementById('total-amount').textContent = total.toLocaleString('id-ID');
            totalEl.classList.remove('hidden');
            btn.classList.remove('hidden');
            clearBtn.disabled = false;
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
            showToast('Item dihapus dari keranjang');
        }

        function updateCartItem(index, qty) {
            if (qty < 1) removeFromCart(index);
            else cart[index].kuantitas = qty;
            updateCartDisplay();
        }

        function showClearCartConfirmation() {
            document.getElementById('clearCartModal').style.display = 'flex';
        }

        function closeClearCartModal() {
            document.getElementById('clearCartModal').style.display = 'none';
        }

        function clearCart() {
            cart = [];
            updateCartDisplay();
            showToast('Keranjang dikosongkan');
            closeClearCartModal();
        }

        function validateCart() {
            if (cart.length === 0) {
                showToast('Keranjang kosong', 'error');
                return false;
            }
            if (!currentTableId) {
                showToast('Pilih meja terlebih dahulu', 'error');
                return false;
            }
            return true;
        }

        function placeOrder() {
            if (!validateCart()) return;
            const btn = document.getElementById('place-order-btn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Memproses...';
            const cleanCart = cart.map(item => ({
                id_menu: item.id_menu,
                kuantitas: item.kuantitas,
                catatan: item.catatan || ''
            }));
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    action: 'createOrder',
                    items: JSON.stringify(cleanCart),
                    table_id: currentTableId,
                    order_id: currentOrderId || ''
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Pesanan berhasil dibuat!');
                    cart = [];
                    currentOrderId = data.order_id;
                    updateCartDisplay();
                    setTimeout(() => showSection('orders'), 1000);
                } else {
                    showToast(data.error, 'error');
                }
            })
            .catch(() => showToast('Gagal terhubung ke server', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = orig;
            });
        }

        function refreshOrders() {
            loadOrders();
        }

        function loadOrders() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=getOrders'
            })
            .then(r => r.json())
            .then(orders => {
                const container = document.getElementById('orders-content');
                if (!orders.length || orders.error) {
                    container.innerHTML = `<div class="empty-state"><p>Tidak ada pesanan</p></div>`;
                    return;
                }
                let html = '';
                let currentOrder = null;
                orders.forEach(item => {
                    if (currentOrder !== item.id_pesanan) {
                        if (currentOrder !== null) html += '</div>';
                        currentOrder = item.id_pesanan;
                        currentOrderId = item.id_pesanan;
                        const date = new Date(item.tanggal_pesanan).toLocaleString('id-ID');
                        html += `<div class="mb-4 p-4 bg-gray-50 rounded"><strong>Pesanan #${item.id_pesanan}</strong> (${date}) - ${item.status_pesanan}<div class="mt-2">`;
                    }
                    html += `<div class="order-item">
                                <div>${item.nama_menu} × ${item.kuantitas}</div>
                                <div>Rp ${(item.harga * item.kuantitas).toLocaleString('id-ID')}</div>
                            </div>`;
                });
                if (currentOrder) html += '</div></div>';
                container.innerHTML = html;
            })
            .catch(() => showToast('Gagal muat pesanan', 'error'));
        }

        function loadCurrentOrderForRating() {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'action=getOrdersForRating'
            })
            .then(r => r.json())
            .then(orders => {
                if (!orders.length) {
                    showToast('Tidak ada pesanan selesai untuk dirating', 'error');
                    document.getElementById('rating-form').classList.add('hidden');
                    return;
                }
                const order = orders[0];
                currentRatedOrderId = order.id_pesanan;
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `action=getRating&order_id=${order.id_pesanan}`
                })
                .then(r => r.json())
                .then(data => {
                    if (data.rating > 0) {
                        showToast('Anda sudah memberikan rating untuk pesanan ini.', 'error');
                        document.getElementById('rating-form').classList.add('hidden');
                    } else {
                        document.getElementById('rating-form').classList.remove('hidden');
                        updateRatingDisplay(0);
                        document.getElementById('comment').value = '';
                    }
                });
            })
            .catch(() => showToast('Gagal memuat pesanan untuk rating', 'error'));
        }

        function updateRatingDisplay(rating) {
            const emoji = document.getElementById('rating-emoji');
            const text = document.getElementById('rating-text');
            const starContainer = document.getElementById('star-rating');
            const map = {
                0: { e: '😐', t: 'Pilih rating Anda', c: '#6b7280' },
                1: { e: '😞', t: 'Sangat Kurang', c: '#ef4444' },
                2: { e: '😕', t: 'Kurang', c: '#f97316' },
                3: { e: '😊', t: 'Cukup', c: '#eab308' },
                4: { e: '😃', t: 'Puas', c: '#22c55e' },
                5: { e: '🤩', t: 'Sangat Puas!', c: '#22c55e' }
            };
            const d = map[rating] || map[0];
            emoji.textContent = d.e;
            text.textContent = d.t;
            text.style.color = d.c;
            starContainer.className = 'star-rating';
            if (rating > 0) starContainer.classList.add(`selected-${rating}`);
        }

        function initStarRating() {
            const starContainer = document.getElementById('star-rating');
            const labels = starContainer.querySelectorAll('label');
            const inputs = starContainer.querySelectorAll('input');
            function resetClasses() {
                starContainer.classList.remove('hover-1', 'hover-2', 'hover-3', 'hover-4', 'hover-5');
            }
            labels.forEach((label, index) => {
                const value = index + 1;
                label.addEventListener('mouseenter', () => {
                    resetClasses();
                    starContainer.classList.add(`hover-${value}`);
                });
                label.addEventListener('click', () => {
                    resetClasses();
                    starContainer.classList.add(`selected-${value}`);
                    updateRatingDisplay(value);
                });
            });
            starContainer.addEventListener('mouseleave', () => {
                resetClasses();
                const checked = document.querySelector('input[name="rating"]:checked');
                if (checked) starContainer.classList.add(`selected-${checked.value}`);
            });
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    resetClasses();
                    if (input.checked) {
                        starContainer.classList.add(`selected-${input.value}`);
                        updateRatingDisplay(parseInt(input.value));
                    }
                });
            });
        }

        function openLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            fetch('../logout.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=logout'
            })
            .then(() => {
                window.location.href = '../login.php';
            })
            .catch(() => {
                window.location.href = '../login.php';
            });
        }

        function openRatingConfirmModal() {
            document.getElementById('ratingConfirmModal').style.display = 'flex';
        }

        function closeRatingConfirmModal() {
            document.getElementById('ratingConfirmModal').style.display = 'none';
        }

        function submitRating() {
            const rating = document.querySelector('input[name="rating"]:checked');
            if (!rating) return showToast('Pilih rating terlebih dahulu', 'error');
            const comment = document.getElementById('comment').value.trim();
            const btn = document.getElementById('submit-rating-btn');
            const orig = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Mengirim...';
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `action=saveRating&rating=${rating.value}&comment=${encodeURIComponent(comment)}&order_id=${currentRatedOrderId}`
            })
            .then(r => r.json())
            .then(data => {
                const msg = document.getElementById('rating-message');
                if (data.success) {
                    msg.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-green-100 text-green-800';
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                    document.getElementById('rating-form').classList.add('hidden');
                    showToast('Terima kasih atas rating Anda!', 'success');
                } else {
                    msg.className = 'mt-4 p-4 rounded-lg text-center font-medium bg-red-100 text-red-800';
                    msg.textContent = data.error;
                    msg.classList.remove('hidden');
                    setTimeout(() => msg.classList.add('hidden'), 5000);
                }
            })
            .catch(() => showToast('Gagal mengirim rating', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = orig;
                closeRatingConfirmModal();
            });
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('logoutBtn').addEventListener('click', openLogoutModal);
            ['logoutModal', 'clearCartModal', 'ratingConfirmModal'].forEach(id => {
                window.onclick = e => {
                    if (e.target.id === id) document.getElementById(id).style.display = 'none';
                };
            });
            document.getElementById('rating-form').addEventListener('submit', e => {
                e.preventDefault();
                openRatingConfirmModal();
            });
            initStarRating();
            loadCurrentTable();
            showSection('create-order');
        });
    </script>
</body>
</html>