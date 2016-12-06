<?PHP
// FILE INCLUDE VALIDATION
if (!defined('IDEV_REPORT')) { die('Unauthorized Access'); }
// -------------------------------------------------------------------------------------------------

function idev_strtotime($date, $end = false) {
list($month, $day, $year) = explode("-",$date);
if($end == false) {
return(mktime( 0,0,0,$month, $day, $year));
} else {
return(mktime( 23,59,59,$month, $day, $year));
} }

$show_chart_valid = false;
?>

<div class="widget box">
<div class="widget-header"><h4><i class="icon-bar-chart"></i> Daily Report</h4></div>
<div class="widget-content">

<form class="form-horizontal row-border" method="post" action="reports.php">

<div class="form-group">
<label class="col-md-3 control-label">Select A Day</label>
<div class="col-md-9"><select id="startmonthdropdown" name="startmonthdropdown" class="form-control input-width-small" style="display:inline-block;"></select> 
<select id="startdaydropdown" name="startdaydropdown" class="form-control input-width-small" style="display:inline-block;"></select> 
<select id="startyeardropdown" name="startyeardropdown" class="form-control input-width-small" style="display:inline-block;"></select></div>
</div>

<div class="form-actions">
<input type="submit" value="Build Report" class="btn btn-primary">
</div>
<input type="hidden" name="report" value="6">
</form>
</div>
</div>



<?PHP

if(isset($_POST['startdaydropdown'])) {
	$start_str = $_POST['startmonthdropdown'] . "-" .  $_POST['startdaydropdown'] . "-" . $_POST['startyeardropdown'];
	$start_date = idev_strtotime($start_str);
	$end_date = idev_strtotime($start_str, true);
	$date_range = " and stamp >= '$start_date' and stamp <= '$end_date'";
	$start_date1 = date('m-d-Y', $start_date);
	$end_date1 = date('m-d-Y', $end_date);
	$display_date = $start_date1 . " to " . $end_date1;
	$showing_date = date('m-d-Y', $start_date);
} else {
	$start_date = idev_strtotime(date('m-d-Y'));    
	$end_date = idev_strtotime(date('m-d-Y'), true);    
	$showing_date = date('m-d-Y');
}

// COMMISSION COUNT
$c1 = $db->prepare("SELECT COUNT(*) FROM idevaff_sales WHERE bonus = '0' AND code BETWEEN ? AND ? ");
$c1->execute(array($start_date,$end_date));
$c1 = $c1->fetchColumn();

$c2 = $db->prepare("SELECT COUNT(*) FROM idevaff_archive WHERE bonus = '0' AND code BETWEEN ? and ? ");
$c2->execute(array($start_date,$end_date));
$c2 = $c2->fetchColumn();
$commission_today = $c1 + $c2;
if ($commission_today > 0) { $show_chart_valid = true; }

// ACCOUNT COUNT
$new_accounts = $db->prepare("select COUNT(*) from idevaff_affiliates where signup_date BETWEEN ? and ?");
$new_accounts->execute(array($start_date,$end_date));
$new_accounts = $new_accounts->fetchColumn();
if ($new_accounts > 0) { $show_chart_valid = true; }

// HIT COUNT
$new_hits = $db->prepare("select COUNT(*) from idevaff_iptracking where stamp BETWEEN ? and ?");
$new_hits->execute(array($start_date,$end_date));
$new_hits = $new_hits->fetchColumn();
if ($new_hits > 0) { $show_chart_valid = true; }

// COMMISSION AMOUNT
$table_paid = $db->prepare("select sum(payment) as earnings_paid from idevaff_archive where bonus = '0' AND code BETWEEN ? and ?");
$table_paid->execute(array($start_date,$end_date));
$table_paid = $table_paid->fetch();
$table_paid_total = $table_paid['earnings_paid'];

$table_total = $db->prepare("select sum(payment) as earnings_total from idevaff_sales where bonus = '0' AND code BETWEEN ? and ?");
$table_total->execute(array($start_date,$end_date));
$table_total = $table_total->fetch();
$table_total = $table_total['earnings_total'];

$table_grand = number_format($table_paid_total + $table_total,$decimal_symbols);
if($cur_sym_location == 1) { $table_grand = $cur_sym . $table_grand; }
if($cur_sym_location == 2) { $table_grand = $table_grand . " " . $cur_sym; }
$table_grand = $table_grand . " " . $currency;

if ($table_paid_total > 0) { $show_chart_valid = true; }
if ($table_total > 0) { $show_chart_valid = true; }
?>

<div class="widget box">
<div class="widget-header">
<h4><i class="icon-bar-chart"></i> Report Results for <font color="#CC0000"><?PHP echo $showing_date; ?></font></h4>
<span class="pull-right">
<form method="POST" action="<?PHP echo html_output($_SERVER['PHP_SELF']); ?>" target="_blank" style="display:inline-block;">
<input type="hidden" name="export" value="1">
<input type="hidden" name="wiz_data" value="10">
<input type="hidden" name="wiz_type" value="1">
<input type="hidden" name="start_date" value="<?PHP echo $start_date; ?>">
<input type="hidden" name="end_date" value="<?PHP echo $end_date; ?>">
<button type="submit" class="btn btn-inverse btn-sm"><i class="icon-table"></i> Export To Excel</button></form>
 <form method="POST" action="report.php" target="_blank" style="display:inline-block;">
<input type="hidden" name="report_data" value="16">
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

// DISPLAY CHART
if ($show_chart_valid == true) {
	$valid_chart_data = true;
	include ("charts/chart_daily.php");
	?>
    <div id="daily_report" class="chart"></div>
    <script type="text/javascript">
	$(document).ready(function(){
	
		var data = [["&nbsp;",0],["New Affiliates", <?php echo $new_accounts?>], ["Unique Hits", <?php echo $new_hits;?>],["New Commissions",<?php echo $commission_today?>], [" ",0]];
		$.plot("#daily_report", [ data ], {
			series: {
				bars: {
					show: true,
					barWidth: 0.3,
					lineWidth:0,
					align:'center',
				},
				grow: { active: true, growings:[ { stepMode: "maximum" } ] }
			},
			xaxis: {
				mode: "categories",
				tickLength: 0
			},grid: {
				borderColor: "#DDDDDD", 
				borderWidth: 1
			}
		});	
	});
    </script>
    <?php
}

if (!isset($valid_chart_data)) {
	echo "<div class=\"alert alert-danger\" style=\"text-align:center;\"><h4>No Chart Data To Display</h4>A chart will be displayed only if there is data for the chart.</div>";
}

?>

</div>
</div>

<script type="text/javascript">
window.onload=function(){
    populatedropdown("startdaydropdown", "startmonthdropdown", "startyeardropdown")
<?PHP
if(isset($_POST['startdaydropdown'])) {
    echo("setdropdown('startdaydropdown', " . $_POST['startdaydropdown'] . ")\n"); }
if(isset($_POST['startmonthdropdown'])) {
    echo("setdropdown('startmonthdropdown', " . $_POST['startmonthdropdown'] . ")\n"); }
if(isset($_POST['startyeardropdown'])) {
    echo("setdropdown('startyeardropdown', " . $_POST['startyeardropdown'] . ")\n"); }
?>
}
</script>
