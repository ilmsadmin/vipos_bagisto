<?php

namespace ZPlus\ViPos\Database\Seeders;

use Illuminate\Database\Seeder;
use ZPlus\ViPos\Models\PosSession;
use ZPlus\ViPos\Models\PosTransaction;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sale;
use Carbon\Carbon;

class PosTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test users
        $cashier1 = User::firstOrCreate(
            ['email' => 'cashier1@example.com'],
            [
                'name' => 'Cashier One',
                'password' => bcrypt('password123'),
            ]
        );

        $cashier2 = User::firstOrCreate(
            ['email' => 'cashier2@example.com'],
            [
                'name' => 'Cashier Two',
                'password' => bcrypt('password123'),
            ]
        );

        // Create test customers
        $customers = [];
        for ($i = 1; $i <= 5; $i++) {
            $customers[] = Customer::firstOrCreate(
                ['email' => "customer{$i}@example.com"],
                [
                    'name' => "Test Customer {$i}",
                    'phone' => "090000000{$i}",
                    'address' => "123 Test Street {$i}",
                ]
            );
        }

        // Create POS sessions
        $this->createSessionsWithTransactions($cashier1, $customers);
        $this->createSessionsWithTransactions($cashier2, $customers);
        
        // Create current open session
        $this->createOpenSession($cashier1);
    }

    /**
     * Create sessions with transactions
     */
    private function createSessionsWithTransactions($user, $customers)
    {
        for ($day = 7; $day >= 1; $day--) {
            $openedAt = Carbon::now()->subDays($day)->setHour(9)->setMinute(0);
            $closedAt = Carbon::now()->subDays($day)->setHour(18)->setMinute(0);
            
            $session = PosSession::create([
                'user_id' => $user->id,
                'opening_balance' => 500000,
                'closing_balance' => 500000 + rand(1000000, 5000000),
                'total_sales' => 0,
                'total_cash' => 0,
                'total_card' => 0,
                'transaction_count' => 0,
                'opened_at' => $openedAt,
                'closed_at' => $closedAt,
                'status' => 'closed',
            ]);

            // Create 5-15 transactions per session
            $transactionCount = rand(5, 15);
            $totalSales = 0;
            $totalCash = 0;
            $totalCard = 0;

            for ($i = 0; $i < $transactionCount; $i++) {
                $customer = $customers[array_rand($customers)];
                $paymentMethod = rand(0, 1) ? 'cash' : 'card';
                $subtotal = rand(50000, 1000000);
                $discountPercentage = rand(0, 20);
                $discountAmount = $subtotal * $discountPercentage / 100;
                $taxAmount = ($subtotal - $discountAmount) * 0.1; // 10% VAT
                $totalAmount = $subtotal - $discountAmount + $taxAmount;
                $paidAmount = $paymentMethod === 'cash' ? ceil($totalAmount / 10000) * 10000 : $totalAmount;
                
                $transactionTime = $openedAt->copy()->addMinutes(rand(0, 540));
                
                $transaction = PosTransaction::create([
                    'transaction_number' => 'POS' . $openedAt->format('Ymd') . sprintf('%04d', $i + 1),
                    'pos_session_id' => $session->id,
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'discount_percentage' => $discountPercentage,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'change_amount' => $paidAmount - $totalAmount,
                    'payment_method' => $paymentMethod,
                    'status' => 'completed',
                    'completed_at' => $transactionTime,
                    'created_at' => $transactionTime,
                    'updated_at' => $transactionTime,
                    'items' => $this->generateRandomItems(),
                ]);

                $totalSales += $totalAmount;
                if ($paymentMethod === 'cash') {
                    $totalCash += $totalAmount;
                } else {
                    $totalCard += $totalAmount;
                }
            }

            // Update session totals
            $session->update([
                'total_sales' => $totalSales,
                'total_cash' => $totalCash,
                'total_card' => $totalCard,
                'transaction_count' => $transactionCount,
                'closing_balance' => $session->opening_balance + $totalCash,
            ]);
        }
    }

    /**
     * Create an open session for testing
     */
    private function createOpenSession($user)
    {
        $session = PosSession::create([
            'user_id' => $user->id,
            'opening_balance' => 500000,
            'opened_at' => Carbon::now()->setHour(9)->setMinute(0),
            'status' => 'open',
        ]);

        // Add a few transactions to current session
        $customers = Customer::limit(3)->get();
        $totalSales = 0;
        $totalCash = 0;
        $totalCard = 0;

        foreach ($customers as $index => $customer) {
            $paymentMethod = $index % 2 ? 'cash' : 'card';
            $subtotal = rand(100000, 500000);
            $totalAmount = $subtotal * 1.1; // Add 10% tax
            
            PosTransaction::create([
                'transaction_number' => 'POS' . date('Ymd') . sprintf('%04d', $index + 1),
                'pos_session_id' => $session->id,
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax_amount' => $subtotal * 0.1,
                'total_amount' => $totalAmount,
                'paid_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed',
                'completed_at' => now(),
                'items' => $this->generateRandomItems(),
            ]);

            $totalSales += $totalAmount;
            if ($paymentMethod === 'cash') {
                $totalCash += $totalAmount;
            } else {
                $totalCard += $totalAmount;
            }
        }

        $session->update([
            'total_sales' => $totalSales,
            'total_cash' => $totalCash,
            'total_card' => $totalCard,
            'transaction_count' => 3,
        ]);
    }

    /**
     * Generate random items for transaction
     */
    private function generateRandomItems()
    {
        $items = [];
        $itemCount = rand(1, 5);
        
        for ($i = 0; $i < $itemCount; $i++) {
            $items[] = [
                'product_id' => rand(1, 100),
                'name' => 'Product ' . ($i + 1),
                'quantity' => rand(1, 5),
                'price' => rand(10000, 200000),
                'subtotal' => 0, // Will be calculated
            ];
            $items[$i]['subtotal'] = $items[$i]['quantity'] * $items[$i]['price'];
        }
        
        return $items;
    }
}
