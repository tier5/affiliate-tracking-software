<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function stripeCallBack($charge)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey("sk_test_Lf7l6YvX9WNgphuWzg3ntDJ5");

        // You can find your endpoint's secret in your webhook settings
        $endpoint_secret = "whsec_uB7FqnVseC2gnbyZih5XHg8OUkTdMdho";
        $payload = @file_get_contents("php://input");
        $sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];
        $event = null;
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400); // PHP 5.4 or greater
            exit();
        } catch(\Stripe\Error\SignatureVerification $e) {
            // Invalid signature
            http_response_code(400); // PHP 5.4 or greater
            exit();
        }
        // Do something with $event
        Log::info('here');
        http_response_code(200); // PHP 5.4 or greater
    }
}
