<?php

namespace Webkul\Admin\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TenantActivated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $company) {}

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
        return [
            'title'      => 'Akun Perusahaan Aktif',
            'message'    => "Selamat! Perusahaan Anda '{$this->company->name}' telah berhasil diaktifkan. Anda sekarang dapat mengakses seluruh fitur CRM.",
            'type'       => 'activation',
            'action_url' => route('admin.dashboard.index'),
        ];
    }
}
