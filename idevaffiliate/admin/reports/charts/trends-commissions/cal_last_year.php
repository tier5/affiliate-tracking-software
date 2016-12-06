<?php
$total_month = 12;

$record_array= array();

for($m=0; $m<$total_month;$m++)
{
	$startTime	= mktime(0, 0, 0, $total_month-$m , 1, date('Y')-1);    
	$endTime 	= mktime(23, 59, 59, $total_month-$m, cal_days_in_month ( CAL_GREGORIAN, $total_month-$m, date('Y')-1 ), date('Y')-1);
	
	$c1 = $db->query("select COUNT(*) from idevaff_sales where code BETWEEN '$startTime' and '$endTime' and bonus = '0'");
	if($c1->rowCount() > 0)
	{
		$c1 = $c1->fetchColumn();
	}
	else
	{
		$c1 = 0;
	}
	
	$c2 = $db->query("select COUNT(*) from idevaff_archive where code BETWEEN '$startTime' and '$endTime' and bonus = '0'");
	if($c2->rowCount() > 0)
	{
		$c2 = $c2->fetchColumn();
	}
	else
	{
		$c2 = 0;
	}
	
	$commission = $c1 + $c2;
	
	$record_array[]	= array (
						'tm'		=> $startTime * 1000,
						'commission'	=> $commission
					);
}

$record_array	= array_reverse($record_array);

?>
<script type="text/javascript">

$(document).ready(function(){
    
	var comm = [<?php $commissions = ''; foreach($record_array as $record){ $commissions .= "[".$record['tm'].",".$record['commission']."],";} echo rtrim($commissions,",");?>];
	
	var dash = [
		{ label: "New Commissions", data: comm, color: App.getLayoutColorCode('blue') }	
	];
	$.plot("#charts_trend", dash, $.extend(true, {}, Plugins.getFlotDefaults(), {
		xaxis: {
			tickLength: 0,
			mode: "time",
			tickSize: [1, "month"],
			timeformat: "%b"
		},
		series: {
			lines: {
				fill: true,
				lineWidth: 1.5
			},
			points: {
				show: true,
				radius: 2.5,
				lineWidth: 1.1
			},
			grow: { active: true, growings:[ { stepMode: "maximum" } ] }
		},
		grid: {
			hoverable: true,
			clickable: true
		},
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		}
	}));
});
</script>