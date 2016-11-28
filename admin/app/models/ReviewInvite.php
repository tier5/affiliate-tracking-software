<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    use Vokuro\Models\ReviewSite;


    class ReviewInvite extends Model {

        const RATING_TYPE_YES_NO    = 1;
        const RATING_TYPE_5_STAR    = 2;
        const RATING_TYPE_NPS       = 3;

        public $review_invite_id;
        public $date_sent;
        public $phone;
        public $name;
        public $followed_link;
        public $api_key;
        public $location_id;
        public $date_viewed;
        public $review_invite_type_id;
        public $rating;
        public $recommend;
        public $date_last_sent;
        public $times_sent;


        public function initialize() {
            $this->setSource('review_invite');


            $this->hasManyToMany(
                "review_invite_id",
                "Vokuro\Models\ReviewInviteReviewSite",
                "review_invite_id",
                "review_site_id",
                "Vokuro\Models\ReviewSite",
                "review_site_id",
                array('alias' => 'review_sites')
            );
        }


        /*
          * Find the data for the report
          */
        public static function findCustomers($agency_id) {
            $location_list = '';
            if (!empty($_POST['locations'])) {
                foreach ($_POST['locations'] as $check) {
                    //add comma if started
                    if ($location_list != '') $location_list .= ',';
                    $location_list .= $check;
                } // go to the next location
                //clean the locatin list for use in query
                $location_list = preg_replace(
                    array(
                        '/[^\d,]/',    // Matches anything that's not a comma or number.
                        '/(?<=,),+/',  // Matches consecutive commas.
                        '/^,+/',       // Matches leading commas.
                        '/,+$/'        // Matches trailing commas.
                    ),
                    '',              // Remove all matched substrings.
                    $location_list
                );
            }
            $start = '1980-01-01';
            $now = new \DateTime('tomorrow');
            $end = $now->format('Y-m-d');
            if (isset($_POST['start_date']) && $_POST['start_date'] != '') {
                $start = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['start_date'])));
            }
            if (isset($_POST['end_date']) && $_POST['end_date'] != '') {
                $end = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['end_date'])));
            }
