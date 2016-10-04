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
            $dbLocations = \Vokuro\Models\LocationReviewSite::find("review_site_id = 3 AND json_access_token IS NOT NULL");
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
            $LocationID = 64;
            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = 1");
            $objLocation = \Vokuro\Models\Location::findFirst("location_id = {$LocationID}");
            $FoundAgency = [];

            $objReviewService = new \Vokuro\Services\Reviews();
            $objReviewService->importFacebook($objLocationReviewSite, $objLocation, $FoundAgency);
        }
    }

    $objImporter = new LocationImporter();
    $objImporter->initialize();
    $objImporter->ImportGoogleMyBusinessReviews();
    //$objImporter->ImportFacebookReviews();