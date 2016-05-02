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

class StripeForm extends Form
{

  public function initialize($entity = null, $options = null)
  {
    // In edition the id is hidden
    if (isset($options['edit']) && $options['edit']) {
      $subscription_stripe_id = new Hidden('subscription_stripe_id');
    } else {
      $subscription_stripe_id = new Text('subscription_stripe_id');
    }
    $this->add($subscription_stripe_id);


    $plan = new Text('plan', array(
        'placeholder' => 'Plan Name'
    ));
    $plan->addValidators(array(
        new PresenceOf(array(
            'message' => 'The plan name is required'
        ))
    ));
    $this->add($plan);


    $amount = new Numeric('amount', array(
        'placeholder' => 'Amount',
        'min' => '0',
        'step' => '0.01'
    ));
    $amount->addValidator(new PresenceOf(array(
        'message' => 'The amount is required'
    )));
    $this->add($amount);

    

    $description = new Text('description', array(
        'placeholder' => 'Description'
    ));
    $this->add($description);


  }
}
