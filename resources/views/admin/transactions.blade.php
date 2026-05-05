@extends('layouts.admin')

@section('content')

<!-- Header Atas -->
<div class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Laporan Transaksi</h1>
        <p class="text-slate-500 font-medium">Pantau arus kas dan penjualan tiket Anda.</p>
    </div>

    <div class="flex gap-4">
        <button class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold hover:bg-white hover:text-indigo-600 flex items-center gap-2">
            <i class="fa-solid fa-file-excel w-5 h-5"></i>
            Ekspor Excel
        </button>
        <button class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 flex items-center gap-2">
            <i class="fa-solid fa-file-pdf w-5 h-5"></i>
            Unduh PDF
        </button>
    </div>
</div>

<!-- Filter + Table -->
<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">

    <!-- Filter -->
    <div class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">

        <input type="text" placeholder="Cari Order ID, Nama, atau Email..."
            class="flex-1 px-5 py-3 rounded-xl border border-slate-200 bg-white outline-none">

        <select class="px-5 py-3 rounded-xl border border-slate-200 bg-white">
            <option>Semua Status</option>
            <option>Success</option>
            <option>Pending</option>
            <option>Expired</option>
        </select>

        <select class="px-5 py-3 rounded-xl border border-slate-200 bg-white">
            <option>Bulan Ini</option>
            <option>Bulan Lalu</option>
            <option>Tahun 2024</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-bold">
                <tr>
                    <th class="px-8 py-4">Order ID</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Tanggal</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y border-t">

                <tr class="hover:bg-slate-50">
                    <td class="px-8 py-6 font-mono font-bold text-indigo-600">#TRX-99210</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Donni Prabowo</p>
                        <p class="text-xs text-slate-400">donni@example.com</p>
                    </td>
                    <td class="px-8 py-6">Jazz Night 2024</td>
                    <td class="px-8 py-6 text-sm text-slate-500">26 Mar 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs">Success</span>
                    </td>
                    <td class="px-8 py-6 text-right font-bold">Rp 155.000</td>
                </tr>

                <tr class="hover:bg-slate-50">
                    <td class="px-8 py-6 font-mono font-bold">#TRX-99209</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Maya Sari</p>
                        <p class="text-xs text-slate-400">maya@example.com</p>
                    </td>
                    <td class="px-8 py-6">AI Workshop</td>
                    <td class="px-8 py-6 text-sm text-slate-500">26 Mar 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs">Pending</span>
                    </td>
                    <td class="px-8 py-6 text-right font-bold">Rp 55.000</td>
                </tr>

                <tr class="hover:bg-slate-50">
                    <td class="px-8 py-6 font-mono font-bold text-indigo-600">#TRX-99208</td>
                    <td class="px-8 py-6">
                        <p class="font-bold">Budi Santoso</p>
                        <p class="text-xs text-slate-400">budi@example.com</p>
                    </td>
                    <td class="px-8 py-6">Hackathon 2024</td>
                    <td class="px-8 py-6 text-sm text-slate-500">25 Mar 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs">Free</span>
                    </td>
                    <td class="px-8 py-6 text-right font-bold">Rp 0</td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t flex justify-between items-center">
        <p class="text-sm text-slate-500">Menampilkan 3 data</p>

        <div class="flex gap-2">
            <button class="px-4 py-2 border rounded-xl text-sm flex items-center gap-2"><i class="fa-solid fa-chevron-left w-4 h-4"></i>Prev</button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm">1</button>
            <button class="px-4 py-2 border rounded-xl text-sm">2</button>
            <button class="px-4 py-2 border rounded-xl text-sm flex items-center gap-2">Next<i class="fa-solid fa-chevron-right w-4 h-4"></i></button>
        </div>
    </div>

</div>

@endsection