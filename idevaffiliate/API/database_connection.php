<?PHP
#############################################################
## iDevAffiliate Version 9
## Copyright - iDevDirect LLC
## Website: http://www.idevdirect.com/
## Support: http://www.idevsupport.com/
#############################################################

if (is_file('templates/bootstrap/css/bootstrap.css')) {
$css_location = "templates/bootstrap/css/bootstrap.css";
} else { $css_location = "admin/templates/bootstrap/css/bootstrap.css"; }

$db_connection_failure = '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>iDevAffiliate Error</title>
<link href="' . $css_location . '" rel="stylesheet">
<link href="' . $css_location . '" rel="stylesheet">
</head>
<body>
<div style="padding:50px; width:800px; margin-left:auto; margin-right:auto;">
<div class="panel panel-primary">
<div class="panel-heading">
<h3 class="panel-title"><strong>Database Connection Error</strong><span class="pull-right">iDevAffiliate Cannot Run</span></h3>
</div>
<div class="panel-body">
<p>We could not connect to your database: <font color="#CC0000">' . $dbname . '</font></b></p>
<p>Check your database connectivity settings in your <strong>API/database.php</strong> file.</p>
</div>
</div>
</div>
</body>
</html>';

$installcheck = 1;

try {
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
	$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET @@SESSION.sql_mode='TRADITIONAL,NO_AUTO_VALUE_ON_ZERO'");
}
catch(PDOException $e) {
    die ($db_connection_failure);
}

?>