@extends('layouts.admin')
@section('title', 'Laporan Transaksi - Admin')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')
@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</span>
                <h3 class="text-2xl font-black text-slate-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-xl shadow-inner">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Transaksi</span>
                <h3 class="text-2xl font-black text-slate-900">{{ number_format($totalTransactions) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 text-xl shadow-inner">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transaksi Sukses</span>
                <h3 class="text-2xl font-black text-emerald-600">{{ number_format($successCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex items-center justify-between transition-all hover:shadow-md hover:-translate-y-0.5 duration-200">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transaksi Pending</span>
                <h3 class="text-2xl font-black text-amber-500">{{ number_format($pendingCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 text-xl shadow-inner">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">Order ID</th>
                        <th class="px-8 py-5">Detail Pembeli</th>
                        <th class="px-8 py-5">Event</th>
                        <th class="px-8 py-5">Tgl Transaksi</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        @php
                            $isPending = strtolower($trx->status) === 'pending';
                            $isSuccess = in_array(strtolower($trx->status), ['success', 'settlement']);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition duration-150 {{ $isPending ? 'bg-slate-50/10' : '' }}">
                            <td class="px-8 py-6">
                                <span class="font-mono font-bold px-3 py-1.5 rounded-xl text-sm transition-all {{ $isPending ? 'bg-slate-100 text-slate-500' : 'text-indigo-600 bg-indigo-50/70 border border-indigo-100/50' }}">
                                    {{ $trx->order_id }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-800 text-base leading-tight">{{ $trx->customer_name }}</p>
                                <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-1.5 font-medium">
                                    <i class="fa-regular fa-envelope text-[10px] text-slate-400"></i> {{ $trx->customer_email }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5 font-medium">
                                    <i class="fa-brands fa-whatsapp text-[10px] text-slate-400"></i> {{ $trx->customer_phone }}
                                </p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    @if($trx->event && $trx->event->poster_path && Storage::disk('public')->exists($trx->event->poster_path))
                                        <img src="{{ asset('storage/' . $trx->event->poster_path) }}" alt="Event" class="w-10 h-12 object-cover rounded-lg shadow-sm border border-slate-100">
                                    @else
                                        <div class="w-10 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 text-xs font-bold border border-slate-200">
                                            EVT
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-700 leading-tight">{{ $trx->event->title ?? '-' }}</p>
                                        @if($trx->event && $trx->event->category)
                                            <span class="text-[9px] bg-slate-100 text-slate-500 font-bold px-2 py-0.5 rounded uppercase mt-1.5 inline-block">
                                                {{ $trx->event->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                <div>{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-400 mt-1 font-normal">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($isSuccess)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider border border-emerald-200">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                        Success
                                    </span>
                                @elseif($isPending)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wider border border-amber-200">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold uppercase tracking-wider border border-rose-200">
                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                        {{ $trx->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right font-black text-lg {{ $isPending ? 'text-slate-400' : 'text-indigo-900' }}">
                                Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 text-2xl shadow-inner">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="font-bold text-slate-400">Belum ada data transaksi</p>
                                    <p class="text-xs text-slate-300">Transaksi dari pengunjung akan tercatat di sini secara otomatis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-xs font-bold text-slate-400">Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() }} Transaksi</span>
            <div>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
