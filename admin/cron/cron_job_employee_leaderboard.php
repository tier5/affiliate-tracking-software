<?php
    require 'bootstrap.php';
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

    $Start = date("Y-m-01", strtotime('now'));
    $End = date("Y-m-t", strtotime('now'));

    $dbBusinesses = \Vokuro\Models\Agency::find('parent_id != ' . \Vokuro\Models\Agency::AGENCY);

    $dbAllPermissions = \Vokuro\Models\UsersLocation::find();
    $tAllPermissions = [];
    foreach($dbAllPermissions as $objPermission) {
        $tAllPermissions[$objPermission->user_id][$objPermission->location_id] = 1;
    }

    $dbAllNotifications = \Vokuro\Models\LocationNotifications::find();
    $tAllNotifications = [];
    foreach($dbAllNotifications as $objNotification) {
        if($objNotification->employee_leaderboards)
            $tAllNotifications[$objNotification->user_id][$objNotification->location_id] = 1;
    }

    foreach ($dbBusinesses as $objBusiness) {
        $dbLocations = \Vokuro\Models\Location::find("agency_id = {$objBusiness->agency_id}");

        $dbAllUsers = \Vokuro\Models\Users::find("agency_id = {$objBusiness->agency_id}");
        $tRecipients = [];

        foreach($dbAllUsers as $objUser) {
            foreach($dbLocations as $objLocation) {
                if (($objUser->role == \Vokuro\Models\Users::ROLE_SUPER_ADMIN || $objUser->role == \Vokuro\Models\Users::ROLE_ADMIN) && isset($tAllNotifications[$objUser->id][$objLocation->location_id])) {
                    $tRecipients[$objLocation->location_id][] = $objUser->id;
                } else {
                    if(isset($tAllPermissions[$objUser->id][$objLocation->location_id]) && isset($tAllNotifications[$objUser->id][$objLocation->location_id]))
                        $tRecipients[$objLocation->location_id][] = $objUser->id;
                }
            }
        }

       foreach ($dbLocations as $objLocation) {
            if(isset($tRecipients[$objLocation->location_id])) {
                $dbEmployees = \Vokuro\Models\Users::getEmployeeListReport($objBusiness->agency_id, $Start, $End, $objLocation->location_id, 0, 0, 1);
                $dbRecipients = \Vokuro\Models\Users::find("id IN (" . implode(',', $tRecipients[$objLocation->location_id]) . ")");

                $objEmail = new \Vokuro\Services\Email();
                $objEmail->sendEmployeeReport($dbEmployees, $objLocation, $dbRecipients);
            }
        }
    }
