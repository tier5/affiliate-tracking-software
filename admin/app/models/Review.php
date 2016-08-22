<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

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
    }