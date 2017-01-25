<?php

namespace Vokuro\Controllers;
use Phalcon\Mvc\Controller;
use Services\Facebook\Facebook;
use Vokuro\Models\Review;
use Vokuro\Services\Reviews;


class ReviewfeedsController extends ControllerBase
{

    /*public function initialize()
    {
        parent::initialize();
        $path_to_admin = realpath(__DIR__ . '/../../');
        include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
        define('APPLICATION_NAME', 'User Query - Google My Business API');
        define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');
        $this->tag->setTitle('Get Mobile Reviews | Dashboard');
    }

    protected function getAccessToken(){
        $length = sizeof($_SESSION['google_access_token']);
        if($length){
            return $_SESSION['google_access_token'][$length - 1];
        }
    }

    protected function setAccessToken($access_token){
        $_SESSION['google_access_token'][] = $access_token;
    }

    protected function setRefreshToken($refresh_token){
        $_SESSION['google_refresh_token'][] = $refresh_token;
    }

    protected function getRefreshToken(){
        $length = sizeof($_SESSION['google_refresh_token']);
        if ($length) {
            return $_SESSION['google_refresh_token'][$length - 1];
        }
    }

    public function googleReviewsAction(){

        $reviewService = new Reviews();

        $client = $this->getGoogleClient();
        try {
            //if we don't have a token, it will complain.. in that case we catch the error, and in our case
            //redirect back over to where they can click the link to get the new token
            $client->setAccessToken($this->getAccessToken());
        }catch(\Exception $e){
            return $this->response->redirect('/reviewfeeds/google');
            exit();
        }
        $myBusiness = new \Google_Service_Mybusiness($client);
        $accounts = $myBusiness->accounts->listAccounts()->getAccounts();
        if($accounts) foreach($accounts as $account){
            /**
             * @var $account \Google_Service_Mybusiness_Account
             */
            /*print '<h1>'.$account->accountName.'</h1>';
            $locations = $myBusiness->accounts_locations->listAccountsLocations($account->name)->getLocations();
            if($locations) foreach($locations as $location){
                print '<h1>Location:'.$location->locationName.'</h1>';
                /**
                 * @var $location \Google_Service_Mybusiness_Location
                 */
                /*$lr = $myBusiness->accounts_locations_reviews->listAccountsLocationsReviews($location->name);
                $reviews = $lr->getReviews();
                $reviewCount = $lr->getTotalReviewCount();
                $avg = $lr->getAverageRating();
                print "<p>review count {$reviewCount}</p>";
                print "<p>average: {$avg}</p>";
                if($reviews) foreach($reviews as $review){
                    /**
                     * @var $review \Google_Service_Mybusiness_Review Object
                     */
                    /*$rating = $review->getStarRating();
                    $ratings = ['ZERO' => 0,'ONE' => 1,'TWO' => 2,'THREE'=> 3 ,'FOUR' => 4,'FIVE' => 5];
                    $rating = $ratings[$rating];
                    $review_id = str_replace('/reviews','',$review->getReviewId());
                    $reviewer = $review->getReviewer();
                    /**
                     * @var $reviewer \Google_Service_Mybusiness_Reviewer
                     */
                    /*print '<pre>';
                    print '</pre>';
                    print "<h4>Comment: {$review->comment}</h4>";
                    print 'link<br />';
                    $arr = [
                        'rating_type_id' => 3,
                        'review_text' => $review->comment,
                        'rating_type_review_id' => $review_id,
                        'external_id' => $review_id,
                        'rating' => $rating,
                        'location_id' => 1
                    ];
                    $reviewService->saveReviewFromData($arr);
                }
            }
        }
    }

    protected function getGoogleClient(){

        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/reviewfeeds/google/';

        $client = new \Google_Client();

        $client->setApplicationName(APPLICATION_NAME);

        $client->setAuthConfigFile(CLIENT_SECRET_PATH);

        $client->addScope("https://www.googleapis.com/auth/plus.business.manage");

        $client->setRedirectUri($redirect_uri);


        // For retrieving the refresh token

        $client->setAccessType('offline');

        $client->setApprovalPrompt("force");
        return $client;
    }

    public function googleAction(){
        $client = $this->getGoogleClient();

        /************************************************

        We are going to create the Google My Business API

        service, and query it.

         ************************************************/
        /*$credentialsPath = CREDENTIALS_PATH;

        if (isset($_GET['code'])) {
            // Exchange authorization code for an access token.
            $client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');

            $accessToken = $client->authenticate($_GET['code']);
        }


        // Load previously authorized credentials from a file.
        $authUrl = $client->createAuthUrl();
        $this->view->authUrl = $authUrl;
        $this->view->setMainView('google/auth');

        if (!$accessToken)
            return;
        //$client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');
        $client->setAccessToken($accessToken['access_token']);
        // Refresh the token if it's expired.
        $access_token = $client->getAccessToken();
        $refreshToken = $client->getRefreshToken();
        $_SESSION['google_access_token'][] = $access_token;
        $_SESSION['google_refresh_token'][] = $refreshToken;
        return $this->response->redirect('/reviewfeeds/googlereviews');
    }

    protected function getReviewsFromToken($access_token)
    {
        $client = $this->getGoogleClient();
        $client->setAccessToken($access_token['access_token']);
        $googleBusinessService = new \Google_Service_Mybusiness($client);
        $accounts = $googleBusinessService->accounts->listAccounts();
        foreach ($accounts as $account) {
            print '<h1>Account: '.$account->name.'</h1>';
            print '<h2>Locations</h2>';
            $locations = $googleBusinessService->accounts_locations->listAccountsLocations($account->name)->getLocations();
            if(!is_array($locations)){
                $locations = [$locations];
            }
            foreach($locations as $location){
                print '<h1>'.$location->name.'</h1>';
                $reviewObject = $googleBusinessService->accounts_locations_reviews->listAccountsLocationsReviews($location->name);
                $reviewCount = $reviewObject->getTotalReviewCount();
                $reviews = $reviewObject->getReviews();
                print '<h1>'.$reviewCount.'</h1>';
            }

        }

    }*/

