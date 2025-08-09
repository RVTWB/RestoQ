/* Gunakan Tailwind untuk sebagian besar styling */
/* Tambahkan style custom yang spesifik */

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
    text-decoration: none; /* Tambahkan ini untuk menghilangkan garis bawah */
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
    border: none; /* Tambahkan ini jika menggunakan Tailwind, untuk override default */
    text-decoration: none; /* Tambahkan ini untuk link yang dijadikan tombol */
    display: inline-block; /* Tambahkan ini untuk link yang dijadikan tombol */
}

.btn-primary {
    background-color: #22c55e; /* Green */
    color: #ffffff;
}

.btn-primary:hover {
    background-color: #16a34a; /* Darker green */
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #f97316; /* Orange */
    color: #ffffff;
}

.btn-secondary:hover {
    background-color: #ea580c; /* Darker orange */
    transform: translateY(-1px);
}

.btn-danger {
    background-color: #ef4444; /* Red */
    color: #ffffff;
}

.btn-danger:hover {
    background-color: #dc2626; /* Darker red */
    transform: translateY(-1px);
}

.btn-disabled {
    background-color: #9ca3af; /* Gray */
    color: #ffffff;
    cursor: not-allowed;
    opacity: 0.6; /* Tambahkan opacity untuk disabled state */
}

.order-status-badge {
    padding: 0.2rem 0.6rem;
    border-radius: 0.4rem;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-pending {
    background-color: #fcd34d; /* Yellow */
    color: #92400e;
}

.status-confirmed {
    background-color: #60a5fa; /* Blue */
    color: #1e40af;
}

.status-completed {
    background-color: #34d399; /* Green */
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
    cursor: pointer; /* Tambahkan cursor pointer */
}

.close-button:hover,
.close-button:focus {
    color: black;
    text-decoration: none;
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

/* Styles for scrollbar */
.scrollable-content {
    max-height: 400px;
    overflow-y: auto;
    padding: 0.5rem;
}

/* Custom scrollbar styles */
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

/* Tambahkan style untuk tabel bahan jika diperlukan */
/* Kebanyakan sudah menggunakan class Tailwind */