<html>
<head></head>
<body style="background-color: #E4E4E4;padding: 20px; margin: 0; min-width: 640px;">
<table border="0" cellspacing="0" width="530" style="color:#262626;background-color:#fff; padding:27px 30px 20px 30px;margin:auto; border:1px solid #e1e1e1;">
	<tbody>
        <tr>
            <td style="padding:40px 0 40px 0;">
            	Hi {{employeeName}},
            	<p>
            		Thank you for activating your account, we have created a mobile landing page so that you can request feedback from your customers in person from your mobile phone. 

            	</p>

                <p>
                    Click on the link below and add the the page to your home screen so that you can easily access this page. This link is customized to you so that all feedback and reviews will be tracked back to your account.

                </p>

                 <p>The best practices is to ask your customer for feedback right after you have completed the services for them. We recommend that you ask them to please leave a review on one of the sites we suggest and to mention your name in the review online.</p>

                <p style="font-size: 13px;line-height:24px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;">

                	<a href="http://{{ publicUrl }}{{ confirmUrl }}"><i>Personalized Feedback Form - Click Here</i></a>
                 </p>   

                   <p>Do not give this link out to any one else it is a personalized link for you and will track all your feedback requests. Each employee has their own personalized feedback form. </p>

                    Login Details:</br>;
                    <p>Please view the Login Credentials Below:<br>
                       Login URL:<br>
                       Login Email:{{email}}<br>
                       Login Password:{{pasword}}
                    </p>   


                  <p>Looking forward to helping you build a strong online reputation.</p>

                    {{ AgencyUser }}<br/>
                    {{ AgencyName }}
                    <br>

                </p>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>

