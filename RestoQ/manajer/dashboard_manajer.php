<?php
require_once '../auth/manajer/auth_manajer.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manajer</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            font-size: 0.85rem;
            border: none;
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
        .stat-card {
            padding: 1.5rem;
            border-radius: 0.5rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
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
    <!-- Toast Notification -->
    <div class="toast-container" id="toast-container"></div>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1 class="text-xl font-bold mb-6 text-center">Dashboard Manajer</h1>
            <nav>
                <a href="#" class="active" onclick="showSection('summary')">Ringkasan Harian</a>
                <a href="#" onclick="showSection('revenue')">Grafik Pendapatan</a>
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
                    <h2 class="text-2xl font-bold text-gray-800" id="main-dashboard-title">Ringkasan Harian</h2>
                </div>
                <div class="flex items-center">
                    <div class="w-9 h-9 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-2">
                        <?php echo isset($_SESSION['username']) ? strtoupper(substr($_SESSION['username'], 0, 1)) : 'M'; ?>
                    </div>
                    <span class="text-gray-700 font-medium text-sm">
                        <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Manajer'; ?>
                    </span>
                </div>
            </header>

            <!-- Summary Section -->
            <section id="summary" class="dashboard-section">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Ringkasan Harian</h3>
                    <button class="btn btn-secondary" onclick="refreshSummary()">Refresh Data</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="stat-card">
                        <div class="stat-label">Total Pendapatan Hari Ini</div>
                        <div class="stat-value" id="total-revenue">Rp 0</div>
                        <div class="text-sm text-gray-500" id="current-date"></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Jumlah Transaksi Hari Ini</div>
                        <div class="stat-value" id="total-transactions">0</div>
                        <div class="text-sm text-gray-500" id="current-date-2"></div>
                    </div>
                </div>
            </section>

            <!-- Revenue Chart Section -->
            <section id="revenue" class="dashboard-section hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-800">Grafik Pendapatan</h3>
                    <div>
                        <select id="period-select" class="border rounded px-3 py-2" onchange="loadRevenueData()">
                            <option value="week">Mingguan (7 Hari)</option>
                            <option value="month">Bulanan (12 Bulan)</option>
                            <option value="year">Tahunan (5 Tahun)</option>
                        </select>
                    </div>
                </div>
                <div class="card">
                    <canvas id="revenue-chart" height="400"></canvas>
                </div>
            </section>
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
        // Chart instance
        let revenueChart = null;

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
            if (sectionId === 'summary') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('summary')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Ringkasan Harian';
                loadDailySummary();
            } else if (sectionId === 'revenue') {
                targetLink = document.querySelector(`.sidebar a[onclick="showSection('revenue')"]`);
                document.getElementById('main-dashboard-title').textContent = 'Grafik Pendapatan';
                loadRevenueData();
            }

            if (targetLink) {
                targetLink.classList.add('active');
            }
        }

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

        // Daily Summary functions
        function loadDailySummary() {
            fetch('../auth/manajer/auth_manajer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=getDailySummary'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('API Error:', data.error);
                    showToast('Gagal: ' + data.error, 'error');
                    return;
                }
                document.getElementById('total-revenue').textContent = 'Rp ' + (data.total_pendapatan || 0).toLocaleString('id-ID');
                document.getElementById('total-transactions').textContent = data.jumlah_transaksi || 0;
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showToast('Gagal memuat ringkasan harian', 'error');
            });
        }

        function refreshSummary() {
            loadDailySummary();
            showToast('Data ringkasan diperbarui');
        }

        // Revenue Chart functions
        function loadRevenueData() {
            const period = document.getElementById('period-select').value;

            fetch('../auth/manajer/auth_manajer.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=getRevenueData&period=${period}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('API Error:', data.error);
                    showToast('Gagal: ' + data.error, 'error');
                    return;
                }
                updateRevenueChart(data.labels, data.values);
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                showToast('Gagal memuat data pendapatan', 'error');
            });
        }

        function updateRevenueChart(labels, values) {
            const ctx = document.getElementById('revenue-chart').getContext('2d');

            // Hancurkan chart sebelumnya jika ada
            if (revenueChart) {
                revenueChart.destroy();
            }

            revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: values,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // Logout functions
        document.getElementById('logoutBtn').addEventListener('click', function() {
            document.getElementById('logoutModal').style.display = 'flex';
        });

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function confirmLogout() {
            fetch('../logout.php', {
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
                        window.location.href = '../login.php';
                    }, 1000);
                } else {
                    showToast('Logout gagal', 'error');
                }
            })
            .catch(error => {
                console.error('Error logout:', error);
                showToast('Terjadi kesalahan saat logout', 'error');
                setTimeout(() => {
                    window.location.href = '../login.php';
                }, 1500);
            });
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal hari ini
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const today = new Date().toLocaleDateString('id-ID', options);
            document.getElementById('current-date').textContent = today;
            document.getElementById('current-date-2').textContent = today;

            // Load section default (summary)
            showSection('summary');
        });
    </script>
</body>
</html>