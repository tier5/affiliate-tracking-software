<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    use Vokuro\Models\ReviewSite;

    /**
     * Vokuro\Models\LocationNotifications
     */
    class ReviewInviteReviewSite extends Model
    {
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
    }