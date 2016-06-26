<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\SMSBroadcast
     */
    class SMSBroadcast extends Model
    {
        public function initialize()
        {
            $this->setSource('sms_broadcast');
        }


        /*
         * Find the data for the report
         */
        public static function getReport($agency_id)
        {
            // A raw SQL statement
            $sql   = "SELECT sms_broadcast.*, (SELECT COUNT(review_invite_id) FROM review_invite WHERE review_invite.sms_broadcast_id = sms_broadcast.sms_broadcast_id) AS total_sent,
                  (SELECT COUNT(review_invite_id) FROM review_invite WHERE review_invite.sms_broadcast_id = sms_broadcast.sms_broadcast_id AND review_invite.date_viewed IS NOT NULL) AS total_clicked
                FROM sms_broadcast
                WHERE sms_broadcast.agency_id = ".$agency_id."
                ORDER BY sms_broadcast.date_sent DESC";
            //echo '<p>sql:'.$sql.'</p>';
            // Base model
            $list = new SMSBroadcast();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }
    }