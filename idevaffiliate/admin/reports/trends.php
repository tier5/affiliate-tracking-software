<?PHP
// FILE INCLUDE VALIDATION
if (!defined('IDEV_REPORT')) { die('Unauthorized Access'); }
// -------------------------------------------------------------------------------------------------
$accounts_chart_valid = false;
$hits_chart_valid = false;
$commission_chart_valid = false;
$revenue_chart_valid = false;
?>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-bar-chart"></i> Trends Report</h4></div>
<div class="widget-content">

<form class="form-horizontal row-border" method="post" action="reports.php">

<div class="form-group">
<label class="col-md-3 control-label">Choose A Timeline</label>
<div class="col-md-4"><select name="timeline" class="form-control">
<option value="1"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 1)) { echo " selected"; } ?>>Past 30 Days</option>
<option value="2"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 2)) { echo " selected"; } ?>>Past 90 Days</option>
<option value="3"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 3)) { echo " selected"; } ?>>Past 6 Months</option>
<option value="4"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 4)) { echo " selected"; } ?>>Past 1 Year</option>
<option value="5"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 5)) { echo " selected"; } ?>>Last Calendar Month</option>
<option value="6"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 6)) { echo " selected"; } ?>>Current Calendar Month</option>
<option value="7"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 7)) { echo " selected"; } ?>>Last Calendar Year</option>
<option value="8"<?PHP if ((isset($_POST['timeline'])) && ($_POST['timeline'] == 8)) { echo " selected"; } ?>>Current Calendar Year</option>
</select></div>
</div>

<div class="form-group">
<label class="col-md-3 control-label">Display Chart</label>
<div class="col-md-4"><select name="chart_type" class="form-control">
<option value="1"<?PHP if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 1)) { echo " selected"; } ?>>New Affiliates</option>
<option value="2"<?PHP if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 2)) { echo " selected"; } ?>>New Commissions</option>
<option value="3"<?PHP if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 3)) { echo " selected"; } ?>>Unique Hits</option>
<option value="4"<?PHP if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 4)) { echo " selected"; } ?>>Commission Revenue</option>
</select></div>
</div>

<div class="form-actions">
<input type="submit" value="Build Report" class="btn btn-primary">
</div>
<input type="hidden" name="report" value="7">
</form>
</div>
</div>

<?PHP

if(isset($_POST['timeline'])) {

	if ($_POST['timeline'] == 1) { // Last 30 Days
		$printable_timeline = "Past 30 Days";
		$start_date = strtotime("midnight -30 days");
		$end_date = time();
	} elseif ($_POST['timeline'] == 2) { // Last 90 Days
		$printable_timeline = "Past 90 Days";
		$start_date = strtotime("midnight -90 days");
		$end_date = time();
	} elseif ($_POST['timeline'] == 3) { // Last 6 Months
		$printable_timeline = "Past 6 Months";
		$start_date = strtotime("midnight -6 months");
		$end_date = time();
	} elseif ($_POST['timeline'] == 4) { // Last 1 Year
		$printable_timeline = "Past 1 Year";
		$start_date = strtotime("midnight -1 year");
		$end_date = time();
	} elseif ($_POST['timeline'] == 5) { // Last Calendar Month
		$printable_timeline = "Last Calendar Month";
		$start_date = mktime(0, 0, 0, date('m')-1, 1, date('Y'));     
		$end_date   = mktime(23, 59, 59, date('m'), 0, date('Y'));
	} elseif ($_POST['timeline'] == 6) { // Current Calendar Month
		$printable_timeline = "Current Calendar Month";
		$start_date = mktime(0, 0, 0, date('m'), 1, date('Y'));     
		$end_date = time();
	} elseif ($_POST['timeline'] == 7) { // Last Calendar Year
		$printable_timeline = "Last Calendar Year";
		$start_date = mktime(0, 0, 0, 1, 1, date('Y')-1);     
		$end_date = mktime(23, 59, 59, 12, 31, date('Y')-1);
	} elseif ($_POST['timeline'] == 8) { // Current Calendar Year
		$printable_timeline = "Current Calendar Year";
		$start_date = mktime(0, 0, 0, 1, 1, date('Y'));     
		$end_date = time(); 
	}
		
	$start_date1 = date('m-d-Y', $start_date);
	$end_date1 = date('m-d-Y', $end_date);

} else {
	$printable_timeline = "Past 30 Days";
	$start_date = strtotime("midnight -30 days");
	$end_date = time();

	$start_date1 = date('m-d-Y', $start_date);
	$end_date1 = date('m-d-Y', $end_date);
}

// COMMISSION COUNT
$c1 = $db->prepare("select COUNT(*) from idevaff_sales where code BETWEEN ? and ? and bonus = '0'");
$c1->execute(array($start_date,$end_date));
$c1 = $c1->fetchColumn();
$c2 = $db->prepare("select COUNT(*) from idevaff_archive where code BETWEEN ? and ? and bonus = '0'");
$c2->execute(array($start_date,$end_date));
$c2 = $c2->fetchColumn();
$commission_today = $c1 + $c2;
if ($commission_today > 0) { $commission_chart_valid = true; }

// ACCOUNT COUNT
$new_accounts = $db->prepare("select COUNT(*) from idevaff_affiliates where signup_date BETWEEN ? and ?");
$new_accounts->execute(array($start_date,$end_date));
$new_accounts = $new_accounts->fetchColumn();
if ($new_accounts > 0) { $accounts_chart_valid = true; }