//echo '<pre>$_POST:'.print_r($_POST,true).'</pre>';
            //echo '<p>$start:'.$start.':$end:'.$end.'</p>';
            $negative = (isset($_POST['review_type_negative']) && $_POST['review_type_negative'] == 1 ? 1 : 0);
            $positive = (isset($_POST['review_type_positive']) && $_POST['review_type_positive'] == 1 ? 1 : 0);

            //if there are no locations, then don't bother searching
            if ($location_list != '' && ($negative == 1 || $positive == 1)) {
                // A raw SQL statement
                $sql = "SELECT l.name AS location_name, ri.*
              FROM review_invite ri
                INNER JOIN location l ON ri.location_id = l.location_id
              WHERE l.agency_id = " . $agency_id . " AND ri.recommend IS NOT NULL AND ri.date_viewed IS NOT NULL AND ri.sms_broadcast_id IS NULL
                " . ($location_list != '' ? ' AND l.location_id IN (' . $location_list . ')' : '') . "
                " . ($negative == 1 && $positive != 1 ? " AND ri.recommend = 'N'" : '') . "
                " . ($positive == 1 && $negative != 1 ? " AND ri.recommend = 'Y'" : '') . "
                AND ri.date_sent BETWEEN '" . $start . " 00:00:00' AND '" . $end . " 23:59:59'
                AND ri.review_invite_id IN (SELECT MAX(ri2.review_invite_id)
		                                        FROM review_invite ri2
		                                          INNER JOIN location l2 ON ri2.location_id = l2.location_id
		                                        WHERE l2.agency_id = " . $agency_id . " AND ri2.recommend IS NOT NULL AND ri2.date_viewed IS NOT NULL
                                                " . ($location_list != '' ? ' AND l2.location_id IN (' . $location_list . ')' : '') . "
                                                " . ($negative == 1 && $positive != 1 ? " AND ri2.recommend = 'N'" : '') . "
                                                " . ($positive == 1 && $negative != 1 ? " AND ri2.recommend = 'Y'" : '') . "
                                                AND ri2.date_sent BETWEEN '" . $start . " 00:00:00' AND '" . $end . " 23:59:59' AND ri2.sms_broadcast_id IS NULL
		                                        GROUP BY ri2.phone)
              ORDER BY ri.date_sent DESC";

                //echo '<p>$sql:'.$sql.'</p>';

                // Base model
                $list = new ReviewInvite();

                // Execute the query
                $params = null;
                return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
            } else {
                return false;
            }
        }


        /*
          * Find the data for the report
          */
        public static function getInvitesPending() {
            // A raw SQL statement
            $sql = "SELECT ri.* 
              FROM review_invite ri
                INNER JOIN location l ON ri.location_id = l.location_id
              WHERE l.message_frequency > 0 AND l.message_tries > 0 AND ri.times_sent < l.message_tries AND ri.sms_broadcast_id IS NULL 
                #verify this isn't an old message
                AND ri.date_last_sent IS NOT NULL AND ri.date_sent > DATE_SUB(NOW(), INTERVAL ((l.message_frequency * l.message_tries)+1) HOUR)
                AND ri.date_last_sent < DATE_ADD(ri.date_sent, INTERVAL (l.message_frequency * ri.times_sent) HOUR)
                AND ri.date_viewed IS NULL";

            // Base model
            $list = new ReviewInvite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
          * Find the data for the report
          */
        public static function getReviewReport($location_id) {
            // A raw SQL statement
            $sql = "SELECT COUNT(review_invite.review_invite_id) AS daily_num
                FROM review_invite 
                WHERE review_invite.location_id = " . $location_id . " AND MONTH(review_invite.date_sent) = MONTH(NOW()) AND YEAR(review_invite.date_sent) = YEAR(NOW())";

            // Base model
            $list = new ReviewInvite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
          * Find the data for the report
          *
          *  Review Invitations list (search bar above list)
          *  (invites have the following fields:
          *  Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          *  Can respond to reviews by clicking the "Respond" button)
          */
        public static function getReviewInvitesByLocation($location_id, $only_site_clicked = false) {
            // A raw SQL statement
            $sql = "SELECT users.name AS sent_by, review_invite.*
                FROM review_invite 
                  INNER JOIN users ON review_invite.sent_by_user_id = users.id
                WHERE review_invite.location_id = " . $location_id . " AND sms_broadcast_id IS NULL
                  " . ($only_site_clicked ? 'AND (SELECT COUNT(*) FROM review_invite_review_site WHERE review_invite_review_site.review_invite_id = review_invite.review_invite_id) > 0' : '') . "
                ORDER BY review_invite.date_sent DESC";



                 /*$sql = "SELECT users.name AS sent_by, review_invite.*
                FROM review_invite 
                  INNER JOIN users ON review_invite.sent_by_user_id = users.id
                WHERE review_invite.location_id = " . $location_id . " AND sms_broadcast_id IS NULL ORDER BY review_invite.date_sent DESC";*/

                //exit;

            // Base model
            $list = new ReviewInvite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
          * Find the data for the report
          *
          *  Review Invitations list (search bar above list)
          *  (invites have the following fields:
          *  Phone/Email, Name, Sent By, Time Sent, Followed Link, Recommended?)
          *  Can respond to reviews by clicking the "Respond" button)
          */
        public static function getReviewInvitesByPhone($location_id, $phone) {
            // A raw SQL statement
            $sql = "SELECT users.name AS sent_by, review_invite.*
                FROM review_invite 
                  INNER JOIN users ON review_invite.sent_by_user_id = users.id
                WHERE review_invite.location_id = " . $location_id . " AND sms_broadcast_id IS NULL
                  AND review_invite.phone = '" . $phone . "'
                ORDER BY review_invite.date_sent DESC";

            // Base model
            $list = new ReviewInvite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
          * Find the data for the report
          *
          */
        public static function getReviewInviteClickReport($location_id) {
            //echo $location_id;exit;
            // A raw SQL statement
            $sql = "SELECT COUNT(rirs.review_site_id) AS num_clicks, rirs.review_site_id, rs.icon_path
                FROM review_invite ri
                  INNER JOIN review_invite_review_site rirs ON ri.review_invite_id = rirs.review_invite_id
                  INNER JOIN review_site rs ON rirs.review_site_id = rs.review_site_id
                WHERE ri.location_id = " . $location_id . " AND ri.sms_broadcast_id IS NULL
                GROUP BY rirs.review_site_id";//exit;

            // Base model
            $list = new ReviewInvite();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


    }