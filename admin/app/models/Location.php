<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\Location
     * The Locations
     */
    class Location extends BaseModel
    {
    	const TYPE_FACEBOOK = 1;
    	const TYPE_YELP = 2;
        const TYPE_GOOGLE = 3;

        public $location_id;
        public $agency_id;
        public $name;
        public $phone;
        public $address;
        public $locality;
        public $state_province;
        public $postal_code;
        public $country;
        public $date_reviews_checked;
        public $latitude;
        public $longitude;
        public $region_id;



        public function initialize()
        {
            $this->setSource('location');
			$this->skipAttributes(['region_id']);
            $this->belongsTo('region_id', __NAMESPACE__ . '\Region', 'region_id', array(
                'alias' => 'region',
                'reusable' => true
            ));


            //$this->belongsTo('location_id', 'Vokuro\Models\LocationReviewSite', 'location_id',
            //  array('alias' => 'location_review_site')
            //);

            $this->hasManyToMany(
                "location_id",
                "Vokuro\Models\UsersLocation",
                "location_id",
                "user_id",
                "Models\Users",
                "id",
                array('alias' => 'users')
            );
        }

        /*public static function current()
        {
            $identity = $this->session->get('auth-identity');

            return $identity['location_id'];
        }*/

        /*
         * Find the data for the report
         */
        public static function getLocations($agency_id)
        {
            // A raw SQL statement
            $sql   = "SELECT location.*,
                (SELECT SUM(review_count) FROM location_review_site WHERE location_id = location.location_id) AS review_count,
                (SELECT SUM((rating * review_count)) FROM location_review_site WHERE location_id = location.location_id) AS rating
              FROM location
              WHERE location.agency_id = ".$agency_id."
              ORDER BY location.location_id ASC";
            //echo '<p>sql:'.$sql.'</p>';
            // Base model
            $list = new Location();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }

        public static function reviewType($locationId)
        {
            $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $locationId);
            $review_info = Location::findFirst(
                array($conditions, "bind" => $parameters)
            );

            if (!empty($review_info) && $review_info->review_invite_type_id) {
                $review_type_id = $review_info->review_invite_type_id;
            } else {
                $review_type_id = 1;
            }

            return $review_type_id;
        }

    }