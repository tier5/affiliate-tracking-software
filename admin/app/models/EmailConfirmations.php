<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Vokuro\Mail\Mail;

/**
 * EmailConfirmations
 * Stores the reset password codes and their evolution
 */
class EmailConfirmations extends Model
{


    public $id;
    public $usersId;
    public $code;
    public $createdAt;
    public $modifiedAt;
    public $confirmed;



    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->createdAt = time();

        // Generate a random confirmation code
        if(!$this->code) $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        // Set status to non-confirmed
        $this->confirmed = 'N';
    }

    /**
     * Sets the timestamp before update the confirmation
     */
    public function beforeValidationOnUpdate()
    {
        // Timestamp the confirmaton
        $this->modifiedAt = time();
    }

    /**
     * Send a confirmation e-mail to the user after create the account
     */
    public function afterCreate()
    {
        $email = new \Vokuro\Services\Email();
      try {
       $email->sendActivationEmailByUserId($this->usersId);
      } catch (Exception $e) {
          print $e;
        //do nothing
      }
    }

    public function initialize()
    {
        $this->belongsTo('usersId', __NAMESPACE__ . '\Users', 'id', array(
            'alias' => 'user'
        ));
    }
}
