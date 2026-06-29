@extends('layouts.app')

@section('title', 'Kelola Akun')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class=" text-[#D73535]"></i> Kelola Akun
        </h1>
        <p class="text-gray-500 mt-1">Kelola akun Admin dan Kasir yang memiliki akses ke sistem</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <button onclick="openModalTambah()" 
                    class="bg-[#D73535] text-white px-5 py-2.5 rounded-xl hover:bg-red-700 transition flex items-center gap-2 shadow-md">
                <i class="bi bi-person-plus-fill"></i> Tambah Akun Baru
            </button>
            
            <div class="flex items-center gap-3">
                <select id="filterRole" class="border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <option value="all">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select>
                
                <div class="relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchUser" placeholder="Cari username atau nama..." 
                           class="pl-10 pr-4 py-2 border rounded-xl w-64 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                </div>
                
                <button onclick="loadUsers()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition">
                    <i class="bi bi-search"></i> Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Username</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Nama Lengkap</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Role</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody id="usersTable">
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-400">
                            <i class="bi bi-hourglass-split text-3xl animate-spin"></i>
                            <p class="mt-2">Loading data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="border-t px-6 py-4 flex justify-between items-center bg-gray-50">
            <div class="text-sm text-gray-500" id="paginationInfo"></div>
            <div class="flex gap-2" id="paginationButtons"></div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH / EDIT AKUN -->
