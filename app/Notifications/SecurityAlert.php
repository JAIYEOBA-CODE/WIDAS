<?php

namespace App\Notifications;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecurityAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Alert $alert
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[{$this->alert->severity}] {$this->alert->title}")
            ->greeting("Security Alert - {$this->alert->severity}")
            ->line($this->alert->message)
            ->line("Type: {$this->alert->type}")
            ->line("Severity: {$this->alert->severity}")
            ->action('View Alert', url('/alerts/' . $this->alert->id))
            ->line('Please review this alert as soon as possible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'type' => $this->alert->type,
            'severity' => $this->alert->severity,
            'title' => $this->alert->title,
            'message' => $this->alert->message,
        ];
    }
}
