<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_webhook_reduces_stock_and_sends_ticket_email(): void
    {
        Mail::fake();

        $category = \App\Models\Category::create([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);

        $event = Event::create([
            'category_id' => $category->id,
            'title' => 'Workshop Digital Business',
            'description' => 'Demo event',
            'date' => now()->addDay(),
            'location' => 'Amikom Yogyakarta',
            'price' => 50000,
            'stock' => 5,
            'poster_path' => null,
        ]);

        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-TEST-001',
            'customer_name' => 'Budi',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '08123456789',
            'total_price' => 55000,
            'status' => 'pending',
        ]);

        $response = $this->postJson('/midtrans/callback', [
            'order_id' => $transaction->order_id,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);

        $response->assertOk();
        $this->assertSame('success', $transaction->fresh()->status);
        $this->assertSame(4, $event->fresh()->stock);

        Mail::assertSent(\App\Mail\EventTicketMail::class, function ($mail) use ($transaction) {
            return $mail->hasTo($transaction->customer_email);
        });
    }
}
