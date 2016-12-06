<?php
// total days in current month 
$total_days = date('t');
$current_day= date('d');

$total_days = $total_days - ($total_days - $current_day);

$record_array= array();

for($d = 0; $d<$total_days;$d++)
{
	$startTime		= mktime(0, 0, 0, date('m'), $total_days-$d, date('Y'));    
	$endTime 		= mktime(23, 59, 59, date('m'), $total_days-$d, date('Y'));
		
	$new_hits = $db->query("select COUNT(distinct ip) from idevaff_iptracking where stamp  BETWEEN '$startTime' AND '$endTime'");
	if($new_hits->rowCount() > 0)
	{
		$new_hits = $new_hits->fetchColumn();
	}
	else
	{
		$new_hits = 0;
	}
	
	$record_array[]	= array (
						'tm'		=> $startTime * 1000,
						'newhit'	=> $new_hits
					);
}

$record_array	= array_reverse($record_array);

?>
<script type="text/javascript">

$(document).ready(function(){
    
	var hits = [<?php $hits = ''; foreach($record_array as $record){ $hits .= "[".$record['tm'].",".$record['newhit']."],";} echo rtrim($hits,",");?>];
	
	
	var dash = [
		{ label: "Unique Hits", data: hits, color: App.getLayoutColorCode('blue') }	
	];
	
	$.plot("#charts_trend", dash, $.extend(true, {}, Plugins.getFlotDefaults(), {
		xaxis: {
			tickLength: 0,
			mode: "time",
			tickSize: [1, "day"],
			timeformat: "%b %d"
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