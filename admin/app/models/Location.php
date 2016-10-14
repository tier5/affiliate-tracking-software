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


    }