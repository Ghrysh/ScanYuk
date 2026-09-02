<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\QueueLocation;
use App\Models\QueueService;
use App\Models\QueueTicket;

Route::get('/debug-register', function(Request $request) {
    try {
        $location = QueueLocation::first();
        if (!$location) return "No location";
        
        $service = $location->services()->first();
        if (!$service) return "No service";
        
        $queueNumber = $service->generateNextQueueNumber();
        
        $ticket = QueueTicket::create([
            'queue_location_id' => $location->id,
            'queue_service_id' => $service->id,
            'queue_number' => $queueNumber,
            'customer_name' => 'Debug User',
            'customer_phone' => '08123456789',
            'status' => 'waiting',
            'date' => now()->toDateString(),
        ]);
        
        return "SUCCESS: Ticket created with ID " . $ticket->id;
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
});
