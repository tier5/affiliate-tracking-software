<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class CreditCardForm extends Form
{

  public function initialize($entity = null, $options = null)
  {
    //credit card fields    
    $cardnumber = new Text('card-number');
    $cardnumber->addValidators(array(
        new PresenceOf(array(
            'message' => 'The card number is required'
        ))
    ));
    $this->add($cardnumber);

    $expirymonth = new Text('expiry-month');
    $expirymonth->addValidators(array(
        new PresenceOf(array(
            'message' => 'The expiration month is required'
        ))
    ));
    $this->add($expirymonth);

    $expiryyear = new Text('expiry-year');
    $expiryyear->addValidators(array(
        new PresenceOf(array(
            'message' => 'The expiration year is required'
        ))
    ));
    $this->add($expiryyear);

    $cvc = new Text('cvc');
    $cvc->addValidators(array(
        new PresenceOf(array(
            'message' => 'The CVC is required'
        ))
    ));
    $this->add($cvc);
  }

  /**
    * Prints messages for a specific element
    */
  public function messages($name)
  {
    if ($this->hasMessagesFor($name)) {
      foreach ($this->getMessagesFor($name) as $message) {
        $this->flash->error($message);
      }
    }
  }
}
