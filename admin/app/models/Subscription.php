<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Vokuro\Models\SubscriptionInterval;

/**
 * Vokuro\Models\Subscription
 * The regions
 */
class Subscription extends Model
{
	public function initialize()
	{
		$this->setSource('subscription');

    $this->belongsTo('subscription_interval_id', __NAMESPACE__ . '\SubscriptionInterval', 'subscription_interval_id', array(
      'alias' => 'interval',
      'reusable' => true
    ));
	}
}