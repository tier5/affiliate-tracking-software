<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// CONNECT TO THE DATABASE @ MAKE SITE CONFIG SETTINGS AVAILABLE
// ----------------------------------------------------------------
include_once("../../API/config.php");

// CHECK VALID SECRET KEY IS PRESENT AND VALID
// - The variable is already sanitized.
// - The variable is already validated through global $$, _GET, or _POST.
// ------------------------------------------------------------------------------

$secret = check_type_api('secret');
$get_rows = $db->prepare("select secret from idevaff_config where secret = ? limit 1");
$get_rows->execute(array($secret));
if (is_numeric($secret) && $get_rows->rowCount()) {

// Your code goes here.

}

?>
