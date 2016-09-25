<?php
    require 'bootstrap.php';

    $Start = date("Y-m-01", strtotime('now'));
    $End = date("Y-m-t", strtotime('now'));
    $BusinessID = 163;
    $dbLocations = \Vokuro\Models\Location::find("agency_id = {$BusinessID}");

    $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
    $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = " . $objBusiness->parent_id);

    foreach($dbLocations as $objLocation) {
        $dbEmployees = \Vokuro\Models\Users::getEmployeeListReport($BusinessID, $Start, $End, $objLocation->location_id, 0, 0, 1);
        $objEmail = new \Vokuro\Services\Email();
        $objEmail->sendEmployeeReport($dbEmployees, $objAgency, $objBusiness);
        die();
    }

