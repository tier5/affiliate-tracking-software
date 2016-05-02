<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\SubscriptionStripe
 * The model for Stripe subscription configuration
 */
class SubscriptionStripe extends Model
{
	public function initialize()
	{
		$this->setSource('subscription_stripe');
	}
}