<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;
    use Vokuro\Models\UsersLocation;
    use Phalcon\Validation;

    /**
     * Vokuro\Models\Users
     * All the users registered in the application
     */
    class Users extends BaseModel {
        public $id;
        public $name;
        public $email;
        public $password;
        public $mustChangePassword;
        public $profilesId;
        public $banned;
        public $suspended;
        public $active;
        public $agency_id;
        public $sent_by_user_id;
        public $is_admin;
        public $subscription_valid;
        public $phone;
        public $send_confirmation;
        public $is_all_locations;
        public $is_employee;
        public $role;
        public $top_banner_show;

        const ROLE_SUPER_ADMIN      = 'Super Admin';
        const ROLE_ADMIN            = 'Admin';
        const ROLE_USER             = 'User';

        /**
         * @return mixed
         */
        public function getId() {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id) {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getEmail() {
            return $this->email;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email) {
            $this->email = $email;
        }

        /**
         * @return mixed
         */
        public function getName() {
            return $this->name;
        }

        /**
         * @param mixed $name
         */
        public function setName($name) {
            $this->name = $name;
        }

        /**
         * @return mixed
         */
        public function getIsEmployee() {
            return $this->is_employee;
        }

        /**
         * @param mixed $is_employee
         */
        public function setIsEmployee($is_employee) {
            $this->is_employee = $is_employee;
        }


         /**
         * @return mixed
         */
        public function getTopBannerShow() {
            return $this->top_banner_show;
        }

        /**
         * @param mixed $top_banner_show
         */
        public function setTopBannerShow($top_banner_show) {
            $this->top_banner_show = $top_banner_show;
        }



        /**
         * @param $role
         */
        public function is_all_locations() {
            return $this->is_all_locations;
        }

        /**
         * @param $role
         */
        public function setRole($role) {
            $this->role = $role;
        }

        /**
         * @return mixed
         */
        public function getRole() {
            return $this->role;
        }

        public function getFirstName(){
            $first_name = null;
            $name = trim($this->getName());
            $space_exists = strpos($name,' ') > 0;
            if($space_exists){
                $parts = explode(' ',$name);
                $first_name = $parts[0];
            }

            return $first_name;
        }

        public function getById($id) {
            if(!is_numeric($id)) throw new \Exception("Invalid Id specified, expecting number");
            return $this->findFirst($id);
        }

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate() {
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
        public function afterSave() {
            if($this->is_employee && $this->send_confirmation && $this->role != 'Super Admin') {
                $emailConfirmation = new EmailConfirmations();
                $emailConfirmation->usersId = $this->id;
                $emailConfirmation->template = 'employee';
                if ($emailConfirmation->save()) {
                    $this->getDI()
                        ->getFlash()
                        ->notice('A confirmation email has been sent to ' . $this->email);
                }

            }

            if ($this->active == 'N' && $this->send_confirmation) {
                $emailConfirmation = new EmailConfirmations();
                $emailConfirmation->usersId = $this->id;
                if($emailConfirmation->save()) {
                  $this->getDI()
                      ->getFlash()
                      ->notice('A confirmation email has been sent to ' . $this->email);
                }
            }
            return true;
        }


        public function beforeValidationOnUpdate() {
           return true;
        }



        /**
         * Validate that emails are unique across users
         */
        public function validation() {
            if(!$this->id) {
              // this validation is commented due to removed email field from create business form agency
              //$this->validate(new Uniqueness(array(
              //    "field" => "email",
              //    "message" => "The email is already registered"
              //)));            
            }

            return $this->validationHasFailed() != true;
        }


        public function initialize() {
            $this->byPassConfirmationEmail = false;

            $this->belongsTo('profilesId', __NAMESPACE__ . '\Profiles', 'id', array(
                'alias' => 'profile',
                'reusable' => true
            ));

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
        public static function getEmployeeConversionReport($agency_id,
                                                           $start_time,
                                                           $end_time,
                                                           $location_id,
                                                           $sort_order) {
            // A raw SQL statement
            $sql = "SELECT distinct
                       users.name,
                       users.id,
                       (SELECT COUNT(*)
                          FROM
                              review_invite
                          WHERE
                              review_invite.location_id = ".$location_id." AND
                              sms_broadcast_id IS NULL AND
                              users.id = review_invite.sent_by_user_id AND
                              review_invite.date_sent >= DATE_FORMAT(NOW(), '%Y-%m'))
                          AS
                              sms_sent_this_month,

                        (SELECT COUNT(*)
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id AND
                                  review_invite.date_viewed >= DATE_FORMAT(NOW(), '%Y-%m'))
                            AS
                                  sms_received_this_month ,

                        (SELECT COUNT(*)
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id AND
                                  recommend = 'Y' AND
                                  review_invite.date_viewed >= DATE_FORMAT(NOW(), '%Y-%m'))
                            AS
                                  positive_feedback_this_month

                    FROM users
                    LEFT OUTER join users_location
                    ON users.id = users_location.user_id
                    WHERE (users_location.location_id = ".$location_id."  OR
                          users.is_all_locations = 1 ) AND users.agency_id = {$agency_id} AND
                      (users.profilesId = 3 OR users.is_employee = 1) OR (users.role = 'Super Admin' AND users.agency_id = {$agency_id})
                    ORDER BY positive_feedback_this_month desc
                  ;";
//echo $sql;
            // Base model  main dashboard
            $list = new Users();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
         * This function pull up a report of the top employees
         */
        public static function getEmployeeListReport($agency_id,
                                                     $start_time,
                                                     $end_time,
                                                     $location_id,
                                                     $review_invite_type_id,
                                                     $profilesId,
                                                     $employees_only) {

            if ($start_time == "") {$start_time = date("1970-01-01");}
            if ($end_time == "") {$end_time = date("Y-m-d H:i:s");}

            $ProfileWhere = '';
            switch($profilesId) {
                case 1:
                case 2:
                case 4:
                    break;

                case 3:
                    $ProfileWhere = ' AND profilesID = 4 ';
                    break;
            }

            if ($employees_only) {
                $ProfileWhere .= ' AND is_employee = 1';
            }
            // A raw SQL statement
            $sql = "SELECT DISTINCT
                      users.name,
                      users.id,
            		  users.is_employee,
                      users.email,
                      users.role,
                      (SELECT COUNT(*)
                          FROM
                              review_invite
                          WHERE
                              review_invite.location_id = ".$location_id." AND
                              sms_broadcast_id IS NULL AND
                              users.id = review_invite.sent_by_user_id AND
                              review_invite.date_sent >= DATE_FORMAT('" . $start_time . "', '%Y-%m-%d') AND
                              review_invite.date_sent <= DATE_FORMAT('" . $end_time . "', '%Y-%m-%d'))
                          AS
                              sms_sent_this_month,

                      (SELECT COUNT(*)
                          FROM
                                review_invite
                          WHERE
                                review_invite.location_id = ".$location_id." AND
                                sms_broadcast_id IS NULL AND
                                users.id = review_invite.sent_by_user_id AND
                                review_invite.date_viewed >= DATE_FORMAT('" . $start_time . "', '%Y-%m-%d') AND
                                review_invite.date_viewed <= DATE_FORMAT('" . $end_time . "', '%Y-%m-%d'))
                          AS
                                sms_received_this_month,

                      (SELECT COUNT(*)
                          FROM
                                review_invite
                          WHERE
                                review_invite.location_id = ".$location_id." AND
                                sms_broadcast_id IS NULL AND
                                users.id = review_invite.sent_by_user_id AND
                                recommend = 'Y' AND
                                review_invite.date_viewed >= DATE_FORMAT('" . $start_time . "', '%Y-%m-%d') AND
                                review_invite.date_viewed <= DATE_FORMAT('" . $end_time . "', '%Y-%m-%d'))
                          AS
                                positive_feedback_this_month

                    FROM users
                    LEFT OUTER JOIN users_location
                    ON users.id = users_location.user_id
                    WHERE (users_location.location_id = ".$location_id." OR
                          users.is_all_locations = 1) AND users.agency_id = {$agency_id} AND 
                          (users.profilesId = 3 OR users.is_employee = 1) OR (users.role = 'Super Admin' AND users.agency_id = {$agency_id}) 
                    ORDER BY positive_feedback_this_month desc
                    ;";
//echo $sql . "<BR><BR>";
            $list = new Users();

            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }


        /*
         * Find the data for the report
         */
        public static function getReportData($location_id,
                                             $start_time,
                                             $end_time,
                                             $conversion_report_type) {
            // A raw SQL statement
            $sql = "SELECT
                      DAY(review_invite.date_sent) AS day_num,
                      COUNT(review_invite.review_invite_id) AS daily_num
                    FROM agency
                    INNER JOIN location ON agency.agency_id = location.agency_id
                    INNER JOIN review_invite ON location.location_id = review_invite.location_id
                    WHERE
                      location.location_id = ".$location_id." AND
                      sms_broadcast_id IS NULL AND
                      review_invite.date_sent >= '".$start_time."' AND
                      review_invite.date_sent <= '".$end_time."'
                      ".($conversion_report_type=='click_through'?" AND review_invite.date_viewed IS NOT NULL":"")."
                      ".($conversion_report_type=='conversion'?" AND review_invite.date_viewed IS NOT NULL AND
                      (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != ''))":"")."
                    GROUP BY
                      DAY(review_invite.date_sent)
                    ORDER BY
                      DAY(review_invite.date_sent)";

            // Base model
            $list = new Users();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }

        /*
         * Find the data for the report
         */
        public static function findRecord($arr, $num) {
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
        public static function getEmployeesByUser($user,
                                                  $profilesId) {
            // A raw SQL statement
            $sql = "SELECT users.*
                FROM users
                  INNER JOIN users_location ON users.id = users_location.user_id
                  INNER JOIN users u ON users_location.user_id = u.id
                WHERE
                  users.agency_id = ".$user->agency_id." AND
                  users_location.location_id IN (
                    SELECT ul.location_id
                    FROM users_location ul
                    WHERE
                      ul.user_id = ".$user->id.") AND
                      users.profilesId != 1 AND
                      users.profilesId != 4 AND
                      users.profilesId = ".$profilesId."
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
        public static function getEmployeesByLocation($locationid) {
            // A raw SQL statement
            $sql   = "SELECT users.*
                FROM users
                  INNER JOIN users_location ON users.id = users_location.user_id
                WHERE
                  users_location.location_id = ".$locationid." AND
                  users.profilesId = 3
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
        public static function getDailySignupCount() {
            // A raw SQL statement
            $now = new \DateTime('today');
            $sqltime = $now->format('Y-m-d');
            $sql = "SELECT
                      a.agency_id,
                      MIN(u.create_time) AS date_created
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



            public static function getEmployeeConversionReportGenerate($review_invite_type_id,$agency_id,
                                                           $start_time,
                                                           $end_time,
                                                           $location_id,
                                                           $sort_order) {

              if($review_invite_type_id=='')
              {
                $review_invite_type_id=1;
              }
            // A raw SQL statement
            $sql = "SELECT distinct
                       users.name,
                       users.id,
                       (SELECT COUNT(*)
                          FROM
                              review_invite
                          WHERE
                              review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                              sms_broadcast_id IS NULL AND
                              users.id = review_invite.sent_by_user_id"
                              .(($start_time === false) ? ' ) ' : ' AND review_invite.date_sent >="' . $start_time . '") ').
                          "AS
                              sms_sent_this_month,

                        (SELECT COUNT(*)
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id"
                                  .(($start_time === false) ? ' ) ' : ' AND review_invite.date_sent >="' . $start_time . '") ').
                                  
                            "AS
                                  sms_received_this_month ,

                        (SELECT COUNT(*)
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id AND
                                  recommend = 'Y'"
                                  .(($start_time === false) ? ' ) ' : ' AND review_invite.date_sent >="' . $start_time . '") ').
                            "AS
                                  positive_feedback_this_month,


                                  (SELECT sum(review_invite.rating) 
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id AND
                                  recommend = 'Y'"
                                  .(($start_time === false) ? ' ) ' : ' AND review_invite.date_sent >="' . $start_time . '") ').
                            "AS
                                  rates

                    FROM users
                    LEFT OUTER join users_location
                    ON users.id = users_location.user_id
                    WHERE (users_location.location_id = ".$location_id."  OR
                          users.is_all_locations = 1 ) AND users.agency_id = {$agency_id} AND
                      (users.profilesId = 3 OR users.is_employee = 1) OR (users.role = 'Super Admin' AND users.agency_id = {$agency_id})
                    ORDER BY positive_feedback_this_month desc
                  ;";

            // Base model  main dashboard
            $list = new Users();

            // Execute the query
            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }




          public static function getEmployeeListReportGenerate($agency_id,
                                                     $start_time,
                                                     $end_time,
                                                     $location_id,
                                                     $review_invite_type_id,
                                                     $profilesId,
                                                     $employees_only) {

            if ($start_time == "") {$start_time = date("1970-01-01");}
            if ($end_time == "") {$end_time = date("Y-m-d H:i:s");}

            $ProfileWhere = '';
            switch($profilesId) {
                case 1:
                case 2:
                case 4:
                    break;

                case 3:
                    $ProfileWhere = ' AND profilesID = 4 ';
                    break;
            }

            if ($employees_only) {
                $ProfileWhere .= ' AND is_employee = 1';
            }

            if($review_invite_type_id=='')
            {
              $review_invite_type_id=1;
            }

            $order_by="positive_feedback_this_month";

            if($review_invite_type_id!=1)
            {
              $order_by="sms_received_this_month";
            }
            // A raw SQL statement
            $sql = "SELECT DISTINCT
                      users.name,
                      users.id,
                  users.is_employee,
                      users.email,
                      users.role,
                      (SELECT COUNT(*)
                          FROM
                              review_invite
                          WHERE
                              review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND 
                              sms_broadcast_id IS NULL AND
                              users.id = review_invite.sent_by_user_id AND
                              review_invite.date_sent >= '" . $start_time . "')
                          AS
                              sms_sent_this_month,

                      (SELECT COUNT(*)
                          FROM
                                review_invite
                          WHERE
                                review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                sms_broadcast_id IS NULL AND
                                users.id = review_invite.sent_by_user_id AND
                              review_invite.date_sent >= '" . $start_time . "')
                          AS
                                sms_received_this_month,

                      (SELECT COUNT(*)
                          FROM
                                review_invite
                          WHERE
                                review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                sms_broadcast_id IS NULL AND
                                users.id = review_invite.sent_by_user_id AND
                                recommend = 'Y' AND
                              review_invite.date_sent >= '" . $start_time . "')
                          AS
                                positive_feedback_this_month,
                                   (SELECT sum(review_invite.rating) 
                            FROM
                                  review_invite
                            WHERE
                                  review_invite.location_id = ".$location_id." AND review_invite.review_invite_type_id=".$review_invite_type_id." AND
                                  sms_broadcast_id IS NULL AND
                                  users.id = review_invite.sent_by_user_id AND
                                  recommend = 'Y' AND
                                  review_invite.date_viewed >= '". $start_time . "')
                            AS
                                  rates

                    FROM users
                    LEFT OUTER JOIN users_location
                    ON users.id = users_location.user_id
                    WHERE (users_location.location_id = ".$location_id." OR
                          users.is_all_locations = 1) AND users.agency_id = {$agency_id} AND 
                          (users.profilesId = 3 OR users.is_employee = 1) OR (users.role = 'Super Admin' AND users.agency_id = {$agency_id}) 
                    ORDER BY ".$order_by." desc
                    ;";

            $list = new Users();

            $params = null;
            return new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        }



    }
