<?PHP

// total days in last month 
$total_days = cal_days_in_month ( CAL_GREGORIAN, date('m')-1, date('Y') );

$record_array= array();

for($d = 0; $d<$total_days;$d++)
{
	$startTime		= mktime(0, 0, 0, date('m')-1, $total_days-$d, date('Y'));    
	$endTime 		= mktime(23, 59, 59, date('m')-1, $total_days-$d, date('Y'));
		
	$new_accounts 	= $db->query("select COUNT(*) from idevaff_affiliates where signup_date BETWEEN '$startTime' and '$endTime'");
	
	if($new_accounts->rowCount() > 0)
	{
		$new_accounts = $new_accounts->fetchColumn();
	}
	else
	{
		$new_accounts	= 0;	
	}
	
	$record_array[]	= array (
						'tm'		=> $startTime * 1000,
						'account'	=> $new_accounts
					);
}

$record_array	= array_reverse($record_array);

?>
<script type="text/javascript">

$(document).ready(function(){
    
	var account = [<?php $accounts = ''; foreach($record_array as $record){ $accounts .= "[".$record['tm'].",".$record['account']."],";} echo rtrim($accounts,",");?>];
	
	
	var dash = [
		{ label: "New Affiliates", data: account, color: App.getLayoutColorCode('blue') }	
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