<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\ReviewSite
 */
class ReviewSite extends Model
{
	public function initialize()
	{
		$this->setSource('review_site');
	}
}