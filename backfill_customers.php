<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\QueueTicket;
use App\Models\QueueCustomer;

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
echo "Backfilled $count visits.\n";
