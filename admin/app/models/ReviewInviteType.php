<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\Agency
 * The Locations
 */
class ReviewInviteType extends Model
{
	public function initialize()
	{
		$this->setSource('review_invite_type');
	}
}