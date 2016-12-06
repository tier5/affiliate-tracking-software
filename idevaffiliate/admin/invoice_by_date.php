<?PHP
include_once("../API/config.php");
include_once("includes/session.check.php");

function idev_strtotime($date, $end = false) {
    list($month, $day, $year) = explode("-",$date);
    if($end == false) {
        return(mktime( 0,0,0,$month, $day, $year));
    } else {
        return(mktime( 23,59,59,$month, $day, $year));
            }
}

$start_date = $_REQUEST['start'];
$end_date = $_REQUEST['end'];

$start_date1 = date('m-d-Y', $start_date);
$end_date1 = date('m-d-Y', $end_date);

$alldata=$db->prepare("select * from idevaff_affiliates where id = ?");
$alldata->execute(array($_REQUEST['id']));
$indv_data=$alldata->fetch();
$uname=$indv_data['username'];
$payto=$indv_data['payable'];
$ufname=$indv_data['f_name'];
$ulname=$indv_data['l_name'];
$uemail=$indv_data['email'];
$ad1=$indv_data['address_1'];
$ad2=$indv_data['address_2'];
$c=$indv_data['city'];
$s=$indv_data['state'];
$z=$indv_data['zip'];
$coun=$indv_data['country'];
$phone=$indv_data['phone'];
$fax=$indv_data['fax'];
$url=$indv_data['url'];
$pp=$indv_data['pp'];
$hits=$indv_data['hits_in'];
$company=$indv_data['company'];
$app=$indv_data['approved'];

$get_country_name = $db->prepare("select country_name from idevaff_countries where country_code = ?");
$get_country_name->execute(array($coun));
$get_country_data = $get_country_name->fetch();
$coun = $get_country_data['country_name'];

$get_tax = $db->prepare("SELECT AES_DECRYPT(tax_id_ssn, '" . AUTH_KEY . "') AS decrypted FROM idevaff_affiliates where id = ?");
$get_tax->execute(array($_REQUEST['id']));
$get_tax = $get_tax->fetch();
$utax = $get_tax['decrypted'];

$get_data = $db->query("select * from idevaff_invoice");
$get_data = $get_data->fetch();
$incom = $get_data['company'];
$inad1 = $get_data['ad1'];
$inad2 = $get_data['ad2'];
$incit = $get_data['city'];
$insta = $get_data['state'];
$inzip = $get_data['zip'];
$country_select = $get_data['country'];
$innot = $get_data['note'];

$get_country_name = $db->prepare("select country_name from idevaff_countries where country_code = ?");
$get_country_name->execute(array($country_select));
$get_country_data = $get_country_name->fetch();
$country_select = $get_country_data['country_name'];

$earnings2 = $db->prepare("select SUM(payment) AS total from idevaff_sales where id = ? and code >= ? and code <= ? and approved = '1'"); 
$earnings2->execute(array($_REQUEST['id'],$start_date,$end_date));
$row2 = $earnings2->fetch();
$pexact = $row2['total'];

$debittotal = $db->prepare("select SUM(amount) AS totaldebs from idevaff_debit where aff_id = ?"); 
$debittotal->execute(array($_REQUEST['id']));
$debit_total =  $debittotal->fetch();
$pexacttotaldebs = $debit_total['totaldebs'];
$pexacttotaldebsd = $pexacttotaldebs;

$amount_to_pay_grand_total = $pexact - $pexacttotaldebsd;

