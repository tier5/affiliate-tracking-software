<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Vokuro\Models\SharingCode
 * The sharing code for agencies
 */
class SharingCode extends Model
{
	public function initialize()
	{
		$this->setSource('sharing_code');
	}
}