// HIT COUNT
$new_hits = $db->prepare("select COUNT(distinct ip) from idevaff_iptracking where stamp BETWEEN ? and ?");
$new_hits->execute(array($start_date,$end_date));
$new_hits = $new_hits->fetchColumn();
if ($new_hits > 0) { $hits_chart_valid = true; }

// COMMISSION AMOUNT
$table_paid = $db->prepare("select sum(payment) as earnings_paid from idevaff_archive where code BETWEEN ? and ? and bonus = '0'");
$table_paid->execute(array($start_date,$end_date));
$table_paid = $table_paid->fetch();
$table_paid_total = $table_paid['earnings_paid'];

$table_total = $db->prepare("select sum(payment) as earnings_total from idevaff_sales where code BETWEEN ? and ? and bonus = '0'");
$table_total->execute(array($start_date,$end_date));
$table_total = $table_total->fetch();
$table_total = $table_total['earnings_total'];

$table_grand = number_format($table_paid_total + $table_total,$decimal_symbols);
if($cur_sym_location == 1) { $table_grand = $cur_sym . $table_grand; }
if($cur_sym_location == 2) { $table_grand = $table_grand . " " . $cur_sym; }
$table_grand = $table_grand . " " . $currency;

if ($table_paid_total > 0) { $revenue_chart_valid = true; }
if ($table_total > 0) { $revenue_chart_valid = true; }
?>

<?PHP
if ((!isset($_POST['chart_type'])) || ($_POST['chart_type'] == 1)) {
$chart_heading = "New Affiliates";
} elseif ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 2)) {
$chart_heading = "New Commissions";
} elseif ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 3)) {
$chart_heading = "Unique Hits";
} elseif ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 4)) {
$chart_heading = "Commission Revenue";
}
?>

<div class="widget box">
<div class="widget-header">
<h4><i class="icon-bar-chart"></i> <?PHP // echo $chart_heading; ?> <!--Chart > -->Report Results: <?PHP echo "<font color=\"#CC0000\">" . $start_date1 . "</font> to <font color=\"#CC0000\">" . $end_date1 . "</font>"; ?></h4>
<span class="pull-right">
<form method="POST" action="<?PHP echo html_output($_SERVER['PHP_SELF']); ?>" target="_blank" style="display:inline-block;">
<input type="hidden" name="export" value="1">
<input type="hidden" name="wiz_data" value="11">
<input type="hidden" name="wiz_type" value="1">
<input type="hidden" name="printable_timeline" value="<?PHP echo $printable_timeline; ?>">
<input type="hidden" name="start_date" value="<?PHP echo $start_date; ?>">
<input type="hidden" name="end_date" value="<?PHP echo $end_date; ?>">
<button type="submit" class="btn btn-inverse btn-sm"><i class="icon-table"></i> Export To Excel</button></form>
 <form method="POST" action="report.php" target="_blank" style="display:inline-block;">
<input type="hidden" name="report_data" value="17">
<input type="hidden" name="printable_timeline" value="<?PHP echo $printable_timeline; ?>">
<input type="hidden" name="start_date" value="<?PHP echo $start_date; ?>">
<input type="hidden" name="end_date" value="<?PHP echo $end_date; ?>">
<button type="submit" class="btn btn-default btn-sm"><i class="icon-print"></i> Printable Version</button></form>
</span>
</div>

<div class="widget-content">


<div class="widget-content">
<ul class="stats">
<li>
<strong><?PHP echo number_format($new_accounts); ?></strong>
<small>New Affiliates</small>
</li>
<li>
<strong><?PHP echo number_format($new_hits); ?></strong>
<small>Unique Hits</small>
</li>
<li>
<strong><?PHP echo number_format($commission_today); ?></strong>
<small>New Commissions</small>
</li>
<li>
<strong><?PHP echo $table_grand; ?></strong>
<small>Commission Revenue</small>
</li>
</ul>
</div>

<div class="divider"></div>

<?PHP

// NEW AFFILIATES CHART
if ((!isset($_POST['chart_type'])) || ($_POST['chart_type'] == 1)) {
	if ($accounts_chart_valid == true) {
		$valid_chart_data = true;
		echo "<div id=\"charts_trend\" class=\"chart\"></div>";
		include ("charts/chart_trends_new_affiliates.php");
	} 
}

// NEW COMMISSONS CHART
if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 2)) {
	if ($commission_chart_valid == true) {
		$valid_chart_data = true;
		echo "<div id=\"charts_trend\" class=\"chart\"></div>";
		include ("charts/chart_trends_new_commissions.php");
	}
}

// UNIQUE HITS CHART
if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 3)) {
	if ($hits_chart_valid == true) {
		$valid_chart_data = true;
		echo "<div id=\"charts_trend\" class=\"chart\"></div>"; 
		include ("charts/chart_trends_unique_hits.php");
	} 
}

// REVENUE CHART
if ((isset($_POST['chart_type'])) && ($_POST['chart_type'] == 4)) {
	if ($revenue_chart_valid == true) {
		$valid_chart_data = true;
		echo "<div id=\"charts_trend\" class=\"chart\"></div>";
		include ("charts/chart_trends_revenue.php");
	} 
}

if (!isset($valid_chart_data)) {
echo "<div class=\"alert alert-danger\" style=\"text-align:center;\"><h4>No Chart Data To Display</h4>A chart will be displayed only if there is data for the chart.</div>";
}

?>

</div>

</div>

