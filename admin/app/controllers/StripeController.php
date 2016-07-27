<?php
    namespace Vokuro\Controllers;

    use Phalcon\Tag;
    use Phalcon\Mvc\Model\Criteria;
    use Phalcon\Paginator\Adapter\Model as Paginator;
    use Vokuro\Forms\ChangePasswordForm;
    use Vokuro\Forms\StripeForm;
    use Vokuro\Models\Location;
    use Vokuro\Models\SubscriptionStripe;
    use Vokuro\Models\Users;
    use Stripe;

    /**
     * Vokuro\Controllers\StripeController
     * CRUD to manage stripe subscriptions
     */
    class StripeController extends ControllerBusinessBase {
        public function initialize() {
            if ($this->session->get('auth-identity')['profile'] == 'Agency Admin') {
                $this->tag->setTitle('Review Velocity | Subscriptions');
                $this->view->setTemplateBefore('private');
            } else {
                $this->response->redirect('/session/login?return=/stripe/');
                $this->view->disable();
                return;
            }

            return parent::initialize();
        }


        /**
         * Searches for stripe subscriptions
         */
        public function indexAction() {
            $identity = $this->auth->getIdentity();

            $conditions = "id = :id:";

            $parameters = array(
                "id" => $identity['id']
            );

            $userObj = Users::findFirst(
                array(
                    $conditions,
                    "bind" => $parameters)
            );

            $conditions = "agency_id = :agency_id:";

            $parameters = array(
                "agency_id" => $userObj->agency_id
            );

            $this->view->subscriptions = SubscriptionStripe::find(
                array(
                    $conditions,
                    "bind" => $parameters)
            );

        }


        /**
         * Creates a subscriptions
         */
        public function createAction() {
            $identity = $this->auth->getIdentity();
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

            if ($this->request->isPost()) {

                $sub = new SubscriptionStripe();

                $sub->assign(array(
                    'agency_id' => $userObj->agency_id,
                    'plan' => $this->request->getPost('plan', 'striptags'),
                    'amount' => $this->request->getPost('amount'),
                    'description' => $this->request->getPost('description'),
                ));

                if (!$sub->save()) {
                    $messages = array();
                    foreach ($sub->getMessages() as $message) {
                        $messages[] = str_replace("subscription_interval_id", "Interval", $message->getMessage());
                    }

                    $this->flash->error($messages);
                } else {
                    $this->flash->success("The subscription was created successfully");

                    Tag::resetInput();
                }
            }

            $this->view->subscription = new SubscriptionStripe();
            $this->view->form = new StripeForm(null);
        }

        protected function GetSubscriptionPrice() {
            // Need to determine subscription selected to generate price
            return 100; // One dolla make u holla
        }

        public function updatePaymentAction() {

            $this->view->StripePublishableKey = $this->config->stripe->publishable_key;
            $PaymentAmount = $this->GetSubscriptionPrice();
            $this->view->PaymentAmount = $PaymentAmount;
            //$this->CreatePlan(); // Only called once
        }

        /**
         * Gets the subscription / pricing plan for this business in our db and converts them to Stripe format
         */
        protected function GetStripeSubscriptions() {
            // TODO: Michael give me the magic to get the business subscription #s.  Hardcoding a sample.

            return [
                'Name'      => 'Best Business Deal Ever!!!',
                'Price'     => 100, // In cents
                'interval'  => 'month',
                'id'        => 'best_business',
            ];
        }

        /**
         * 1) Look for reoccuring billing in Stripe API
         *
         */
        public function submitPaymentAction() {
            // Need to verify payment is due, otherwise update card information.
            $PaymentAmount = $this->GetSubscriptionPrice();
            $this->view->PaymentAmount = $PaymentAmount;

            if ($this->request->isPost()) {
                $User = $this->auth->getUser();
                $Token = $this->request->getPost('stripeToken', 'striptags');
                // Going to let payee enter email address instead of assuming email in account.  TODO:  Check if this is correct.
                //$Email = $User->email;
                $Email = $this->request->getPost('email', 'striptags');

                $Customer = \Stripe\Customer::create([
                    'email'     => $Email,
                    'source'    => $Token,
                    'plan'      => 'best_business',
                ]);

                echo "<PRE>";
                print_r($Customer);
                // TODO:  Store Customer ID

                // This is a one time charge
 /*               $Charge = \Stripe\Charge::create([
                    'customer'  => $Customer->id,
                    'amount'    => $PaymentAmount,
                    'currency'  => 'usd',
                ]);*/

                echo "<h1> Successfully charged $" . $PaymentAmount / 100 . "!</h1>";
                die();


            }
        }
    }
