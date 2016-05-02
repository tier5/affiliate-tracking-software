<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\UsersSubscription
 * The regions
 */
class UsersSubscription extends Model
{
	public function initialize()
	{
		$this->setSource('users_subscription');
	}
}