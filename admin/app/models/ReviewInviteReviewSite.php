<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    use Vokuro\Models\ReviewSite;

    /**
     * Vokuro\Models\LocationNotifications
     */
    class ReviewInviteReviewSite extends Model
    {
         public $review_invite_id;
         public $review_site_id;

        public function initialize()
        {
            $this->setSource('review_invite_review_site');

            $this->belongsTo('review_invite_id', 'Vokuro\Models\ReviewInvite', 'id',
                array('alias' => 'review_invites')
            );
            $this->belongsTo('review_site_id', 'Vokuro\Models\ReviewSite', 'review_site_id',
                array('alias' => 'review_sites')
            );
        }


            public static function countexists($review_invite_id, $review_site_id) {
               
            // A raw SQL statement
            $sql = "SELECT *
                FROM review_invite_review_site  WHERE review_invite_id = " . $review_invite_id . " AND review_site_id = ".$review_site_id;

                //exit;

            // Base model
            $list = new ReviewInviteReviewSite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }



    }