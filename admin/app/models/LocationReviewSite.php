<?php
	namespace Vokuro\Models;

	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Validator\Uniqueness;

	/**
	 * Vokuro\Models\LocationReviewSite
	 */
	class LocationReviewSite extends Model
	{
		public function initialize()
		{
			$this->setSource('location_review_site');

			$this->belongsTo('review_site_id', 'Vokuro\Models\ReviewSite', 'review_site_id',
				array('alias' => 'review_site')
			);
		}
	}