<div id="userModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-person-badge text-2xl"></i>
                <h2 class="text-xl font-bold" id="modalTitle">Tambah Akun</h2>
            </div>
            <button onclick="closeModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="userForm">
                <input type="hidden" id="userId" name="user_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required>
                </div>
                
                <div class="mb-4" id="passwordField">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="namaLengkap" name="nama_lengkap" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select id="role" name="role" 
                            class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]">
                        <option value="kasir">Kasir</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-[#D73535] text-white py-2.5 rounded-xl hover:bg-red-700 transition font-semibold">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 bg-gray-200 text-gray-700 py-2.5 rounded-xl hover:bg-gray-300 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL RESET PASSWORD -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-key text-2xl"></i>
                <h2 class="text-xl font-bold">Reset Password</h2>
            </div>
            <button onclick="closeResetModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="resetPasswordForm">
                <input type="hidden" id="resetUserId">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="newPassword" name="password" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required minlength="6">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" id="confirmPassword" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-[#D73535] text-white py-2.5 rounded-xl hover:bg-red-700 transition font-semibold">
                        <i class="bi bi-check-circle"></i> Reset
                    </button>
                    <button type="button" onclick="closeResetModal()" class="flex-1 bg-gray-200 text-gray-700 py-2.5 rounded-xl hover:bg-gray-300 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL UBAH PASSWORD SENDIRI -->
<div id="changePasswordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="bg-gradient-to-r from-[#D73535] to-red-700 text-white p-5 rounded-t-2xl flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i class="bi bi-shield-lock text-2xl"></i>
                <h2 class="text-xl font-bold">Ubah Password</h2>
            </div>
            <button onclick="closeChangePasswordModal()" class="hover:bg-white/20 rounded-full p-2 transition">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <form id="changePasswordForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                    <input type="password" id="currentPassword" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" id="newPasswordSelf" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required minlength="6">
                    <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" id="confirmPasswordSelf" 
                           class="w-full border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                           required>
                </div>
                
                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex-1 bg-[#D73535] text-white py-2.5 rounded-xl hover:bg-red-700 transition font-semibold">
                        <i class="bi bi-save"></i> Ubah Password
                    </button>
                    <button type="button" onclick="closeChangePasswordModal()" class="flex-1 bg-gray-200 text-gray-700 py-2.5 rounded-xl hover:bg-gray-300 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1;
    
    function loadUsers() {
        const role = document.getElementById('filterRole').value;
        const search = document.getElementById('searchUser').value;
        
        let url = `/api/admin/kelola-akun?page=${currentPage}`;
        if (role !== 'all') url += `&role=${role}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            totalPages = data.last_page;
            renderTable(data.data);
            renderPagination(data.current_page, data.last_page, data.total);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('usersTable').innerHTML = `
                <tr><td colspan="5" class="text-center py-10 text-red-500">
                    <i class="bi bi-wifi-off text-3xl"></i>
                    <p class="mt-2">Error loading data</p>
                </td></tr>
            `;
        });
    }
    
    function renderTable(users) {
        const tbody = document.getElementById('usersTable');
        
        if (users.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-gray-400">
                <i class="bi bi-inbox text-3xl"></i>
                <p class="mt-2">Tidak ada data</p>
            </td></tr>`;
            return;
        }
        
        let html = '';
        users.forEach((user, index) => {
            const roleBadge = user.role === 'admin' 
                ? '<span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700"><i class="bi bi-shield-check"></i> Admin</span>'
                : '<span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700"><i class="bi bi-person"></i> Kasir</span>';
            
            html += `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">${(currentPage - 1) * 10 + index + 1}</td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-600">${escapeHtml(user.username)}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-800">${escapeHtml(user.nama_lengkap)}</td>
                    <td class="px-6 py-4 text-center">${roleBadge}</td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openModalEdit(${user.id_user})" class="text-blue-600 hover:text-blue-800 transition mx-1" title="Edit">
                            <i class="bi bi-pencil-square text-lg"></i>
                        </button>
                        <button onclick="openResetPassword(${user.id_user})" class="text-yellow-600 hover:text-yellow-800 transition mx-1" title="Reset Password">
                            <i class="bi bi-key text-lg"></i>
                        </button>
                        <button onclick="deleteUser(${user.id_user})" class="text-red-600 hover:text-red-800 transition mx-1" title="Hapus">
                            <i class="bi bi-trash3 text-lg"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    function renderPagination(current, last, total) {
        const info = document.getElementById('paginationInfo');
        const buttons = document.getElementById('paginationButtons');
        
        if (total === 0) {
            info.innerHTML = 'Tidak ada data';
            buttons.innerHTML = '';
            return;
        }
        
        const start = (current - 1) * 10 + 1;
        const end = Math.min(current * 10, total);
        info.innerHTML = `Menampilkan ${start} - ${end} dari ${total} data`;
        
        let html = '';
        if (current > 1) {
            html += `<button onclick="goToPage(${current - 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">← Prev</button>`;
        }
        for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
            html += `<button onclick="goToPage(${i})" class="px-3 py-1 border rounded-lg ${i === current ? 'bg-[#D73535] text-white' : 'hover:bg-gray-100'}">${i}</button>`;
        }
        if (current < last) {
            html += `<button onclick="goToPage(${current + 1})" class="px-3 py-1 border rounded-lg hover:bg-gray-100">Next →</button>`;
        }
        buttons.innerHTML = html;
    }
    
    function goToPage(page) {
        currentPage = page;
        loadUsers();
    }
    
    function openModalTambah() {
        document.getElementById('modalTitle').innerText = 'Tambah Akun';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('passwordField').style.display = 'block';
        document.getElementById('password').required = true;
        document.getElementById('userModal').classList.remove('hidden');
        document.getElementById('userModal').classList.add('flex');
    }
    
    function openModalEdit(id) {
        fetch(`/api/admin/kelola-akun/${id}`)
            .then(response => response.json())
            .then(user => {
                document.getElementById('modalTitle').innerText = 'Edit Akun';
                document.getElementById('userId').value = user.id_user;
                document.getElementById('username').value = user.username;
                document.getElementById('namaLengkap').value = user.nama_lengkap;
                document.getElementById('role').value = user.role;
                document.getElementById('passwordField').style.display = 'none';
                document.getElementById('password').required = false;
                document.getElementById('userModal').classList.remove('hidden');
                document.getElementById('userModal').classList.add('flex');
            });
    }
    
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const userId = document.getElementById('userId').value;
        const url = userId ? `/api/admin/kelola-akun/${userId}` : '/api/admin/kelola-akun';
        const method = userId ? 'PUT' : 'POST';
        
        const formData = {
            username: document.getElementById('username').value,
            nama_lengkap: document.getElementById('namaLengkap').value,
            role: document.getElementById('role').value
        };
        
        if (!userId) {
            formData.password = document.getElementById('password').value;
        }
        
        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeModal();
                loadUsers();
            } else {
                let errorMsg = 'Error: ';
                if (data.errors) {
                    errorMsg += Object.values(data.errors).flat().join(', ');
                } else {
                    errorMsg += data.message;
                }
                alert(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
    
    function openResetPassword(id) {
        document.getElementById('resetUserId').value = id;
        document.getElementById('resetPasswordForm').reset();
        document.getElementById('resetPasswordModal').classList.remove('hidden');
        document.getElementById('resetPasswordModal').classList.add('flex');
    }
    
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            alert('Password tidak cocok!');
            return;
        }
        
        const userId = document.getElementById('resetUserId').value;
        
        fetch(`/api/admin/kelola-akun/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ password: newPassword })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeResetModal();
            } else {
                let errorMsg = 'Error: ';
                if (data.errors) {
                    errorMsg += Object.values(data.errors).flat().join(', ');
                } else {
                    errorMsg += data.message;
                }
                alert(errorMsg);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
    
    function openChangePassword() {
        document.getElementById('changePasswordForm').reset();
        document.getElementById('changePasswordModal').classList.remove('hidden');
        document.getElementById('changePasswordModal').classList.add('flex');
    }
    
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPasswordSelf').value;
        const confirmPassword = document.getElementById('confirmPasswordSelf').value;
        
        if (newPassword !== confirmPassword) {
            alert('Password baru tidak cocok!');
            return;
        }
        
        fetch('/api/admin/kelola-akun/change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeChangePasswordModal();
            } else {
                alert(data.message || 'Gagal mengubah password');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    });
    
    function deleteUser(id) {
        if (confirm('Apakah Anda yakin ingin menghapus akun ini?')) {
            fetch(`/api/admin/kelola-akun/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadUsers();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }
    
    function closeModal() {
        document.getElementById('userModal').classList.add('hidden');
        document.getElementById('userModal').classList.remove('flex');
    }
    
    function closeResetModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
        document.getElementById('resetPasswordModal').classList.remove('flex');
    }
    
    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('hidden');
        document.getElementById('changePasswordModal').classList.remove('flex');
    }
    
    document.getElementById('filterRole').addEventListener('change', () => {
        currentPage = 1;
        loadUsers();
    });
    
    document.getElementById('searchUser').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            currentPage = 1;
            loadUsers();
        }
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        loadUsers();
    });
</script>
@endsection