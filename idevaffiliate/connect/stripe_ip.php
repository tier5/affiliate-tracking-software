<?php
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

$ip = getenv('HTTP_CLIENT_IP') ?: getenv('HTTP_X_FORWARDED_FOR') ?:
	getenv('HTTP_X_FORWARDED') ?: getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED') ?: getenv('REMOTE_ADDR');

$str = <<< EOD
var x = document.getElementById("idev_custom_x21").value = '$ip';
EOD;

echo $str;
?>