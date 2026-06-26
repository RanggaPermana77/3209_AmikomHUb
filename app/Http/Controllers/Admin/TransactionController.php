<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // Mengambil transaksi terbaru dengan pembatasan 20 baris/halaman
        $transactions = Transaction::with('event')->latest()->paginate(20);

        // Menghitung statistik untuk ringkasan di dashboard transaksi
        $totalRevenue = Transaction::whereIn('status', ['success', 'settlement', 'Success', 'Settlement'])->sum('total_price');
        $totalTransactions = Transaction::count();
        $pendingCount = Transaction::whereIn('status', ['pending', 'Pending'])->count();
        $successCount = Transaction::whereIn('status', ['success', 'settlement', 'Success', 'Settlement'])->count();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'pendingCount',
            'successCount'
        ));
    }
}
