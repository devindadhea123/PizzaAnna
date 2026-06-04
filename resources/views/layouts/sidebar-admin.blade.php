<!-- Sidebar Admin -->
<aside class="sidebar w-72 bg-gradient-to-b from-gray-900 to-gray-800 text-white flex-shrink-0 overflow-y-auto">
    <div class="p-6">
        <!-- Logo -->
<!-- Logo dengan Foto Bulat -->
<div class="flex items-center gap-3 mb-8">
    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 shadow-lg">
        <img src="{{ asset('images/logo-pizzaanna.jpeg') }}" 
             alt="Logo PizzAnna" 
             class="w-full h-full object-cover">
    </div>
    <div>
        <h1 class="font-bold text-xl">Pizz<span class="text-[#D73535]">Anna</span></h1>
        <p class="text-xs text-gray-400">Admin Panel</p>
    </div>
</div>
        
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-grid text-xl w-5"></i>
                <span>Dashboard</span>
            </a>
            
            <!-- Riwayat Pesanan -->
            <a href="{{ route('admin.riwayat-pesanan') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.riwayat-pesanan') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-receipt text-xl w-5"></i>
                <span>Riwayat Pesanan</span>
            </a>
            
            <!-- Riwayat Prediksi -->
            <a href="{{ route('admin.riwayat-prediksi') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.riwayat-prediksi') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-graph-up text-xl w-5"></i>
                <span>Riwayat Prediksi</span>
            </a>
            
            <!-- Manajemen Menu -->
            <a href="{{ route('admin.manajemen-menu') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.manajemen-menu') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-egg-fried text-xl w-5"></i>
                <span>Manajemen Menu</span>
            </a>
            
            <!-- Kategori -->
            <a href="{{ route('admin.kategori') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.kategori') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-tags text-xl w-5"></i>
                <span>Kategori</span>
            </a>
            
            <!-- Topping -->
            <a href="{{ route('admin.topping') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.topping') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <i class="bi bi-plus-circle text-xl w-5"></i>
                <span>Topping</span>
            </a>
            
            <!-- Kelola Akun -->
<a href="{{ route('admin.kelola-akun') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.kelola-akun') ? 'bg-[#D73535] shadow-lg shadow-red-900/30' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
    <i class="bi bi-people-fill text-xl w-5"></i>
    <span>Kelola Akun</span>
</a>
    <div class="absolute bottom-0 w-72 p-6">
        <div class="border-t border-gray-700 pt-4">
            <div class="flex items-center space-x-3 mb-3">
                <i class="bi bi-person-circle text-2xl text-gray-400"></i>
                <div>
                    <p class="font-medium">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->role ?? 'admin' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-gray-700 rounded-xl hover:bg-gray-600 transition">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    .sidebar-link {
        transition: all 0.2s ease;
    }
    .sidebar-link:hover {
        padding-left: 1.25rem;
    }
</style>