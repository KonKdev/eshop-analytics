<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ordersCount;
    public $totalRevenue;

    public function __construct($ordersCount, $totalRevenue)
    {
        $this->ordersCount = $ordersCount;
        $this->totalRevenue = $totalRevenue;
    }

    public function build()
    {
        return $this->subject('Καθημερινή Αναφορά Παραγγελιών')
                    ->view('emails.daily_report');
    }
}