$check_for_vat = $db->prepare("select * from idevaff_vat where country = ? and admin_invoice = '1'");
$check_for_vat->execute(array($indv_data['country']));
if ($check_for_vat->rowCount()) {
$get_vals = $check_for_vat->fetch();
$vat_value = $get_vals['rate'];
$vat_value_extended = "1" . $vat_value;

$check_vat_valid = $db->prepare("SELECT COUNT(*) FROM idevaff_affiliates where id = ? and vat_override = '1'");
$check_vat_valid->execute(array($_REQUEST['id']));
if (!$check_vat_valid->fetchColumn()) {
$vat_amount = ($amount_to_pay_grand_total / $vat_value_extended) * $vat_value;
} }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP echo $char_set; ?>" />
<title>iDevAffiliate Report - Payment Invoice</title>
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="templates/style_invoice.css" type="text/css">
</head>
<body>
<div class="main_container">
	<div class="row">
		<div class="row-half fleft">
			<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th scope="col">Affiliate Information</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?PHP if ($company) { ; ?>
							<?PHP echo $company; ?><br />
							<?PHP } ; ?>
							<?PHP echo $ufname; ?> <?PHP echo $ulname; ?><br />
							<?PHP echo $ad1; ?><br />
							<?PHP if ($ad2) { ; ?>
							<?PHP echo $ad2; ?><br />
							<?PHP } ; ?>
							<?PHP echo $c; ?>, <?PHP echo $s; ?> <?PHP echo $z; ?><br />
							Country: <?PHP echo $coun; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="row-half fright">
			<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th scope="col"><?PHP echo $sitename; ?> Information</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?PHP if ($incom) { ; ?>
							<?PHP echo $incom; ?><br />
							<?PHP } ; ?>
							<?PHP echo $inad1; ?><br />
							<?PHP if ($inad2) { ; ?>
							<?PHP echo $inad2; ?><br />
							<?PHP } ; ?>
							<?PHP echo $incit; ?>, <?PHP echo $insta; ?> <?PHP echo $inzip; ?><br />
							Country: <?PHP echo $country_select; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th colspan="3" scope="col">Account Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td width="16%" class="grey">Affiliate ID</td>
					<td width="34%"><?PHP echo html_output($_REQUEST['id']); ?></td>
					<td width="50%" rowspan="5" style="text-align:center;vertical-align:middle;"><a href="#" onclick="window.print();return false;"><img border="0" src="../images/print_invoice.gif" width="31" height="31"></a><br />
						<a href="#" onclick="window.print();return false;">Print Invoice</a></td>
				</tr>
				<tr>
					<td width="16%" class="grey">Username</td>
					<td width="34%"><?PHP echo $uname; ?></td>
				</tr>
				<tr>
					<td width="16%" class="grey">Phone Number</td>
					<td width="34%"><?PHP echo $phone; ?></td>
				</tr>
				<tr>
					<td width="16%" class="grey">Payment Preference</td>
					<td width="34%"><?PHP if ($pp == 1) { print "PayPal</font>"; } elseif ($pp == 0) { print "Paper Check"; } ?></td>
				</tr>
				<tr>
					<td width="16%" class="grey">Tax ID, SSN or VAT</td>
					<td width="34%"><?PHP if ($utax) { print $utax; } else { print "N/A"; } ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="row">
		<?PHP
$getcustomrows = $db->query("select id, title from idevaff_form_fields_custom where display_invoice = '1' order by sort");
if ($getcustomrows->rowCount()) {
?>
		<?PHP
print "<table class=\"table_invoice\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><thead><th colspan=\"2\">Custom Fields</th>";
//while ($qry = $getcustomrows->fetch()) {
foreach ($getcustomrows->fetchAll() as $qry) {
$group_id = $qry['id'];
$custom_title = $qry['title'];
$getvars = $db->prepare("select custom_value from idevaff_form_custom_data where custom_id = ? and affid = ?");
$getvars->execute(array($group_id,$_REQUEST['id']));
$getvars = $getvars->fetch();
$custom_value = $getvars['custom_value'];
if ($custom_value == null) { $custom_value = "N/A"; }
echo "</thead><tr><tbody>";
echo "<td class='grey' width='40%'>" . $custom_title . "\n</td>";
echo "<td width='60%'>" . $custom_value . "\n</td>";
echo "</tbody></tr>";
}
echo "</table>";
}
?>
	</div>
	<div class="row">
		<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th colspan="2" scope="col">Payment Information</th>
				</tr>
			</thead>
			<tbody>
			<td width="25%">Payment Date</td>
				<td width="75%"><?PHP $pdate = time(); echo date('m-d-Y', $pdate); ?></td>
			</tr>
			<tr>
				<td width="25%"><?PHP if ($pexacttotaldebs <= 0) { ?><font color="#CC0000"><?PHP } ?>Payment Amount<?PHP if ($pexacttotaldebs <= 0) { ?></font><?PHP } ?></td>
				<td width="75%"><?PHP if ($pexacttotaldebs <= 0) { ?><font color="#CC0000"><?PHP } ?><?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo number_format($pexact,$decimal_symbols); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?><?PHP if ($pexacttotaldebs <= 0) { ?><?PHP } ?></td>
			</tr>
