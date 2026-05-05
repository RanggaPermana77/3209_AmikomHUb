@extends('layouts.admin')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">Rp 12.450.000</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">1.284</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black">8 Event</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black">12 Pesanan</h3>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terakhir</h3>
        <a href="/admin/transactions" class="text-indigo-600 font-bold hover:underline">Lihat Semua</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y border-t">
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase text-sm">Donni Prabowo</p>
                        <p class="text-xs text-slate-400">donni@example.com</p>
                    </td>
                    <td class="px-8 py-6">Jazz Night 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Success</span>
                    </td>
                    <td class="px-8 py-6 font-bold text-indigo-600">Rp 155.000</td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase text-sm">Maya Sari</p>
                        <p class="text-xs text-slate-400">maya@example.com</p>
                    </td>
                    <td class="px-8 py-6">AI & Future Workshop</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold">Pending</span>
                    </td>
                    <td class="px-8 py-6 font-bold text-indigo-600">Rp 55.000</td>
                </tr>

                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase text-sm">Budi Santoso</p>
                        <p class="text-xs text-slate-400">budi@example.com</p>
                    </td>
                    <td class="px-8 py-6">Hackathon 2024</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Free</span>
                    </td>
                    <td class="px-8 py-6 font-bold text-indigo-600">Rp 0</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection