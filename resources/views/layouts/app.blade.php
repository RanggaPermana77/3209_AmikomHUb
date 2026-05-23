<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <nav class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                AH
            </div>
            <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
        </div>
        <div class="hidden md:flex gap-8 font-medium items-center">
            <a href="#" class="text-indigo-600">Jelajahi</a>
            <a href="#" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="#" class="hover:text-indigo-600 transition">Tentang Kami</a>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                <i class="fa-solid fa-gauge-high mr-2"></i>Admin Panel
            </a>
        </div>
    </nav>

    <!-- KONTEN DINAMIS -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-950 text-indigo-100 py-16 px-6 mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16 mb-12 pb-12 border-b border-indigo-800">
                <!-- Left Column: Logo & Description -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-indigo-900 font-bold text-lg">
                            AH
                        </div>
                        <span class="text-xl font-bold text-white">AmikomEventHub</span>
                    </div>
                    <p class="text-indigo-300 text-sm leading-relaxed max-w-xs">
                        Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                    </p>
                </div>

                <!-- Middle Column: Navigasi -->
                <div class="space-y-4">
                    <h3 class="text-white font-semibold text-lg">Navigasi</h3>
                    <ul class="space-y-2 text-indigo-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition duration-200">Home</a></li>
                        <li><a href="{{ route('home') }}#events" class="hover:text-white transition duration-200">Semua Event</a></li>
                        <li><a href="#" class="hover:text-white transition duration-200">Cara Bayar</a></li>
                    </ul>
                </div>

                <!-- Right Column: Hubungi Kami -->
                <div class="space-y-4">
                    <h3 class="text-white font-semibold text-lg">Hubungi Kami</h3>
                    <ul class="space-y-2 text-indigo-300 text-sm">
                        <li>
                            <a href="mailto:support@eventtiket.com" class="hover:text-white transition duration-200">
                                support@eventtiket.com
                            </a>
                        </li>
                        <li>
                            <a href="tel:+6281234567890" class="hover:text-white transition duration-200">
                                +62 812 3456 7890
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom: Copyright -->
            <div class="text-center text-indigo-400 text-sm">
                <p>© 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.</p>
            </div>
        </div>
    </footer>

</body>

</html>