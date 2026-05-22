<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class AdminOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        // Gửi tiêu đề mail có Mã đơn hàng
        return $this->subject('🔔 KHÁCH ĐÃ THANH TOÁN: ' . $this->order->tracking_no)
                    ->view('emails.admin_notification');
    }
}