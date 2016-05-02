<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\UsersSubscriptionPayment
 * The regions
 */
class UsersSubscriptionPayment extends Model
{
	public function initialize()
	{
		$this->setSource('users_subscription_payment');
	}
}