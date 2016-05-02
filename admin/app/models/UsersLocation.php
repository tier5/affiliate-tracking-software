<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\Agency
 * The Locations
 */
class UsersLocation extends Model
{
	public function initialize()
	{
		$this->setSource('users_location');
    
    $this->belongsTo('user_id', 'Vokuro\Models\Users', 'id', 
      array('alias' => 'users')
    );
    $this->belongsTo('location_id', 'Vokuro\Models\Location', 'location_id', 
      array('alias' => 'location')
    );
	}
}