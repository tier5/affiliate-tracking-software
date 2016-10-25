<?php
    require 'bootstrap.php';
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    $Start = date("Y-m-01", strtotime('now'));
    $End = date("Y-m-t", strtotime('now'));
    $sql = "SELECT agency_id FROM agency WHERE parent_id <= 100";
    $list = new \Vokuro\Models\Agency();

    $params = null;
    $agencyList = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));

    foreach($agencyList as $agency) {

      $BusinessID = $agency->agency_id;
      $dbLocations = \Vokuro\Models\Location::find("agency_id = {$BusinessID}");

      $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
      $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = " . $objBusiness->parent_id);


      foreach($dbLocations as $objLocation) {
      	 echo $objLocation->location_id;
          $dbEmployees = \Vokuro\Models\Users::getEmployeeListReport($BusinessID, $Start, $End, $objLocation->location_id, 0, 0, 1);
          $objEmail = new \Vokuro\Services\Email();
          echo count($dbEmployees);
          $objEmail->sendEmployeeReport($dbEmployees, $objBusiness);
          //print_r( $dbEmployees);
          die;
          
      }
}
