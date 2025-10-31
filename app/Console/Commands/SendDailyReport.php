<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyReportMail;
use App\Models\Order;
use Carbon\Carbon;

class SendDailyReport extends Command
{
    protected $signature = 'report:daily';
    protected $description = 'Send daily WooCommerce report via email';

    public function handle()
    {
        
        // Πάρε σημερινές παραγγελίες από τη βάση
        $today = Carbon::today();
        $orders = Order::whereDate('created_at', $today)->get();

        $count = $orders->count();
        $total = $orders->sum('total');

        // Στείλε email
        Mail::to('admin@example.com')->send(new DailyReportMail($count, $total));

        $this->info("✅ Daily report sent. Orders: $count | Total: $total €");
    }
}
