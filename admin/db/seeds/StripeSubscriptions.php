<?php

use Phinx\Seed\AbstractSeed;

class StripeSubscriptions extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        /*
        | id  | stripe_customer_id | user_id | stripe_subscription_id | initial_charge_id           |
        +-----+--------------------+---------+------------------------+-----------------------------+
        |   1 | cus_9eFdpG03UI6Amd |     248 | N                      | NULL                        |
        |   2 | cus_9eHdhzycmZVBZ7 |     251 | N                      | NULL                        |
        |   3 | cus_9eHkVJEpA9NvK4 |     252 | sub_9eHkTNanpRYy5d     | ch_19KzBRAFFppZtWAu1b0Bz7mU |
        |   4 | cus_9eMugNVoEYIoPg |     257 | sub_9eMuU8Mb5nDVHD     | ch_19L4AKAFFppZtWAuCItAh1E5 
        */

        $faker = Faker\Factory::create();
        $data = [];
        
        //n
        $data[] = [
            'stripe_customer_id' => 'cus_' . '8randomstring8',
            'user_id' => '159',
            'stripe_subscription_id' => 'N',
            'initial_charge_id' => NULL
        ];

        //actual subscription_id
        $data[] = [
            'stripe_customer_id' => 'cus_81ZXyi4seLYvAD',
            'user_id' => '157',
            'stripe_subscription_id' => 'sub_9tycRz1x0niFRu',
            'initial_charge_id' => 'ch_' . '8randomstring8'
        ];


        $businesses = $this->table('stripe_subscriptions');
        $businesses->insert($data)->save();
    }
}
