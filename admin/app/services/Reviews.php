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
    function __construct($config, $di)
    {
        parent::__construct($config, $di);
    }


    protected $types = [1,2,3];
    /**
     * @param int $rating_type_id the same as the type of review, 1,2,3 etc based off of source
     * @param int $location_id
     */
    public function updateCount($rating_type_id, $location_id){
       $review = new Review();
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
        return false;
    }

    public function updateCountsForLocationById($location_id){
        foreach($this->types  as $type_id) $this->updateCount($type_id,$location_id);
    }
}