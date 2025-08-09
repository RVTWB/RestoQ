// Array to store order data (for demo purposes)
let orders = [];
let bahanData = []; // Array untuk menyimpan data bahan
let nextBahanId = 1; // ID unik untuk bahan

// --- Fungsi Bahan ---

function showAddBahanForm() {
    document.getElementById('bahanModalTitle').textContent = 'Tambah Bahan';
    document.getElementById('bahanForm').reset();
    document.getElementById('bahanId').value = '';
    clearBahanErrors();
    document.getElementById('bahanModal').style.display = 'flex';
}

function showEditBahanForm(id, nama, stok, satuan) {
    document.getElementById('bahanModalTitle').textContent = 'Edit Bahan';
    document.getElementById('bahanId').value = id;
    document.getElementById('namaBahan').value = nama;
    document.getElementById('stok').value = stok;
    document.getElementById('satuan').value = satuan;
    clearBahanErrors();
    document.getElementById('bahanModal').style.display = 'flex';
}

function closeBahanModal() {
    document.getElementById('bahanModal').style.display = 'none';
    clearBahanErrors();
}

function showDeleteBahanConfirm(id) {
    document.getElementById('deleteBahanId').value = id;
    document.getElementById('deleteBahanModal').style.display = 'flex';
}

function closeDeleteBahanModal() {
    document.getElementById('deleteBahanModal').style.display = 'none';
}

function confirmDeleteBahan() {
    const id = parseInt(document.getElementById('deleteBahanId').value);
    bahanData = bahanData.filter(b => b.id !== id);
    renderBahanTable();
    closeDeleteBahanModal();
    alert('Bahan berhasil dihapus');
}

function clearBahanErrors() {
    document.getElementById('namaBahanError').textContent = '';
    document.getElementById('stokError').textContent = '';
    document.getElementById('satuanError').textContent = '';
}

// Validasi form bahan
document.getElementById('bahanForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let isValid = true;
    clearBahanErrors();

    const namaBahan = document.getElementById('namaBahan').value.trim();
    const stok = document.getElementById('stok').value;
    const satuan = document.getElementById('satuan').value;

    if (namaBahan === '') {
        document.getElementById('namaBahanError').textContent = 'Nama bahan harus diisi';
        isValid = false;
    }

    if (stok === '' || isNaN(stok) || parseFloat(stok) < 0) {
        document.getElementById('stokError').textContent = 'Stok harus diisi dengan angka tidak negatif';
        isValid = false;
    }

    if (satuan === '') {
        document.getElementById('satuanError').textContent = 'Satuan harus dipilih';
        isValid = false;
    }

    if (isValid) {
        const id = document.getElementById('bahanId').value;
        const namaBahan = document.getElementById('namaBahan').value;
        const stok = parseFloat(document.getElementById('stok').value);
        const satuan = document.getElementById('satuan').value;

        if (id) {
            // Edit data
            const index = bahanData.findIndex(b => b.id === parseInt(id));
            if (index !== -1) {
                bahanData[index] = { id: parseInt(id), nama: namaBahan, stok: stok, satuan: satuan };
            }
        } else {
            // Tambah data
            bahanData.push({ id: nextBahanId++, nama: namaBahan, stok: stok, satuan: satuan });
        }

        renderBahanTable();
        closeBahanModal();
        alert('Data berhasil disimpan');
    }
});

// Pencarian bahan
function searchBahan() {
    const input = document.getElementById('search-bahan');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('bahan-table-body');
    const rows = table.getElementsByTagName('tr');

    let found = false;
    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let rowContainsFilter = false;

        for (let j = 0; j < cells.length - 1; j++) { // Exclude last cell (actions)
            if (cells[j]) {
                const cellText = cells[j].textContent || cells[j].innerText;
                if (cellText.toLowerCase().indexOf(filter) > -1) {
                    rowContainsFilter = true;
                    found = true;
                    break;
                }
            }
        }

        rows[i].style.display = rowContainsFilter ? '' : 'none';
    }

    // Show "no data" message if no rows match
    const noDataRow = document.getElementById('no-bahan-data');
    if (noDataRow) {
        noDataRow.style.display = found ? 'none' : '';
    }
}

