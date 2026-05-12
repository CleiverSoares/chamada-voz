<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VapiWebhookController;

Route::post('/webhooks/vapi', [VapiWebhookController::class, 'handle']);
