<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$services = \App\Models\QueueService::all();
foreach($services as $s) {
    echo "ID: {$s->id} | Name: {$s->name} | Quota: " . var_export($s->daily_quota, true) . " | isQuotaFull: " . ($s->isQuotaFull() ? 'YES' : 'NO') . "\n";
}
