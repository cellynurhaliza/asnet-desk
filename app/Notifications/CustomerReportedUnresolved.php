<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomerReportedUnresolved extends Notification
{
    use Queueable;

    public function __construct(
        public int $ticketId,
        public string $ticketName,
        public string $customerName,
        public string $note
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'customer_unresolved',
            'ticket_id' => $this->ticketId,
            'ticket_name' => $this->ticketName,
            'customer_name' => $this->customerName,
            'message' => 'Customer reported that the issue is not resolved.',
            'note' => $this->note,
        ];
    }
}
