<?PHP

// FILE INCLUDE VALIDATION
if (!defined('IDEV_EMAIL')) { die('Unauthorized Access'); }
// -------------------------------------------------------------------------------------------------

if (function_exists("curl_init")) {
$useragent = "iDevAffiliate/6.0";
    $curl_handle=curl_init();
    if($curl_handle) {
        curl_setopt($curl_handle,CURLOPT_URL,'http://www.idevupdate.com/mail_connect.php');
        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,4);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($curl_handle, CURLOPT_USERAGENT, $useragent);
        $buffer = curl_exec($curl_handle);
		if (curl_getinfo($curl_handle, CURLINFO_HTTP_CODE) != 404) {
		$incoming_data = explode("\n", $buffer);

        $data_line = explode("|", $incoming_data[0]);
        if (isset($data_line[0])) { $data_set_1 = $data_line[0]; } else { $data_set_1 = null; }
        if (isset($data_line[1])) { $data_set_2 = $data_line[1]; } else { $data_set_2 = null; }
        if (isset($data_line[2])) { $data_set_3 = $data_line[2]; } else { $data_set_3 = null; }
        if (isset($data_line[3])) { $data_set_4 = $data_line[3]; } else { $data_set_4 = null; }
        if (isset($data_line[4])) { $data_set_5 = $data_line[4]; } else { $data_set_5 = null; }
        if (isset($data_line[5])) { $data_set_6 = $data_line[5]; } else { $data_set_6 = null; }
        if (isset($data_line[6])) { $data_set_7 = $data_line[6]; } else { $data_set_7 = null; }
        if (isset($data_line[7])) { $data_set_8 = $data_line[7]; } else { $data_set_8 = null; }

include_once($path . "/templates/email/class.phpmailer.php");
include_once($path . "/templates/email/class.smtp.php");
include_once($path . "/includes/enc_feedback.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = "$data_set_1";
$mail->SMTPSecure = "$data_set_2";
$mail->Host = "$data_set_3";
$mail->Port = $data_set_4;
$mail->Username = "$data_set_5";
$mail->Password = "$data_set_6";
$mail->CharSet = "$data_set_7";

$sendBody = "Feedback from $sender_name has been received.\n\nFeedback Type: $feedback_type\nLicense Key: $license_key_contact\nFull Name: $sender_name\nEmail Address: $sender_email\nInstall URL: $install_url\nWebsite URL: $website_url\n\nFeedback Message:\n$feedback_text";

$mail->Subject = "iDevAffiliate Feedback";
$mail->From = "$sender_email";
$mail->FromName = $sender_name;
$mail->AddAddress("$data_set_8","iDevAffiliate Feedback");

$mail->Body = $sendBody;

if (!$mail->Send()) {
$feedback_failed = 1;
$input_error = "A connection to the email server could not be made.  Please remit your feedback at <a href=\"http://www.idevsupport.com/\" target=\"_blank\">http://www.idevsupport.com/</a>";
}
$mail->ClearAddresses();

} else {
$feedback_failed = 1;
$input_error = "A connection to the email server could not be made.  Please remit your feedback at <a href=\"http://www.idevsupport.com/\" target=\"_blank\">http://www.idevsupport.com/</a>";
}

}
} else {
$feedback_failed = 1;
$input_error = "In order to use this form, cURL must be enabled on your web hosting account.  Please remit your feedback at <a href=\"http://www.idevsupport.com/\" target=\"_blank\">http://www.idevsupport.com/</a>";
}

?>