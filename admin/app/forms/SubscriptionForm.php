<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Vokuro\Models\SubscriptionInterval;

class SubscriptionForm extends Form
{

  public function initialize($entity = null, $options = null)
  {
    // In edition the id is hidden
    if (isset($options['edit']) && $options['edit']) {
      $subscription_id = new Hidden('subscription_id');
    } else {
      $subscription_id = new Text('subscription_id');
    }
    $this->add($subscription_id);


    $name = new Text('name', array(
        'placeholder' => 'Name'
    ));
    $name->addValidators(array(
        new PresenceOf(array(
            'message' => 'The name is required'
        ))
    ));
    $this->add($name);
        

    $subscription_interval_id = new Select('subscription_interval_id', SubscriptionInterval::find(), array(
        'using' => array(
          'subscription_interval_id',
          'name'
        ),
        'useEmpty' => true,
        'emptyText' => '...',
        'emptyValue' => ''
    ));
    $subscription_interval_id->addValidator(new PresenceOf(array(
        'message' => 'The interval is required'
    )));
    $this->add($subscription_interval_id);


    $duration = new Numeric('duration', array(
        'placeholder' => 'Duration',
        'min' => '1',
        'max' => '99'
    ));
    $duration->addValidator(new PresenceOf(array(
        'message' => 'The Duration is required'
    )));
    $this->add($duration);


    $amount = new Numeric('amount', array(
        'placeholder' => 'Amount',
        'min' => '0',
        'step' => '0.01'
    ));
    $amount->addValidator(new PresenceOf(array(
        'message' => 'The Amount is required'
    )));
    $this->add($amount);


    $trial_amount = new Numeric('trial_amount', array(
        'placeholder' => 'Trial Amount',
        'min' => '0',
        'step' => '0.01'
    ));
    $this->add($trial_amount);

    $trial_length = new Numeric('trial_length', array(
        'placeholder' => 'Trial Length',
        'min' => '1',
        'max' => '99'
    ));
    $this->add($trial_length);

  }
}
