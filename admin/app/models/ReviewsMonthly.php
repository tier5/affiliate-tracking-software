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


        public static function newReviewReport($location_id)
        {
            $MonthsBack = 5;
            $StartMonth = date('n', strtotime("-{$MonthsBack} month"));
            $StartMonth2 = date('m', strtotime("-{$MonthsBack} month"));
            $StartYear = date('Y', strtotime("-{$MonthsBack} month"));

            $InitialCount = \Vokuro\Models\Review::count("location_id = {$location_id} AND time_created < '{$StartYear}-{$StartMonth2}-01 00:00:00'");

            $sql   = "
                SELECT COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0) AS reviewcount, month, year
                FROM reviews_monthly
                WHERE location_id = {$location_id}
                GROUP BY month, year
                ORDER BY month, year
            ";

            // Base model
            $list = new ReviewsMonthly();

            // Execute the query
            $params = null;
            $TotalResults = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));

            $tFilteredResults = [];
            $Count = 0;
            $Prev = 0;

            // Initialize all values to initial count
            $CurrentMonth = $StartMonth;
            for($c = 0 ; $c <= $MonthsBack ; $c++) {
                if ($c + $StartMonth > 12) {
                    if($CurrentMonth == 12) {
                        $CurrentMonth = 1;
                        $CurrentYear = $StartYear + 1;
                    }
                    else {
                        $CurrentMonth++;
                        $CurrentYear = $StartYear + 1;
                    }
                }
                else {
                    $CurrentMonth = $c + $StartMonth;
                    $CurrentYear = $StartYear;
                }

                $tFilteredResults[$CurrentMonth] = [
                    'reviewcount' => $InitialCount,
                    'month' => $CurrentMonth,
                    'year' => $CurrentYear
                ];
            }

            foreach($TotalResults->toArray() as $tResult) {
                if(strtotime("{$StartYear}-{$StartMonth}-01") <= strtotime("{$tResult['year']}-{$tResult['month']}-01")) {
                    if($Count == 0) {
                        $Prev = $tResult['reviewcount'];
                    } else {
                        $Prev += $tResult['reviewcount'];
                    }

                    $tResult['reviewcount'] = $Prev + $InitialCount;

                    $tFilteredResults[$tResult['month']] = $tResult;
                    $Count++;
                }
            }

            // Fill in correct values for empty months (must retain value of previous month)
            $Count = 0;
            foreach($tFilteredResults as $Index => &$tResult) {
                if($Count == 0) {
                    $Prev = 0;
                } else {
                    $Prev = $Index != 1 ? $tFilteredResults[$Index-1]['reviewcount'] : $tFilteredResults[12]['reviewcount'];
                }

                $Current = $tFilteredResults[$Index]['reviewcount'];

                if($Current <= $Prev)
                    $tResult['reviewcount'] = $Prev;

                $Count++;
            }

            return $tFilteredResults;
        }
    }
