<?php
namespace Vokuro\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Vokuro\Models\Profiles;

class UsersForm extends Form
{

    public function initialize($entity = null, $options = null)
    {

        // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $name = new Text('name', array(
            'placeholder' => 'Name'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'The name is required'
            ))
        ));

        $this->add($name);

        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The email is required'
            )),
            new Email(array(
                'message' => 'The email is not valid'
            ))
        ));

        $this->add($email);

        $phone = new Text('phone', array(
            'placeholder' => 'Phone'
        ));

        $this->add($phone);

        /*
        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));

        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));

        $password->clear();

        $this->add($password);*/

        /*
        $profilesId = new Select('profilesId', Profiles::find('active = "Y"'), array(
            'using' => array(
                'id',
                'name'
            ),
            'useEmpty' => true,
            'emptyText' => '...',
            'emptyValue' => ''
        ));
        $profilesId->addValidator(new PresenceOf(array(
            'message' => 'The role is required'
        )));
        $this->add($profilesId);*/


        $this->add(new Select('banned', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('suspended', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('active', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));
        
    }
}
