<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    // Nhận dữ liệu đơn hàng vào đây
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('⚠️ CẢNH BÁO: Đơn hàng #' . $this->order->tracking_no . ' đã bị HỦY')
                    ->view('emails.order_cancelled');
    }
}