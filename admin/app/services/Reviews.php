<?php
/**
 * Created by PhpStorm.
 * User: scottconrad
 * Date: 7/22/16
 * Time: 7:28 AM
 */

namespace Vokuro\Services;


use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\Review;

class Reviews extends BaseService
{
    protected $const_class;
    protected $types = [];
    function __construct($config, $di){
        parent::__construct($config, $di);
        $this->types[] = ServicesConsts::$GOOGLE_REVIEW_TYPE;
        $this->types[] = ServicesConsts::$FACEBOOK_REVIEW_TYPE;
        $this->types[] = ServicesConsts::$YELP_REVIEW_TYPE;
        $this->types[] = 0; //this is the internal review type
    }

    /**
     * @param int $rating_type_id the same as the type of review, 1,2,3 etc based off of source
     * @param int $location_id
     */
    public function updateReviewCountByTypeAndLocationId($rating_type_id, $location_id){
       $review = new Review();
        if(!in_array($rating_type_id,$this->types)) {
            throw new \Exception("Invalid review type specified, you provided" .
                $rating_type_id . ', we expected one of: ' . implode(',', $this->types));
        }
        $query = $review->query()->where('location_id = :location_id:')->andWhere('rating_type_id = :rating_type_id:')->
        bind(['rating_type_id'=>$rating_type_id,'location_id'=>$location_id]);
        $count = $query->execute()->count();
        //get the LocationRevievw
        $lr = new LocationReviewSite();
        $result = $lr->findFirst([
            'conditions'=>'location_id = :location_id: AND review_site_id = :review_site_id:',
            'bind'=>['location_id'=>$location_id,'review_site_id'=>$rating_type_id]
            ]
        );

        if($result) {
            $result->update(['review_count' => $count]);
            $cc = $result->update();
            return $cc;
        }
        if(!$result) throw new \Exception("LocationReviewSite not found with rating_type_id of: {$rating_type_id} for
        location with id of: {$location_id}, perhaps it hasn't been imported?");
    }


    /**
     * This function takes in a location id, and updates the respective counts for each type id that is set in the construct
     * @param int $location_id
     */
    public function updateReviewCountsForLocationById($location_id){
        foreach($this->types  as $type_id) $this->updateCount($type_id,$location_id);
    }
}