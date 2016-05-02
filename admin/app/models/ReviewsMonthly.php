<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * reviews_monthly
 * This model registers successfull logins registered users have made
 */
class ReviewsMonthly extends Model
{


	public function initialize()
	{
		$this->setSource('reviews_monthly');
	}
  
    
  /*
    * This function pull up a report of the top employees 
    */
  public static function newReviewReport($location_id)
  {
      // A raw SQL statement
      $sql   = "SELECT * FROM (
	                SELECT COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0) AS reviewcount, month, year 
	                FROM reviews_monthly 
	                WHERE location_id = ".$location_id." 
	                ORDER BY year DESC, month DESC
	                LIMIT 7) AS temp
                ORDER BY year ASC, month ASC";
      //echo '<p>sql:'.$sql.'</p>';

      // Base model
      $list = new ReviewsMonthly();

      // Execute the query
      $params = null;
      return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
  }
}
