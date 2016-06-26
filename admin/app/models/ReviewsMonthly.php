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
            //loop for last seven months
            $end = strtotime("first day of next month");
            $start = $month = strtotime("-7 month", $end);
            $strSQL = "";
            while($month < $end)
            {
                if ($strSQL != '') $strSQL .= " UNION ";
                $strSQL .= "SELECT ".date('m', $month)." AS monthval, ".date('Y', $month)." AS yearval ".PHP_EOL;
                $month = strtotime("+1 month", $month);
            }

            // A raw SQL statement
            $sql   = "SELECT COALESCE(temp.reviewcount,0) AS reviewcount, dates.monthval AS month, dates.yearval AS year
              FROM (
                  ".$strSQL."
                ) AS dates
                LEFT OUTER JOIN (
                  SELECT COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0) AS reviewcount, month, year
                  FROM reviews_monthly
                  WHERE location_id = ".$location_id."
                  ORDER BY YEAR DESC, MONTH DESC LIMIT 7
                ) AS temp  ON temp.month = dates.monthval AND temp.year = dates.yearval
              ORDER BY year ASC, month ASC";
            //echo '<p>sql:'.$sql.'</p>';

            // Base model
            $list = new ReviewsMonthly();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }
    }
