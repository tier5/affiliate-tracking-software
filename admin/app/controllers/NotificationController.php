<?php
    namespace Vokuro\Controllers;

    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\SharingCode;
    use Vokuro\Models\Users;
    use Vokuro\Models\UsersSubscription;

    /**
     * Display the default index page.
     */
    class NotificationController extends ControllerBusinessBase {
        public function initialize() {
            $tUser = $this->auth->getIdentity();
            $logged_in = is_array($tUser);
            if ($logged_in && $tUser['profile'] == 'Agency Admin') {
                if (isset($_POST['locationselect'])) {
                    $this->auth->setLocation($_POST['locationselect']);
                }

                $this->view->setVar('logged_in', $logged_in);
                $this->view->setTemplateBefore('private');
            } else {
                $this->response->redirect('/session/login');
                $this->view->disable();
                return;
            }
            parent::initialize();
        }
         public function allnotificationAction($id=0) {
            //echo $id;
             $tUser = $this->auth->getIdentity();
            $logged_in = is_array($tUser);
            //echo "<pre>";
            //print_r($tUser);

            $result=$this->db->query(" SELECT * FROM `notification` WHERE `to` =".$id);
            $this->view->notification=$result->fetchAll();
            $this->db->query(" UPDATE `notification` SET `read`=1 WHERE `to`=".$id);
            $resultx=$this->db->query(" SELECT * FROM `notification` WHERE `to` =".$id." AND `read` = 0");
                 $x=$resultx->numRows();
            $this->view->setVar('NumberOfNotification', $x);
            $this->view->pick("Notification/allnotification");
        }

    }