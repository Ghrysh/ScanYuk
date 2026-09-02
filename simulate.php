<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fake request
$location = \App\Models\QueueLocation::first();
$service = $location->services()->first();

$request = \Illuminate\Http\Request::create('/antrian/'.$location->uuid.'/register', 'POST', [
    'queue_service_id' => $service->id,
    'customer_name' => 'John Doe',
    'customer_phone' => '08123456789'
]);

$controller = new \App\Http\Controllers\QueuePublicController();
try {
    $response = $controller->store($request, $location->uuid);
    echo "Response status: " . $response->getStatusCode() . "\n";
    if ($response->isRedirection()) {
        echo "Redirects to: " . $response->getTargetUrl() . "\n";
        
        // Let's check session errors
        $errors = session('errors');
        if ($errors) {
            echo "Validation Errors:\n";
            print_r($errors->all());
        }
        
        $error = session('error');
        if ($error) {
            echo "Session Error: $error\n";
        }
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
