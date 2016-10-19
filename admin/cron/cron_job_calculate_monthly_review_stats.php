<?php
    require 'bootstrap.php';

    $dbAllData = \Vokuro\Models\Review::getMonthlyReviewStats();

    foreach($dbAllData as $objData) {
        $objReviewsMonthly = \Vokuro\Models\ReviewsMonthly::findFirst("
            location_id = {$objData->location_id} AND
            year = {$objData->year} AND
            month = {$objData->month}            
        ");

        if(!$objReviewsMonthly)
            $objReviewsMonthly = new \Vokuro\Models\ReviewsMonthly();

        $objReviewsMonthly->year = $objData->year;
        $objReviewsMonthly->month = $objData->month;
        $objReviewsMonthly->location_id = $objData->location_id;

        switch($objData->rating_type_id) {
            case \Vokuro\Models\Location::TYPE_YELP:
                $objReviewsMonthly->yelp_rating = $objData->rating;
                $objReviewsMonthly->yelp_review_count = $objData->count;
                break;
            case \Vokuro\Models\Location::TYPE_FACEBOOK:
                $objReviewsMonthly->facebook_rating = $objData->rating;
                $objReviewsMonthly->facebook_review_count = $objData->count;
                break;
            case \Vokuro\Models\Location::TYPE_GOOGLE:
                $objReviewsMonthly->google_rating = $objData->rating;
                $objReviewsMonthly->google_review_count = $objData->count;
                break;
        }

        $objReviewsMonthly->save();
    }