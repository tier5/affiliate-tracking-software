<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use \App\User;
use \App\RVUser;
use \App\BusinessPlan;
use \App\AffiliateLink;

class AffiliatePlanSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get affiliates
        $affiliates = User::all();

        // loop
        foreach ($affiliates as $affiliate) {
            // get agency super user
            $RVUser = RVUser::where('agency_id', $affiliate->agency_id)
                  ->where('role', 'Super Admin')
                  ->first();

            // get business plans by user id
            $plans = BusinessPlan::where('user_id', $RVUser->id)
                                ->get();

            foreach ($plans as $plan) {
                // generate link code
                $code = $this->generateLinkCode();

                // get link by plan id
                $link = AffiliateLink::firstOrCreate(
                    ['user_id' => $affiliate->id, 'plan_id' => $plan->id],
                    ['commission' => 0, 'code' => $code]
                );
            }
        }
    }

    public function generateLinkCode()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $short_code = '';

        while (1) {
            for ($i = 0; $i < 12; $i++) {
                $short_code .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            $link = AffiliateLink::where('code', $short_code)->first();
            
            if (!$link) {
                break;
            }
        }

        return $short_code;
    }
}
