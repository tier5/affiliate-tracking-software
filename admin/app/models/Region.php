<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\Region
 * The regions
 */
class Region extends Model
{
	public function initialize()
	{
		$this->setSource('region');
	}
}