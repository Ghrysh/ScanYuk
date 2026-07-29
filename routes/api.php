<?php

use App\Http\Controllers\Api\ScanController;
use App\Http\Controllers\PaymentController;

Route::get('/scan/{uuid}', [ScanController::class, 'scanQr']);