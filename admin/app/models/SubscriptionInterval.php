<?php
	namespace Vokuro\Models;

	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Validator\Uniqueness;

	/**
	 * Vokuro\Models\SubscriptionInterval
	 * The regions
	 */
	class SubscriptionInterval extends Model
	{
		public function initialize()
		{
			$this->setSource('subscription_interval');
		}
	}