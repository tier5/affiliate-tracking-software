<?php
    require 'bootstrap.php';
    use Vokuro\Services\Reviews;

    // GARY_TODO:  In a rush.  I know extending the controller is terrible, but dead lines...
    class LocationImporter extends \Vokuro\Controllers\LocationController {

        function initialize() {
            // Purposefully not calling parent
            $path_to_admin = realpath(__DIR__ . '/../');
            include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
            define('APPLICATION_NAME', 'User Query - Google My Business API');
            define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');
        }

        public function LogContents($API, $LocationID, $Data) {
            $Directory = "/tmp/{$API}";
            $FilePath = "{$Directory}/{$LocationID}.log";
            @mkdir($Directory);
            file_put_contents($FilePath, $Data . "\r\n", FILE_APPEND);
        }

        function ImportGoogleMyBusinessReviews() {
            $dbLocations = \Vokuro\Models\LocationReviewSite::find("review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE . " AND json_access_token IS NOT NULL AND json_access_token != ''");
            $objReviewService = new \Vokuro\Services\Reviews();
            foreach ($dbLocations as $objLocation) {
                try {
                    $objReviewService->importGoogleMyBusinessReviews($objLocation->location_id);
                } catch (Exception $e) {
                    $this->LogContents('Google', $objLocation->location_id, $e->getMessage());
                }
            }
        }

        public function ImportFacebookReviews() {
            $dbLocations = \Vokuro\Models\LocationReviewSite::find("review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK . " AND access_token IS NOT NULL AND access_token != ''");
            $objReviewService = new \Vokuro\Services\Reviews();
            foreach($dbLocations as $objLocation) {
                try {
                    $objReviewService->importFacebook($objLocation->location_id);
                } catch (Exception $e) {
                    $this->LogContents('Facebook', $objLocation->location_id, $e->getMessage());
                }
            }
        }

        public function ImportYelpReviews() {
            $dbLocations = \Vokuro\Models\LocationReviewSite::find("review_site_id = " . \Vokuro\Models\Location::TYPE_YELP . " AND external_location_id IS NOT NULL AND external_location_id != ''");
            $objReviewService = new \Vokuro\Services\Reviews();
            foreach($dbLocations as $objLocation) {
                try {
                    $objReviewService->importYelpReviews($objLocation->location_id);
                } catch (Exception $e) {
                    $this->LogContents('Yelp', $objLocation->location_id, $e->getMessage());
                }
            }
        }
    }

    $objImporter = new LocationImporter();
    $objImporter->initialize();
    $objImporter->ImportGoogleMyBusinessReviews();
    $objImporter->ImportFacebookReviews();
    $objImporter->ImportYelpReviews();
