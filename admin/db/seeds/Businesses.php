<?php

use Phinx\Seed\AbstractSeed;

class Businesses extends AbstractSeed
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
        twilio_from_phone   varchar(20) YES     NULL    
        logo_path   varchar(255)    YES     NULL    
        sms_message_logo_path   varchar(255)    YES     NULL    
        main_color  varchar(7)  YES     NULL    
        secondary_color varchar(7)  YES     NULL    
        stripe_account_id   varchar(50) YES     NULL    
        stripe_account_secret   varchar(50) YES     NULL    
        stripe_publishable_keys varchar(50) YES     NULL    
        viral_sharing_code  varchar(255)    YES     NULL    
        review_order_facebook   int(11) YES     NULL    
        review_order_google int(11) YES     NULL    
        review_order_yelp   int(11) YES     NULL    
        message_frequency   int(11) YES     NULL    
        referrer_code   varchar(50) YES     NULL    
        parent_agency_id    int(11) YES     NULL    
        stripe_token    varchar(50) YES     NULL    
        stripe_customer_id  varchar(50) YES     NULL    
        stripe_subscription_id  varchar(50) YES     NULL    
        subscription_valid  char(1) NO      Y   
        agency_type_id  int(11) NO  MUL 1   
        date_created    datetime    YES     NULL    
        email   varchar(255)    YES     NULL    
        address varchar(255)    YES     NULL    
        locality    varchar(255)    YES     NULL    
        state_province  varchar(255)    YES     NULL    
        postal_code varchar(45) YES     NULL    
        country varchar(5)  YES     NULL    
        latitude    varchar(30) YES     NULL    
        longitude   varchar(30) YES     NULL    
        phone   varchar(30) YES     NULL    
        status  int(1)  NO      1   
        deleted int(1)  NO      0   
        subscription_id int(11) YES     NULL    
        date_left   datetime    YES     NULL    
        signup_page int(11) YES     NULL    
        sms_top_bar varchar(7)  YES     NULL    
        sms_button_color    varchar(7)  YES     NULL    
        sms_text_message_default    varchar(255)    YES     NULL    

        parent_id   int(11) NO      0   

        address2    varchar(255)    YES         
        website varchar(255)    YES         
        email_from_name varchar(255)    YES         
        email_from_address  varchar(255)    YES      

        intercom_api_id varchar(255)    YES     NULL    
        intercom_security_hash  varchar(255)    YES     NULL    
        upgraded_status int(11) NO      0   
        twitter_message varchar(255)    YES     NULL
        */


        $faker = Faker\Factory::create();
        $data = [];
        
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'name'      => $faker->userName,
                'review_invite_type_id' => NULL,
                'review_goal' => 10,
                'custom_domain' => NULL,
                'lifetime_value_customer' => NULL,
                'SMS_message' => NULL,
                'message_tries' => 1,
                'notifications' => NULL,
                'rating_threshold_star' => 4,
                'rating_threshold_nps' => 8,
                'twilio_api_key' => NULL,
                'twilio_auth_token' => NULL,
                'twilio_auth_messaging_sid' => NULL,

                'parent_id' => -3,

                'address2' => '',
                'website' => '',
                'email_from_name' => '',
                'email_from_address' => ''

                /*'password'      => sha1($faker->password),
                'password_salt' => sha1('foo'),
                'email'         => $faker->email,
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'created'       => date('Y-m-d H:i:s'),*/
            ];
        }


        $businesses = $this->table('agency');
        $businesses->insert($data)->save();
    }
}