// Render tabel bahan
function renderBahanTable() {
    const tbody = document.getElementById('bahan-table-body');
    tbody.innerHTML = '';

    if (bahanData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada data bahan</td></tr>';
        return;
    }

    bahanData.forEach((bahan, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="py-2 px-4 border-b">${index + 1}</td>
            <td class="py-2 px-4 border-b">${bahan.nama}</td>
            <td class="py-2 px-4 border-b">${bahan.stok}</td>
            <td class="py-2 px-4 border-b">${bahan.satuan}</td>
            <td class="py-2 px-4 border-b">
                <button class="btn btn-secondary text-xs mr-2" onclick="showEditBahanForm(${bahan.id}, '${bahan.nama}', ${bahan.stok}, '${bahan.satuan}')">Edit</button>
                <button class="btn btn-danger text-xs" onclick="showDeleteBahanConfirm(${bahan.id})">Hapus</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}


// --- Fungsi Order (dari file sebelumnya) ---

// Function to generate a simple unique ID
function generateUniqueId() {
    return 'ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase();
}

// Function to format date for Order ID
function formatDateForOrderId(date) {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
    const year = String(d.getFullYear()).slice(-2); // Get last two digits of year
    return `${day}${month}${year}`;
}

// Function to add dummy orders (example)
function addDummyOrders() {
    orders.push({
        id: generateUniqueId(),
        customerName: 'Budi Santoso',
        tableNumber: '01',
        orderTime: new Date().toLocaleString(),
        status: 'pending', // pending, confirmed, completed
        items: [
            { name: 'Nasi Goreng Spesial', completed: false, type: 'food' },
            { name: 'Sate Ayam', completed: false, type: 'food' },
            { name: 'Es Teh Manis', completed: false, type: 'drink' },
            { name: 'Kentang Goreng', completed: false, type: 'food' },
            { name: 'Burger Keju', completed: false, type: 'food' },
            { name: 'Soda', completed: false, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Siti Aminah',
        tableNumber: '03',
        orderTime: new Date(Date.now() - 3600000).toLocaleString(), // 1 hour ago
        status: 'confirmed',
        items: [
            { name: 'Mie Ayam Bakso', completed: false, type: 'food' },
            { name: 'Pangsit Goreng', completed: false, type: 'food' },
            { name: 'Jus Alpukat', completed: false, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Joko Susilo',
        tableNumber: '02',
        orderTime: new Date(Date.now() - 7200000).toLocaleString(), // 2 hours ago
        status: 'completed',
        items: [
            { name: 'Capcay Kuah', completed: true, type: 'food' },
            { name: 'Es Jeruk', completed: true, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Dewi Lestari',
        tableNumber: '04',
        orderTime: new Date(Date.now() - 10800000).toLocaleString(), // 3 hours ago
        status: 'pending',
        items: [
            { name: 'Ayam Geprek', completed: false, type: 'food' },
            { name: 'Nasi Putih', completed: false, type: 'food' },
            { name: 'Air Mineral', completed: false, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Ahmad Fauzi',
        tableNumber: '05',
        orderTime: new Date(Date.now() - 86400000 * 2).toLocaleString(), // 2 days ago
        status: 'completed',
        items: [
            { name: 'Sop Buntut', completed: true, type: 'food' },
            { name: 'Teh Hangat', completed: true, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Maria Ulfah',
        tableNumber: '06',
        orderTime: new Date(Date.now() - 86400000 * 35).toLocaleString(), // 35 days ago (last month)
        status: 'completed',
        items: [
            { name: 'Gado-gado', completed: true, type: 'food' },
            { name: 'Es Campur', completed: true, type: 'drink' }
        ]
    });
    orders.push({
        id: generateUniqueId(),
        customerName: 'Rudi Hartono',
        tableNumber: '07',
        orderTime: new Date(Date.now() - 86400000 * 400).toLocaleString(), // 400 days ago (last year)
        status: 'completed',
        items: [
            { name: 'Rawon', completed: true, type: 'food' },
            { name: 'Kerupuk', completed: true, type: 'food' }
        ]
    });
    // Adding more dummy orders to ensure scrollbar appears in history
    for (let i = 8; i <= 20; i++) {
        orders.push({
            id: generateUniqueId(),
            customerName: `Pelanggan ${i}`,
            tableNumber: `T${i}`,
            orderTime: new Date(Date.now() - 86400000 * (i * 5)).toLocaleString(),
            status: 'completed',
            items: [
                { name: `Menu Selesai A${i}`, completed: true, type: 'food' },
                { name: `Minuman Selesai B${i}`, completed: true, type: 'drink' }
            ]
        });
    }
}

// Function to render orders into the DOM
function renderOrders() {
    const pendingOrdersDiv = document.getElementById('pending-orders');
    const inProgressOrdersDiv = document.getElementById('in-progress-orders');
    // Clear previous content
    pendingOrdersDiv.innerHTML = '<p class="text-gray-500 text-center">Tidak ada pesanan menunggu konfirmasi</p>';
    inProgressOrdersDiv.innerHTML = '<p class="text-gray-500 text-center">Tidak ada pesanan sedang diproses</p>';
    let pendingCount = 0;
    let inProgressCount = 0;
    let totalOrdersCount = 0;
    let totalCompletedOrdersCount = 0;
    let totalInQueueCount = 0; // pending + confirmed
    // Sort orders by time (newest first)
    orders.sort((a, b) => new Date(b.orderTime) - new Date(a.orderTime));
    let antrianNumber = 1; // Counter for "No Antrian"
    orders.forEach(order => {
        totalOrdersCount++; // Count all orders
        // Generate Order ID
        const orderDateFormatted = formatDateForOrderId(order.orderTime);
        const orderIdDisplay = `PSN-${orderDateFormatted}${antrianNumber.toString().padStart(3, '0')}`;
        // Separate food and drink items
        const foodItems = order.items.filter(item => item.type === 'food');
        const drinkItems = order.items.filter(item => item.type === 'drink');
        if (order.status === 'pending') {
            pendingCount++;
            totalInQueueCount++;
            const orderCard = document.createElement('div');
            orderCard.className = 'card order-card mb-3';
            orderCard.innerHTML = `
                <p class="text-gray-600 text-sm text-center mb-1 font-bold">ID Pesanan: ${orderIdDisplay}</p>
                <p class="text-gray-600 text-sm mb-2 text-center font-bold">Pelanggan: ${order.customerName}</p>
                <h4 class="text-md font-semibold text-gray-700 text-center mb-1 font-bold">No Antrian ${antrianNumber.toString().padStart(3, '0')}</h4>
                <p class="text-gray-600 text-sm mb-2 text-left">No Meja: ${order.tableNumber}</p>
                <h5 class="menu-category-title">Makanan:</h5>
                <div class="space-y-1 mb-2">
                    ${foodItems.length > 0 ? foodItems.map((item, index) => `
                        <div class="order-item">
                            <span class="ml-2">${item.name}</span>
                        </div>
                    `).join('') : '<p class="text-gray-500 text-xs ml-2">Tidak ada makanan.</p>'}
                </div>
                <h5 class="menu-category-title">Minuman:</h5>
                <div class="space-y-1 mb-3">
                    ${drinkItems.length > 0 ? drinkItems.map((item, index) => `
                        <div class="order-item">
                            <span class="ml-2">${item.name}</span>
                        </div>
                    `).join('') : '<p class="text-gray-500 text-xs ml-2">Tidak ada minuman.</p>'}
                </div>
                <p class="text-gray-600 text-xs text-center mb-2">Waktu: ${order.orderTime}</p>
                <div class="flex justify-end space-x-3">
                    <button class="btn btn-primary text-xs" onclick="confirmOrder('${order.id}')">Konfirmasi Pesanan</button>
                </div>
            `;
            // Hapus placeholder jika ada pesanan
            if (pendingCount === 1) {
                 pendingOrdersDiv.innerHTML = '';
            }
            pendingOrdersDiv.appendChild(orderCard);
            antrianNumber++; // Increment for the next queue order
        } else if (order.status === 'confirmed') {
            inProgressCount++;
            totalInQueueCount++;
            const orderCard = document.createElement('div');
            orderCard.className = 'card order-card mb-3';
            const allItemsCompleted = order.items.every(item => item.completed);
            orderCard.innerHTML = `
                <p class="text-gray-600 text-sm text-center mb-1 font-bold">ID Pesanan: ${orderIdDisplay}</p>
                <p class="text-gray-600 text-sm mb-2 text-center font-bold">Pelanggan: ${order.customerName}</p>
                <h4 class="text-md font-semibold text-gray-700 text-center mb-1 font-bold">No Antrian ${antrianNumber.toString().padStart(3, '0')}</h4>
                <p class="text-gray-600 text-sm mb-2 text-left">No Meja: ${order.tableNumber}</p>
                <h5 class="menu-category-title">Makanan:</h5>
                <div class="space-y-1 mb-2">
                    ${foodItems.length > 0 ? foodItems.map((item, index) => `
                        <div class="order-item">
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded"
                                       data-order-id="${order.id}" data-item-index="${order.items.indexOf(item)}"
                                       onchange="toggleMenuItemCompletion(this)"
                                       ${item.completed ? 'checked' : ''}>
                                <span class="ml-2 ${item.completed ? 'line-through text-gray-500' : ''}">${item.name}</span>
                            </label>
                        </div>
                    `).join('') : '<p class="text-gray-500 text-xs ml-2">Tidak ada makanan.</p>'}
                </div>
                <h5 class="menu-category-title">Minuman:</h5>
                <div class="space-y-1 mb-3">
                    ${drinkItems.length > 0 ? drinkItems.map((item, index) => `
                        <div class="order-item">
                            <label class="flex items-center text-gray-700">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded"
                                       data-order-id="${order.id}" data-item-index="${order.items.indexOf(item)}"
                                       onchange="toggleMenuItemCompletion(this)"
                                       ${item.completed ? 'checked' : ''}>
                                <span class="ml-2 ${item.completed ? 'line-through text-gray-500' : ''}">${item.name}</span>
                            </label>
                        </div>
                    `).join('') : '<p class="text-gray-500 text-xs ml-2">Tidak ada minuman.</p>'}
                </div>
                <p class="text-gray-600 text-xs text-center mb-2">Waktu: ${order.orderTime}</p>
                <div class="flex justify-end space-x-3">
                    <button class="btn btn-secondary text-xs ${allItemsCompleted ? '' : 'btn-disabled'}" onclick="markOrderAsCompleted('${order.id}')" ${allItemsCompleted ? '' : 'disabled'} id="complete-order-btn-${order.id}">Pesanan Selesai</button>
                </div>
            `;
             // Hapus placeholder jika ada pesanan
             if (inProgressCount === 1) {
                 inProgressOrdersDiv.innerHTML = '';
             }
            inProgressOrdersDiv.appendChild(orderCard);
            antrianNumber++; // Increment for the next queue order
        } else if (order.status === 'completed') {
            totalCompletedOrdersCount++;
        }
    });
    // Update counts for the main queue sections
    document.getElementById('pending-count').textContent = pendingCount;
    document.getElementById('in-progress-count').textContent = inProgressCount;
    // Update summary cards
    document.getElementById('total-orders-count').textContent = totalOrdersCount;
    document.getElementById('in-queue-count').textContent = totalInQueueCount;
    document.getElementById('completed-orders-total-count').textContent = totalCompletedOrdersCount;
    // Re-render history with current filter
    filterHistory();
}

// Helper function to get status text
function getStatusText(status) {
    switch (status) {
        case 'pending': return 'Menunggu Konfirmasi';
        case 'confirmed': return 'Sedang Diproses';
        case 'completed': return 'Selesai';
        default: return '';
    }
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

    // Find the link based on its sectionId to match the new sidebar names
    let targetLink;
    if (sectionId === 'order-queue') {
        targetLink = document.querySelector(`.sidebar a[onclick="showSection('order-queue')"]`);
        document.getElementById('main-dashboard-title').textContent = 'Antrian Pesanan';
    } else if (sectionId === 'order-history') {
        targetLink = document.querySelector(`.sidebar a[onclick="showSection('order-history')"]`);
        document.getElementById('main-dashboard-title').textContent = 'Riwayat Pesanan';
    } else if (sectionId === 'bahan') {
         targetLink = document.querySelector(`.sidebar a[onclick="showSection('bahan')"]`);
         document.getElementById('main-dashboard-title').textContent = 'Daftar Bahan';
    }

    if (targetLink) {
        targetLink.classList.add('active');
    }
}

// Function to open the order details modal (now only for history items)
function openModal(orderId) {
    const order = orders.find(o => o.id === orderId);
    if (!order) return;
    // Generate Order ID for modal
    const orderDateFormatted = formatDateForOrderId(order.orderTime);
    const orderIdDisplay = `PSN-${orderDateFormatted}${order.id.split('-')[1].slice(-3)}`; // Simple way to get last 3 digits of unique ID for antrian number in modal
    document.getElementById('modal-order-id').textContent = `Detail Pesanan: ${orderIdDisplay}`;
    document.getElementById('modal-customer-name').textContent = order.customerName;
    document.getElementById('modal-order-time').textContent = order.orderTime;
    const menuListDiv = document.getElementById('modal-menu-list');
    menuListDiv.innerHTML = '';
    const foodItems = order.items.filter(item => item.type === 'food');
    const drinkItems = order.items.filter(item => item.type === 'drink');
    let menuHtml = `<p class="text-gray-600 text-sm mb-2">No Meja: ${order.tableNumber}</p>`; // Add table number to modal
    if (foodItems.length > 0) {
        menuHtml += '<h5 class="menu-category-title">Makanan:</h5>';
        foodItems.forEach(item => {
            menuHtml += `
                <div class="order-item">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded"
                               disabled
                               ${item.completed ? 'checked' : ''}>
                        <span class="ml-2 ${item.completed ? 'line-through text-gray-500' : ''}">${item.name}</span>
                    </label>
                </div>
            `;
        });
    } else {
        menuHtml += '<h5 class="menu-category-title">Makanan:</h5><p class="text-gray-500 text-xs ml-2">Tidak ada makanan.</p>';
    }
    if (drinkItems.length > 0) {
        menuHtml += '<h5 class="menu-category-title">Minuman:</h5>';
        drinkItems.forEach(item => {
            menuHtml += `
                <div class="order-item">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-green-600 rounded"
                               disabled
                               ${item.completed ? 'checked' : ''}>
                        <span class="ml-2 ${item.completed ? 'line-through text-gray-500' : ''}">${item.name}</span>
                    </label>
                </div>
            `;
        });
    } else {
        menuHtml += '<h5 class="menu-category-title">Minuman:</h5><p class="text-gray-500 text-xs ml-2">Tidak ada minuman.</p>';
    }
    menuListDiv.innerHTML = menuHtml;
    const modalActionsDiv = document.getElementById('modal-actions');
    modalActionsDiv.innerHTML = `
        <span class="text-green-600 font-semibold text-sm">Pesanan ini sudah selesai.</span>
    `; // For history items, always show completed status
    document.getElementById('orderDetailsModal').style.display = 'flex';
}

// Function to close the modal
function closeModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
}

// Function to open delete confirmation modal
let orderToDeleteId = null; // Variable to store the ID of the order to be deleted
function openDeleteConfirmationModal(orderId) {
    orderToDeleteId = orderId;
    document.getElementById('deleteConfirmationModal').style.display = 'flex';
}

// Function to close delete confirmation modal
function closeDeleteModal() {
    document.getElementById('deleteConfirmationModal').style.display = 'none';
    orderToDeleteId = null; // Clear the stored ID
}

// Event listener for the "Ya, Hapus" button in the delete confirmation modal
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (orderToDeleteId) {
        deleteOrder(orderToDeleteId);
        closeDeleteModal();
    }
});

// Function to confirm an order
function confirmOrder(orderId) {
    const orderIndex = orders.findIndex(o => o.id === orderId);
    if (orderIndex !== -1) {
        orders[orderIndex].status = 'confirmed';
        renderOrders(); // Re-render to move the order to "Sedang Diproses"
    }
}

// Function to toggle menu item completion
function toggleMenuItemCompletion(checkbox) {
    const orderId = checkbox.dataset.orderId;
    const itemIndex = parseInt(checkbox.dataset.itemIndex);
    const order = orders.find(o => o.id === orderId);
    if (order && order.items[itemIndex]) {
        order.items[itemIndex].completed = checkbox.checked;
        // Update text style
        const span = checkbox.nextElementSibling;
        if (checkbox.checked) {
            span.classList.add('line-through', 'text-gray-500');
        } else {
            span.classList.remove('line-through', 'text-gray-500');
        }
        // Check if all items are completed to enable "Pesanan Selesai" button for confirmed orders
        if (order.status === 'confirmed') {
            const allItemsCompleted = order.items.every(item => item.completed);
            // Get the specific button for this order using its dynamic ID
            const completeButton = document.getElementById(`complete-order-btn-${order.id}`);
            if (completeButton) {
                if (allItemsCompleted) {
                    completeButton.removeAttribute('disabled');
                    completeButton.classList.remove('btn-disabled');
                    completeButton.classList.add('btn-secondary');
                } else {
                    completeButton.setAttribute('disabled', 'true');
                    completeButton.classList.add('btn-disabled');
                    completeButton.classList.remove('btn-secondary');
                }
            }
        }
    }
}

// Function to mark an order as completed
function markOrderAsCompleted(orderId) {
    const orderIndex = orders.findIndex(o => o.id === orderId);
    if (orderIndex !== -1) {
        const order = orders[orderIndex];
        // Ensure all items are completed before marking order as completed
        const allItemsCompleted = order.items.every(item => item.completed);
        if (allItemsCompleted) {
            orders[orderIndex].status = 'completed';
            orders[orderIndex].orderTime = new Date().toLocaleString(); // Update completion time
            renderOrders(); // Re-render to move the order to history
        } else {
            console.warn("Not all items in the order are completed.");
        }
    }
}

// Function to delete an order
function deleteOrder(orderId) {
    orders = orders.filter(order => order.id !== orderId);
    renderOrders(); // Re-render to update the display
}

// New functions to populate dropdowns
function populateDayFilter() {
    const select = document.getElementById('history-filter-day');
    select.innerHTML = '<option value="">Pilih Tanggal</option>';
    for (let i = 1; i <= 31; i++) {
        const option = document.createElement('option');
        option.value = i.toString().padStart(2, '0');
        option.textContent = i;
        select.appendChild(option);
    }
}

function populateMonthFilter() {
    const select = document.getElementById('history-filter-month');
    select.innerHTML = '<option value="">Pilih Bulan</option>';
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    months.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = (index + 1).toString().padStart(2, '0'); // Month is 0-indexed in JS Date, but 1-indexed for display/value
        option.textContent = month;
        select.appendChild(option);
    });
}

function populateYearFilter() {
    const select = document.getElementById('history-filter-year');
    select.innerHTML = '<option value="">Pilih Tahun</option>';
    const currentYear = new Date().getFullYear();
    const startYear = 2020; // You can adjust this start year as needed
    for (let i = currentYear; i >= startYear; i--) {
        const option = document.createElement('option');
        option.value = i.toString();
        option.textContent = i;
        select.appendChild(option);
    }
}

// Function to filter order history
function filterHistory() {
    const historyOrdersDiv = document.getElementById('history-orders');
    const noHistoryMessage = document.getElementById('no-history-message');
    historyOrdersDiv.innerHTML = ''; // Clear previous content
    const selectedDay = document.getElementById('history-filter-day').value;
    const selectedMonth = document.getElementById('history-filter-month').value;
    const selectedYear = document.getElementById('history-filter-year').value;
    const filteredOrders = orders.filter(order => {
        if (order.status !== 'completed') return false; // Only show completed orders in history
        const orderDate = new Date(order.orderTime);
        const orderDay = orderDate.getDate().toString().padStart(2, '0');
        const orderMonth = (orderDate.getMonth() + 1).toString().padStart(2, '0'); // Month is 0-indexed
        const orderYear = orderDate.getFullYear().toString();
        let matchesDay = true;
        let matchesMonth = true;
        let matchesYear = true;
        if (selectedDay !== '') {
            matchesDay = (orderDay === selectedDay);
        }
        if (selectedMonth !== '') {
            matchesMonth = (orderMonth === selectedMonth);
        }
        if (selectedYear !== '') {
            matchesYear = (orderYear === selectedYear);
        }
        return matchesDay && matchesMonth && matchesYear;
    });
    // Update completed history count
    document.getElementById('completed-history-count').textContent = filteredOrders.length;
    if (filteredOrders.length === 0) {
        noHistoryMessage.style.display = 'block';
    } else {
        noHistoryMessage.style.display = 'none';
        filteredOrders.forEach(order => {
            const orderCard = document.createElement('div');
            orderCard.className = 'card order-card mb-3';
            const orderDateFormatted = formatDateForOrderId(order.orderTime);
            // For history, we can just use the original unique ID for display or generate a consistent one
            const orderIdDisplay = `PSN-${orderDateFormatted}${order.id.split('-')[1].slice(-3)}`; // Example: use last 3 digits of unique ID
            orderCard.innerHTML = `
                <p class="text-gray-600 text-sm mb-1 font-bold text-left">ID Pesanan: ${orderIdDisplay}</p>
                <p class="text-gray-600 text-sm mb-2 text-left font-bold">Pelanggan: ${order.customerName}</p>
                <p class="text-gray-600 text-sm mb-2 text-left">No Meja: ${order.tableNumber}</p>
                <p class="text-gray-600 text-xs text-left mb-2">Waktu: ${order.orderTime}</p>
                <div class="flex justify-between items-center mt-3">
                    <span class="order-status-badge status-${order.status}">${getStatusText(order.status)}</span>
                    <div class="flex space-x-2">
                        <button class="btn btn-primary text-xs" onclick="openModal('${order.id}')">Lihat Detail</button>
                        <button class="btn btn-danger text-xs" onclick="openDeleteConfirmationModal('${order.id}')">Hapus</button>
                    </div>
                </div>
            `;
            historyOrdersDiv.appendChild(orderCard);
        });
    }
}

// Close modals when clicking outside
window.onclick = function(event) {
    const modals = [
        'orderDetailsModal',
        'deleteConfirmationModal',
        'bahanModal',
        'deleteBahanModal'
    ];

    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
}

// Initialize dashboard when the page loads
document.addEventListener('DOMContentLoaded', () => {
    addDummyOrders();
    populateDayFilter();
    populateMonthFilter();
    populateYearFilter();
    renderOrders(); // This will now call filterHistory with default empty values, showing all completed orders
    renderBahanTable(); // Render tabel bahan kosong
    showSection('order-queue'); // Default view
});