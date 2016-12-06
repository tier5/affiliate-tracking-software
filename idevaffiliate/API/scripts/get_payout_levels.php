<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

// ----------------------------------------------------------------
// We've designed this API file as simple as possible.  We didn't use any 
// complex queries and everything should be fairly self explanatory.
// Have fun customizing this API file to meet your needs.
// ----------------------------------------------------------------

// CONNECT TO THE DATABASE & MAKE SITE CONFIG SETTINGS AVAILABLE
// ----------------------------------------------------------------
require_once("../../API/config.php");


// CHECK VALID SECRET KEY IS PRESENT AND VALID
// - The variable is already sanitized.
// - The variable is already validated through _GET, or _POST.
// ------------------------------------------------------------------------------

$secret = check_type_api('secret');
$get_rows = $db->prepare("select secret from idevaff_config where secret = ? limit 1");
$get_rows->execute(array($secret));
if (is_numeric($secret) && $get_rows->rowCount()) {

$data2 = array();
$results2 = $db->query("select currency from idevaff_config");
$row2 = $results2->fetch();
$currency = $row2['currency'];

if (($ap_1 == '1') && ($ap_2 == '1')) { $added = "type != '3'"; }
if (($ap_1 == '1') && ($ap_2 != '1')) { $added = "type != '3' and type != '2'"; }
if (($ap_1 != '1') && ($ap_2 == '1')) { $added = "type != '3' and type != '1'"; }

$data1 = array();
$results = $db->query("select * from idevaff_paylevels where {$added} order by type, level");
while ($row = $results->fetch()) {
$row['currency'] = $currency;
$data1[] = $row;
}
echo json_encode($data1);

}

?>
