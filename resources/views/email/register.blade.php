<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Register Successfully</title>
    <style type="text/css">
        .standardCSS{
            color:#505050;
            font-size:15px;
        }

        @media only screen and (max-width:480px){
            .mediaQueryCSS{
                color:#CCCCCC;
                font-size:20px;
            }
        }
    </style>
</head>
<body class="mediaQueryCSS">
<table class="standardCSS" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="0" width="600" id="emailContainer">
                <tr>
                    <td align="center" valign="top">
                        <h3>Inter Web Leads (www.interwebleads.com)</h3>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        Dear {{ $affiliate_name }},
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        You have been successfully registered in <strong>{{ $campaign_name  }}</strong> as an affiliate.
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        Login Link : <a href="https://www.interwebleads.com/" target="_blank">https://www.interwebleads.com/</a>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        Login Email : {{ $affiliate_email }}
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        Login Password : {{ $affiliate_password }}
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        Affiliate link : {{ $affiliate_url."?affiliate_id=".$affiliate_key }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>