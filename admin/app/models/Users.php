<?php
namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Vokuro\Models\UsersLocation;

/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Users extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $mustChangePassword;

    /**
     *
     * @var string
     */
    public $profilesId;

    /**
     *
     * @var string
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $suspended;

    /**
     *
     * @var string
     */
    public $active;
    
    /**
     *
     * @var integer
     */
    public $agency_id;

    public $sent_by_user_id;
    public $phone;


    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
      if (empty($this->password)) {
        // Generate a plain temporary password
        $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

        // The user must change its password in first login
        $this->mustChangePassword = 'Y';

        // Use this password as default
        $this->password = $this->getDI()
            ->getSecurity()
            ->hash($tempPassword);
      } else {
        // The user must not change its password in first login
        $this->mustChangePassword = 'N';
      }

      // The account must be confirmed via e-mail
      $this->active = 'N';

      // The account is not suspended by default
      $this->suspended = 'N';

      // The account is not banned by default
      $this->banned = 'N';
    }


    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
      if ($this->active == 'N') {
        $emailConfirmation = new EmailConfirmations();
        $emailConfirmation->usersId = $this->id;
        $emailConfirmation->save();
        /*if ($emailConfirmation->save()) {
          $this->getDI()
              ->getFlash()
              ->notice('A confirmation email has been sent to ' . $this->email);
        }*/
      }
    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
      $this->validate(new Uniqueness(array(
        "field" => "email",
        "message" => "The email is already registered"
      )));

      return $this->validationHasFailed() != true;
    }

    public function initialize()
    {
      $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', array(
        'alias' => 'profile',
        'reusable' => true
      ));
      /*
      $this->hasMany('id', __NAMESPACE__ . '\SuccessLogins', 'usersId', array(
        'alias' => 'successLogins',
        'foreignKey' => array(
          'message' => 'Employee cannot be deleted because he/she has activity in the system'
        )
      ));

      $this->hasMany('id', __NAMESPACE__ . '\PasswordChanges', 'usersId', array(
        'alias' => 'passwordChanges',
        'foreignKey' => array(
          'message' => 'Employee cannot be deleted because he/she has activity in the system'
        )
      ));

      $this->hasMany('id', __NAMESPACE__ . '\ResetPasswords', 'usersId', array(
        'alias' => 'resetPasswords',
        'foreignKey' => array(
          'message' => 'Employee cannot be deleted because he/she has activity in the system'
        )
      ));*/
        
      $this->hasManyToMany(
        "id",
        "Vokuro\Models\UsersLocation",
        "user_id",
        "location_id",
        "Vokuro\Models\Location",
        "location_id",
        array('alias' => 'locations')
      );
    }
    
    /*
     * This function pull up a report of the top employees 
     */
    public static function getEmployeeConversionReport($agency_id, $start_time, $end_time, $location_id, $sort_order)
    {
        // A raw SQL statement
        $sql   = "SELECT users.name, users.id, 
                    #find all invites sent this month
                    (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id 
                      AND review_invite.date_sent >= DATE_FORMAT(NOW(), '%Y-%m')) AS sms_sent_this_month, 
                    #find all invites sent 
                    (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id) AS sms_sent_all_time,
                    #find all invites positive feedback
                    (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id AND recommend = 'Y') AS positive_feedback
                  FROM users 
                  WHERE users.agency_id = ".$agency_id."
                  ORDER BY (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id 
                      AND review_invite.date_sent >= DATE_FORMAT(NOW(), '%Y-%m')) ".$sort_order.";";

        // Base model
        $list = new Users();

        // Execute the query
        $params = null;
        return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }


    
    
    /*
     * This function pull up a report of the top employees 
     */
    public static function getEmployeeListReport($agency_id, $start_time, $end_time, $location_id, $review_invite_type_id)
    {
      // A raw SQL statement
      $sql   = "SELECT users.id, users.name, 
                  (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id".($start_time?" AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."'":'').") AS sms_sent_all_time,
                  ".
                  ($review_invite_type_id==1?"((SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id AND recommend = 'Y' ".($start_time?" AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."'":'').") / (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id AND recommend IS NOT NULL".($start_time?" AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."'":'').") * 100) AS avg_feedback":
                  "(SELECT AVG(rating) FROM review_invite WHERE review_invite.location_id = ".$location_id."  AND review_invite.review_invite_type_id = ".$review_invite_type_id." AND rating IS NOT NULL AND rating != '' AND users.id = review_invite.sent_by_user_id AND recommend = 'Y'".($start_time?" AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."'":'').") AS avg_feedback")
                  .", ".$review_invite_type_id." AS review_invite_type_id
                FROM users 
                WHERE users.agency_id = ".$agency_id."
                ORDER BY (SELECT COUNT(*) FROM review_invite WHERE review_invite.location_id = ".$location_id." AND users.id = review_invite.sent_by_user_id".($start_time?" AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."'":'').") DESC;";
      //echo '<p>sql:'.$sql.'</p>';

      // Base model
      $list = new Users();

      // Execute the query
      $params = null;
      return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }
    
    
    /*
     * Find the data for the report
     */
    public static function getReportData($location_id, $start_time, $end_time, $conversion_report_type)
    {
        // A raw SQL statement
        $sql   = "SELECT DAY(review_invite.date_sent) AS day_num, COUNT(review_invite.review_invite_id) AS daily_num
                  FROM agency 
                    INNER JOIN location ON agency.agency_id = location.agency_id
                    INNER JOIN review_invite ON location.location_id = review_invite.location_id
                  WHERE location.location_id = ".$location_id." AND review_invite.date_sent >= '".$start_time."' AND review_invite.date_sent <= '".$end_time."' 
                    ".($conversion_report_type=='click_through'?" AND review_invite.date_viewed IS NOT NULL":"")."
                    ".($conversion_report_type=='conversion'?" AND review_invite.date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != ''))":"")."
                  GROUP BY DAY(review_invite.date_sent)                
                  ORDER BY DAY(review_invite.date_sent)";

        // Base model
        $list = new Users();

        // Execute the query
        $params = null;
        return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }

    
    
    
    /*
     * Find the data for the report
     */
    public static function findRecord($arr, $num)
    {
        $returnnum = 0;
        foreach ($arr as $value) {
          if ($value['day_num'] == $num) {
            $returnnum = $value['daily_num'];
          }
        }
        return $returnnum;
    }
    
    
    /*
     * Find the data for the report
     */
    public static function getEmployeesByUser($user, $profilesId)
    {
      // A raw SQL statement
      $sql   = "SELECT users.*
                FROM users
                  INNER JOIN users_location ON users.id = users_location.user_id
                  INNER JOIN users u ON users_location.user_id = u.id
                WHERE users.agency_id = ".$user->agency_id." AND users_location.location_id IN (SELECT ul.location_id FROM users_location ul WHERE ul.user_id = ".$user->id.")
                  AND users.profilesId != 1 AND users.profilesId != 4 AND users.profilesId = ".$profilesId."
                ORDER BY users.create_time ASC";
      //echo '<p>sql:'.$sql.'</p>';
      // Base model
      $list = new Users();

      // Execute the query
      $params = null;
      return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }
    
    
    /*
     * Find the data for the report
     */
    public static function getEmployeesByLocation($locationid)
    {
      // A raw SQL statement
      $sql   = "SELECT users.*
                FROM users
                  INNER JOIN users_location ON users.id = users_location.user_id
                WHERE users_location.location_id = ".$locationid." AND users.profilesId = 3 
                ORDER BY users.create_time ASC";
      //echo '<p>sql:'.$sql.'</p>';
      // Base model
      $list = new Users();

      // Execute the query
      $params = null;
      return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }
    

    
    /*
     * Find the data for the report
     */
    public static function getDailySignupCount()
    {
      // A raw SQL statement
      $now = new \DateTime('today');
      $sqltime = $now->format('Y-m-d');
      $sql   = "SELECT a.agency_id, MIN(u.create_time) AS date_created
                FROM agency a 
                  INNER JOIN users u ON a.agency_id = u.agency_id
                GROUP BY a.agency_id
                HAVING date_created > '".$sqltime." 00:00:00'";

      //echo '<p>sql:'.$sql.'</p>';
      // Base model
      $list = new Users();

      // Execute the query
      $params = null;
      return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
    }

}
