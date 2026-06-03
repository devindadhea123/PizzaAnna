<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Daily Zone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .hero-pizza {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-[#D73535] to-red-700 min-h-screen flex items-center justify-center">

<div class="container mx-auto px-4">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid md:grid-cols-2">
                <!-- Left Side -->
                <div class="bg-gradient-to-br from-[#D73535] to-red-700 p-8 text-white flex flex-col justify-center">
                    <div class="text-center flex flex-col items-center">
                        <div class="text-6xl mb-4 hero-pizza"></div>
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-white shadow-xl mb-4 border-4 border-white">
                        <img src="{{ asset('images/logo-pizzaanna.jpeg') }}" 
                             alt="Logo PizzaAnna" 
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\"bi bi-pizza-fill text-6xl text-[#D73535]\"></i>'">
                    </div>
                        <h1 class="text-3xl font-bold mb-2">PizzaAnna</h1>
                        <p class="text-white/80 mb-4">Your Favorite Pizza Place</p>
                        
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
                        <p class="text-gray-500">Login to your account</p>
                    </div>
                    
                    @if($errors->any())
                        <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Username</label>
            <div class="relative">
                <i class="bi bi-person absolute left-3 top-3 text-gray-400"></i>
                <input type="text" name="username" value="{{ old('username') }}" 
                    class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                    placeholder="Enter username" required autofocus>
               </div>
                </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2">Password</label>
                            <div class="relative">
                                <i class="bi bi-lock absolute left-3 top-3 text-gray-400"></i>
                                <input type="password" name="password" 
                                    class="w-full pl-10 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D73535]"
                                    placeholder="Enter password" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-[#D73535] text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition transform hover:scale-[1.02]">
                            LOGIN
                        </button>
                    </form>
                    
                    <div class="mt-6 text-center text-sm text-gray-500">
                        <p>Demo Accounts:</p>
                        <div class="flex justify-center gap-4 mt-2">
                            <div class="text-xs">
                                <span class="font-semibold">Admin:</span> admin / admin
                            </div>
                            <div class="text-xs">
                                <span class="font-semibold">Kasir:</span> kasir / kasir
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>