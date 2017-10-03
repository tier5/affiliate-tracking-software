<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegistrationSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $affiliateName;
    public $affiliateEmail;
    public $affiliatePassword;
    public $affiliateKey;
    public $url;
    public $campaignName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($affiliateName,$affiliateEmail,$affiliatePassword,$affiliateKey,$url,$campaignName)
    {
        $this->affiliateName = $affiliateName;
        $this->affiliateEmail = $affiliateEmail;
        $this->affiliatePassword = $affiliatePassword;
        $this->affiliateKey = $affiliateKey;
        $this->url = $url;
        $this->campaignName = $campaignName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $email = $this->affiliateEmail;
            Log::info('sending Registration email to '.$email);
            Mail::send('email.register', [
                'affiliate_name' => $this->affiliateName,
                'affiliate_email' => $this->affiliateEmail,
                'affiliate_password' => $this->affiliatePassword,
                'affiliate_key' => $this->affiliateKey,
                'affiliate_url' => $this->url,
                'campaign_name' => $this->campaignName,
            ], function ($m) use ($email) {
                $m->from(env('MAIL_USERNAME'), 'intertwebleads.com');
                $m->to($email,'InterWebLeads Registration')->subject('Registration confirmation');
            });
        } catch (\Exception $exception){
            Log::info($exception->getMessage());
        }
    }
}
