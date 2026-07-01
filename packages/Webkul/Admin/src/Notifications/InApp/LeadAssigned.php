<?php

namespace Webkul\Admin\Notifications\InApp;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeadAssigned extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $lead) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storing.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title'      => 'Lead Baru Ditugaskan',
            'message'    => "Lead '" . $this->lead->title . "' telah ditugaskan kepada Anda.",
            'type'       => 'lead',
            'action_url' => route('admin.leads.view', $this->lead->id),
        ];
    }
}
