<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Vokuro\Models\SubscriptionInterval;

class AgencyForm extends Form
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


    $name = new Text('name', array(
        'placeholder' => 'Business Name'
    ));
    $name->addValidators(array(
        new PresenceOf(array(
            'message' => 'The name is required'
        ))
    ));
    $this->add($name);
    
    $this->add(new Text('email'));
    $this->add(new Text('address'));
    $this->add(new Text('address2'));
    $this->add(new Text('locality'));
    $this->add(new Text('state_province'));
    $this->add(new Text('postal_code'));
    $this->add(new Text('country'));
    $this->add(new Text('phone'));
    $this->add(new Text('website'));
    $this->add(new Text('email_from_name'));
    $this->add(new Text('email_from_address'));
    $this->add(new TextArea('welcome_email'));
    $this->add(new TextArea('viral_email'));
    $this->add(new TextArea('welcome_email_employee'));
        

  }
}
