<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf; // 👇 Quan trọng: Gọi thư viện PDF

class PlaceOrderMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $subject = 'Hóa đơn mua hàng #' . $this->order->tracking_no;
        
        // 1. Tạo file PDF từ giao diện 'pdf.invoice'
        $pdf = Pdf::loadView('pdf.invoice', ['order' => $this->order]);

        // 2. Gửi mail kèm file PDF
        return $this->subject($subject)
                    ->view('emails.place_order') // Giao diện nội dung mail
                    ->attachData($pdf->output(), 'HoaDon_'.$this->order->tracking_no.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}