    /* old google code worked and saved reviews to DB now does not work
            public function googleAction(){

                $client = new \Google_Client();

                $client->setAccessType('online'); // default: offline
                $client->setApplicationName('googlemybusiness');
                $client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');
                $client->setClientSecret('WBW5XTX9AklVqSwYxM53dPI1');
                $scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
                $client->setRedirectUri('http://localhost/test');
                $client->setScopes(array(
                    'https://www.googleapis.com/auth/plus.me',
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/userinfo.profile',
                    'https://www.googleapis.com/auth/plus.business.manage',
                ));
                //$client->setDeveloperKey('INSERT HERE'); // API key
                require_once __DIR__ . "/../library/Google/mybusiness/Mybusiness.php";
                $service = new \Google_Service_Mybusiness($client);

                if (isset($_GET['logout'])) { // logout: destroy token
                    print '1';
                    unset($_SESSION['token']);
                    die('Logged out.');
                }

                if (isset($_GET['code']) && !isset($_SESSION['token'])) { // we received the positive auth callback, get the token and store it in session
                    print'2';
                    $client->authenticate($_GET['code']);
                    $_SESSION['token'] = $client->getAccessToken();
                }

                if (isset($_SESSION['token'])) { // extract token from session and configure client
                    print'3';
                    $token = $_SESSION['token'];
                    $client->setAccessToken($token);

                    $test = $service->accounts->listAccounts();
                    $test2 = $service->accounts_locations->listAccountsLocations($test[0]['name']);
                    $test3 = $service->accounts_locations_reviews->listAccountsLocationsReviews($test2[0]['name']);

                    print '<pre>';
                    //print_r($test);
                    //print_r($test2);
                    //print_r($test3);

                    print 'Done';

                    //loop through reviews and save to datatbase
                    foreach($test3 as $review){

                        print_r($review);
    //need to convert rating to an int
                        $savereview = new Review();
                        $savereview->location_id    = '61';
                        $savereview->rating_type_id = '3';
                        $savereview->external_id    = $review->reviewId;
                        $savereview->review_text    = $review->comment;
                        $savereview->time_created   = $review->createTime;
                        $savereview->rating         = $review->starRating;
                        $savereview->user_name      = $review->reviewer->displayName;

                        //    if (!$savereview->save()) {
                        //        print_r($savereview->getMessages());
                        //    } else {
                        //        print 'it worked';
                        //    }

                    }
                    print '</pre>';
                    echo "<script>window.close();</script>";
                    die;
                }

                if (!$client->getAccessToken()) { // auth call to google
                    $authUrl = $client->createAuthUrl();
                    header("Location: ".$authUrl);

                    die;
                }
                echo 'Hello, world.';
            }
    */



    public function facebookAction(){
        $fb = new Facebook([
            'app_id' => '1638499289773721',
            'app_secret' => '04dd7d5dc2697ac091e4f67022c41f66',
            'default_graph_version' => 'v2.5',
        ]);

        if(!$_GET) {
            $helper = $fb->getRedirectLoginHelper();
            $permissions = ['email', 'user_likes', 'pages_show_list', 'manage_pages']; // optional
            $loginUrl = $helper->getLoginUrl('http://localhost/reviewfeeds/facebook', $permissions);

            echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
        } else {
            $helper = $fb->getRedirectLoginHelper();

            try {
                $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            if (! isset($accessToken)) {
                if ($helper->getError()) {
                    header('HTTP/1.0 401 Unauthorized');
                    echo "Error: " . $helper->getError() . "\n";
                    echo "Error Code: " . $helper->getErrorCode() . "\n";
                    echo "Error Reason: " . $helper->getErrorReason() . "\n";
                    echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                    header('HTTP/1.0 400 Bad Request');
                    echo 'Bad request';
                }
                exit;
            }

// Logged in

// The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId('1638499289773721'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                    exit;
                }

                echo '<h3>Long-lived</h3>';
                var_dump($accessToken->getValue());
            }

            $_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
            /*
                $request = new FacebookRequest(
                    $session,
                    'GET',
                    '/{page-id}/ratings'
                );
                $response = $request->execute();
                $graphObject = $response->getGraphObject();

            */

            //get user info
            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/me?fields=id,name', $accessToken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $user = $response->getGraphUser();

            echo 'Name: ' . $user['name'];

            //get user accounts
            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/me/accounts', $accessToken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            print '<pre>';
            print_r($response);
            print '</pre>';

            $page_id = $response->getApp()->getId();

            //get page access token
            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/kellerselfdefense/?fields=access_token', $accessToken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }


            print '<pre>';
            print 'test';
            print_r($response);

            $pageAccessToken = $response->getRequest()->getAccessToken();

            print 'test2<br>';

            //get page reviews with page access token
            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $fb->get('/kellerselfdefense/ratings?fields=open_graph_story', $pageAccessToken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            print '<pre>';
            print_r($response);



        }
    }

}