<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\LocationNotifications
     */
    class LocationNotifications extends Model
    {
        public function initialize()
        {
            $this->setSource('location_notifications');

            $this->belongsTo('user_id', 'Vokuro\Models\Users', 'id',
                array('alias' => 'users')
            );
            $this->belongsTo('agency_id', 'Vokuro\Models\Agency', 'agency_id',
                array('alias' => 'agency')
            );
        }
    }