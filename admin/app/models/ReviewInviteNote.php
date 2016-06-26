<?php
	namespace Vokuro\Models;

	use Phalcon\Mvc\Model;
	use Phalcon\Mvc\Model\Validator\Uniqueness;

	/**
	 * Vokuro\Models\LocationNotifications
	 */
	class ReviewInviteNote extends Model
	{
		public function initialize()
		{
			$this->setSource('review_invite_note');

			$this->belongsTo('user_id', 'Vokuro\Models\Users', 'id',
				array('alias' => 'users')
			);
		}
	}