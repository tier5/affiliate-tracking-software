<?php namespace ReviewVelocity\ReviewProviders;
use Vokuro\Models\Location;
interface IProvider{
    public function setLocation(Location $location);
    public function getReviewsByLocation(Location $location);
    public function getLocationsByBusinessId($business_id);
    public function importReviews();
}