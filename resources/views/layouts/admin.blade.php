<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">

        <!-- Logo -->
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                AH
            </div>
            <span class="text-xl font-bold text-white tracking-tight">AmikomEventHub</span>
        </div>

        <!-- Menu -->
        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">Main Menu</p>

            <!-- Dashboard -->
            <a href="/admin"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">
                <i class="fa-solid fa-gauge w-5 h-5"></i>
                Dashboard
            </a>

            <!-- Event -->
            <a href="/admin/events"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">
                <i class="fa-solid fa-calendar-days w-5 h-5"></i>
                Kelola Event
            </a>

            <!-- Transaksi -->
            <a href="/admin/transactions"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">
                <i class="fa-solid fa-receipt w-5 h-5"></i>
                Laporan Transaksi
            </a>

            <!-- KATEGORI -->
            <a href="/admin/categories"
                class="flex items-center gap-3 px-4 py-3 hover:bg-indigo-800 rounded-xl font-bold">
                <i class="fa-solid fa-tag w-5 h-5"></i>
                Kategori
            </a>
        </nav>

        <!-- Logout -->
        <div class="pt-6 border-t border-indigo-800">
            <a href="/"
                class="flex items-center gap-3 px-4 py-3 text-indigo-300 hover:text-white font-medium">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 h-5"></i>
                Keluar
            </a>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 overflow-y-auto">

        <!-- Header -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black">Admin Panel</h1>
                <p class="text-slate-500 font-medium">Selamat datang kembali, Admin!</p>
            </div>
        </header>

        <!-- Konten -->
        @yield('content')

    </main>

</body>

</html>