<?php

namespace Webkul\Admin\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $invoice) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable): array
    {
        $amountFormatted = number_format($this->invoice->amount, 2);
        return [
            'title'      => 'Pembayaran Berhasil',
            'message'    => "Pembayaran Invoice #{$this->invoice->invoice_number} sebesar {$this->invoice->currency} {$amountFormatted} telah lunas.",
            'type'       => 'invoice',
            'action_url' => route('admin.dashboard.index'),
        ];
    }
}
