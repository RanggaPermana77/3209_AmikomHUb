<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Transaction;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return redirect('/');
        }

        return view('ticket', compact('transaction'));
    }
}