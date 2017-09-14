<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Stripe\WebhookController as BaseWebhookController;
use Illuminate\Support\Facades\Log;

class WebhookController extends BaseWebhookController
{
    public function stripeCallBack($charge)
    {
        Log::info('here');
    }
}
