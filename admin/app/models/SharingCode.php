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

		static public function GenerateShareCode($Length = 16) {
            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $ViralCode = '';
            while(true) {
                for ($i = 0; $i < $Length; $i++) {
                    $ViralCode .= $characters[mt_rand(0, strlen($characters) - 1)];
                }
                $objAgency = \Vokuro\Models\Agency::findFirst("viral_sharing_code = '{$ViralCode}'");
                if(!$objAgency) {
                    return $ViralCode;
                }
            }
        }
	}