<?PHP if ($pexacttotaldebs > 0) { ?>
			<tr>
				<td width="25%">Minus Debit Amount</td>
				<td width="75%"><?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo number_format($pexacttotaldebsd,$decimal_symbols); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
			</tr>
			<tr>
				<td width="25%"><font color="#CC0000">Revised Payment Amount</font></td>
				<td width="75%"><font color="#CC0000">
					<?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo number_format($amount_to_pay_grand_total,$decimal_symbols); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?>
					</font></td>
			</tr>
<?PHP } ?>
			<?PHP if (isset($vat_amount)) { ?>
				<tr>
					<td width="25%">VAT Amount</td>
					<td width="75%"><?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo number_format($vat_amount, $decimal_symbols); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
				</tr>
				<?PHP } ?>
				</tbody>
			
		</table>
	</div>
	
<?PHP if ($pexacttotaldebs > 0) { ?>
	<div class="row">
	
		<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th width="25%">Debit Date</th>
					<th width="25%">Debit Amount</th>
					<th width="50%">Debit Reason</th>
				</tr>
			</thead>
			<tbody>
<?PHP
$acct = $db->prepare("select * from idevaff_debit where aff_id = ? ORDER BY id"); 
$acct->execute(array($_REQUEST['id']));
//while ($qry = $acct->fetch()) {
foreach ($acct->fetchAll() as $qry) {
$indidate_debit=date('m-d-Y', $qry['code']);
$indiamt_debit=$qry['amount'];
$indiamtd_debit = (number_format($indiamt_debit,$decimal_symbols));
$debitreason = ${"debit_reason_" . $qry['reason']};

?>
				<tr>
					<td width="25%"><?PHP echo $indidate_debit; ?></td>
					<td width="25%"><?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo $indiamtd_debit; if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
					<td width="50%"><?PHP echo $debitreason; ?></td>
				</tr>
				<?PHP } ?>
			</tbody>
		</table>
	</div>
<?PHP } ?>

	<div class="row">
		<table class="table_invoice" border="0" cellpadding="0" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th width="25%">Commission Date</th>
					<th width="25%">Commission Amount</th>
					<th width="25%">Order Number</th>
					<th width="25%">Commission Type</th>
				</tr>
			</thead>
			<tbody>
<?PHP
$acct = $db->prepare("select * from idevaff_sales where id = ? and code >= ? and code <= ? and approved = '1' ORDER BY record DESC"); 
$acct->execute(array($_REQUEST['id'],$start_date,$end_date));
//while ($qry = $acct->fetch()) {
foreach ($acct->fetchAll() as $qry) {
$indidate=date('m-d-Y', $qry['code']);
$indiamt=$qry['payment'];
$indiamtd = (number_format($indiamt,$decimal_symbols));
$stat1=$qry['top_tier_tag'];
$stat2=$qry['bonus'];
$stat3=$qry['recurring'];
$stat4=$qry['override'];
$ordnum=$qry['tracking'];

if ($stat1 == 1) { $put = "Tier Commission"; }
elseif ($stat4 == 1) { $put = "Override Commission"; }
elseif ($stat2 == 1) { $put = "Bonus Commission"; }
elseif ($stat3 > 0) { $put = "Recurring Commission"; }
else { $put = "Standard Commission"; }

if ($ordnum == '') { $ordnum = "N/A"; }

//$earnings2 = $db->prepare("select SUM(payment) AS total from idevaff_sales where id = ? and code >= ? and code <= ? and approved = '1'"); 
//$earnings2->execute(array($_REQUEST['id'],$start_date,$end_date));
//$row2 = $earnings2->fetch();
//$pexact = $row2['total'];
//$pexactd = (number_format($pexact,$decimal_symbols));
?>
				<tr>
					<td width="25%"><?PHP echo $indidate; ?></td>
					<td width="25%"><?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo $indiamtd; if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?></td>
					<td width="25%"><?PHP echo $ordnum; ?></td>
					<td width="25%"><?PHP echo $put; ?></td>
				</tr>
				<?PHP } ?>
			</tbody>
		</table>
	</div>
	<div class="row">
		<div class="row-half fleft">
			<div class="administator">
				<h4>Administrator Note</h4>
				<p><?PHP echo $innot; ?></p>
			</div>
		</div>
		<div class="fright">
			<div class="commission">
				<h4>Total Payment Amount</h4>
				<h1>
					<?PHP if($cur_sym_location == 1) { echo $cur_sym; } echo number_format($amount_to_pay_grand_total,$decimal_symbols); if($cur_sym_location == 2) { echo " " . $cur_sym . " "; }echo " $currency"; ?>
				</h1>
			</div>
		</div>
	</div>
</div>
</body>
</html>