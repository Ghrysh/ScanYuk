<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QueueTicket;
use App\Models\QueueCustomer;

class BackfillQueueCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:backfill-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill queue customers from existing completed tickets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting backfill...");
        $completedTickets = QueueTicket::where('status', 'completed')
            ->whereNotNull('customer_name')
            ->with('location')
            ->get();

        $count = 0;
        foreach($completedTickets as $ticket) {
            if (!$ticket->location) continue;
            $userId = $ticket->location->user_id;

            $customer = QueueCustomer::where('user_id', $userId)
                ->where(function($q) use ($ticket) {
                    if ($ticket->customer_phone) {
                        $q->where('phone', $ticket->customer_phone);
                    } else {
                        $q->where('name', $ticket->customer_name)->whereNull('phone');
                    }
                })->first();

            if (!$customer) {
                QueueCustomer::create([
                    'user_id' => $userId,
                    'name' => $ticket->customer_name,
                    'phone' => $ticket->customer_phone,
                    'points' => 0,
                    'visits' => 1
                ]);
                $count++;
            } else {
                $customer->increment('visits');
                $count++;
            }
        }
        $this->info("Backfilled $count visits.");
    }
}
