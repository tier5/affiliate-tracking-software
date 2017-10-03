<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class sendPurchaseUpdateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_name;
    public $user_email;
    public $oldProduct;
    public $product;
    public $product_price;
    public $product_commission;
    public $campaign;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($oldProduct,$user_name,$user_email,$product,$product_price,$product_commission,$campaign)
    {
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->oldProduct = $oldProduct;
        $this->product = $product;
        $this->product_price = $product_price;
        $this->product_commission = $product_commission;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $email = $this->user_email;
            Log::info('product upgrade '.$email);
            Mail::send('email.upgrade', [
                'oldProduct' => $this->oldProduct,
                'name' => $this->user_name,
                'campaign' => $this->campaign,
                'product' => $this->product,
                'price' => $this->product_price,
                'commission' => $this->product_commission,
            ], function ($m) use ($email) {
                $m->from(env('MAIL_USERNAME'), 'interwebleads.com');
                $m->to($email,'InterWebLeads')->subject('A sale has been updated');
            });
        } catch (\Exception $exception){
            Log::info($exception->getMessage());
        }
    }
}
