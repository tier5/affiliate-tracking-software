<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Validator\Uniqueness;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

    /**
     * Vokuro\Models\Review
     * The Reviews
     */
    class Review extends BaseModel
    {

        /**
         *
         * @var integer
         */
        public $review_id;

        /**
         *
         * @var integer
         */
        public $review_type_id;

        public $rating;
        public $review_text;
        public $time_created;
        public $user_name;
        public $user_id;
        public $user_image;
        public $external_id;
        public $location_id;
        public $rating_type_id;



        public function initialize()
        {
            $this->setSource('review');
        }

        public static function getMonthlyReviewStats() {
            $sql = "
              SELECT COUNT(*) AS count, MONTH(time_created) AS month, YEAR(time_created) AS year, location_id, rating_type_id, AVG(rating) AS rating 
              FROM review
              GROUP BY location_id, rating_type_id, YEAR(time_created), MONTH(time_created)              
            ";
            // Base model
            $list = new Review();

            // Execute the query
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, null));
        }
       



    }