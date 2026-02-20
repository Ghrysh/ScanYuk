<?php

use App\Http\Controllers\Api\ScanController;

Route::get('/scan/{uuid}', [ScanController::class, 'scanQr']);