<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Vokuro\Models\Agency;
use Vokuro\Models\ReviewInviteType;
use Vokuro\Models\Users;
use Vokuro\Models\LocationNotifications;

class SettingsForm extends Form
{

    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $agency_id = new Hidden('agency_id');
        } else {
            $agency_id = new Text('agency_id');
        }

        $this->add($agency_id);

        $review_invite_type_id = new Select('review_invite_type_id', ReviewInviteType::find(), array(
            'using' => array(
                'review_invite_type_id',
                'review_invite_type_name'
            ),
            'useEmpty' => true,
            'emptyText' => '...',
            'emptyValue' => ''
        ));
        $review_invite_type_id->addValidator(new PresenceOf(array(
            'message' => 'The review invite type is required'
        )));
        $this->add($review_invite_type_id);

        $rating_threshold_star = new Numeric('rating_threshold_star', array(
            'placeholder' => 'Name',
            'min' => '0',
            'max' => '5'
        ));
      //  $rating_threshold_star->addValidator(new PresenceOf(array(
      //      'message' => 'The Rating Threshold (Star Rating) is required'
      //  )));
        $this->add($rating_threshold_star);

        $rating_threshold_nps = new Numeric('rating_threshold_nps', array(
            'placeholder' => 'The Rating Threshold (NPS Rating) is required',
            'min' => '0',
            'max' => '10'
        ));
      //  $rating_threshold_nps->addValidator(new PresenceOf(array(
      //      'message' => 'The Rating Threshold (NPS Rating) is required'
      //  )));
        $this->add($rating_threshold_nps);

        $name = new Text('name', array(
            'placeholder' => 'Name'
        ));

        $this->add($name);

        $review_goal = new Text('review_goal', array(
            'placeholder'   => 'Review Goal',
        ));

        $this->add($review_goal);

        $custom_domain = new Text('custom_domain', array(
            'placeholder' => 'Custom Domain'
        ));

        $this->add($custom_domain);

        $lifetime_value_customer = new Text('lifetime_value_customer', array(
            'placeholder' => 'Lifetime Value of the Customer'
        ));

        $this->add($lifetime_value_customer);

        $SMS_message = new Text('SMS_message', array(
            'placeholder' => 'SMS Message'
        ));

        $this->add($SMS_message);

        $message_tries = new Numeric('message_tries', array(
            'placeholder' => 'SMS Message Tries',
            'min' => '0',
            'max' => '3'
        ));

        $this->add($message_tries);

        $message_frequency = new Numeric('message_frequency', array(
            'placeholder' => 'SMS Message Frequency',
            'min' => '0',
            'max' => '8760'
        ));

        $this->add($message_frequency);

        $notifications = new Text('notifications', array(
            'placeholder' => 'Notifications'
        ));

        $this->add($notifications);



        $twilio_api_key = new Text('twilio_api_key', array(
            'placeholder' => 'Twilio SID'
        ));
        /*$twilio_api_key->addValidator(new PresenceOf(array(
            'message' => 'The Twilio SID is required'
        )));*/
        $this->add($twilio_api_key);

        $twilio_auth_token = new Text('twilio_auth_token', array(
            'placeholder' => 'Twilio Auth Token'
        ));
        /*$twilio_auth_token->addValidator(new PresenceOf(array(
            'message' => 'The Twilio Auth Token is required'
        )));*/
        $this->add($twilio_auth_token);

        $twilio_auth_messaging_sid = new Text('twilio_auth_messaging_sid', array(
            'placeholder' => 'Twilio Messaging Service SID'
        ));
        $this->add($twilio_auth_messaging_sid);

        $twilio_from_phone = new Text('twilio_from_phone', array(
            'placeholder' => 'Twilio Phone Number'
        ));
        $this->add($twilio_from_phone);

        $stripe_account_id = new Text('stripe_account_id', array());
        $this->add($stripe_account_id);

        $stripe_account_secret = new Text('stripe_account_secret', array());
        $this->add($stripe_account_secret);

        $stripe_publishable_keys = new Text('stripe_publishable_keys', array());
        $this->add($stripe_publishable_keys);

        $intercom_api_id = new Text('intercom_api_id', array());
        $this->add($intercom_api_id);

        $intercom_security_hash = new Text('intercom_security_hash', array());
        $this->add($intercom_security_hash);
    }
}
