<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\TicketActivity;

class AutoConfirmCustomerTickets extends Command
{
    protected $signature = 'tickets:auto-confirm';

    protected $description = 'Automatically confirm customer tickets after 24 hours without response';

    public function handle(): int
    {
        $tickets = Ticket::where(
            'customer_confirmation_required',
            true
        )
            ->where('ticket_status', 'Awaiting Customer')
            ->where('customer_confirmation_status', 'Pending')
            ->whereNotNull('customer_confirmation_due_at')
            ->where('customer_confirmation_due_at', '<=', now())
            ->get();

        foreach ($tickets as $ticket) {
            DB::transaction(function () use ($ticket) {
                $lockedTicket = Ticket::whereKey($ticket->id)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$lockedTicket ||
                    $lockedTicket->ticket_status !== 'Awaiting Customer' ||
                    $lockedTicket->customer_confirmation_status !== 'Pending' ||
                    !$lockedTicket->customer_confirmation_due_at ||
                    $lockedTicket->customer_confirmation_due_at->isFuture()
                ) {
                    return;
                }

                $lockedTicket->update([
                    'ticket_status' => 'Completed',
                    'customer_confirmation_status' => 'Auto Confirmed',
                    'customer_confirmed_at' => now(),
                    'customer_confirmation_due_at' => null,
                ]);

                TicketActivity::create([
                    'ticket_id' => $lockedTicket->id,
                    'activity_type' => 'Auto Confirmed',
                    'description' => 'Ticket automatically marked as completed because the customer did not respond within 24 hours.',
                    'performed_by' => 'System',
                ]);
            });
        }

        $this->info('Customer ticket auto-confirmation completed.');

        return self::SUCCESS;
    }
}
