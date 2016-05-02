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

class LocationForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        //location_id
        $this->add(new Hidden('location_id'));
        //name
        $this->add(new Hidden('name'));
        //phone
        $this->add(new Hidden('phone'));
        //address
        $this->add(new Hidden('address'));
        //locality
        $this->add(new Hidden('locality'));
        //state_province
        $this->add(new Hidden('state_province'));
        //postal_code
        $this->add(new Hidden('postal_code'));
        //country
        $this->add(new Hidden('country'));
        //yelp
        $this->add(new Hidden('yelp_id'));
        //facebook_page_id
        $this->add(new Hidden('facebook_page_id'));
        //google_place_id
        $this->add(new Hidden('google_place_id'));
        //latitude
        $this->add(new Hidden('latitude'));
        //longitude
        $this->add(new Hidden('longitude'));
        $this->add(new Hidden('google_api_id'));
    }
}
