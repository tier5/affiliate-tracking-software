<html>
<head></head>
<body style="background-color: #E4E4E4;padding: 20px; margin: 0; min-width: 640px;">
<table border="0" cellspacing="0" width="530" style="color:#262626;background-color:#fff; padding:27px 30px 20px 30px;margin:auto; border:1px solid #e1e1e1;">
	<tbody>
        <tr>
            <td style="padding:0px 0  0 0;">
                <p style="font-size: 13px;line-height:24px;font-family:'HelveticaNeue','Helvetica Neue',Helvetica,Arial,sans-serif;">
                    Hey {{firstName}},<br />

                   <P>Congratulations on joining us at {{ AgencyName }}, I know you’ll love it when you see how easy it is to generate 5-Star reviews from recent customers.</P>

                    <P>If you wouldn’t mind, I’d love it if you answered one quick question: Why did you decide to join us at {{ AgencyName }} ?</P>

                    <P>I’m asking because knowing what made you sign up is really helpful for us in making sure that we’re delivering on what our users want. Just hit "reply" and let me know.
                   </P>
                   {{login_credential}}
                    
                    To get started just confirm your email by <a style="padding:10px; margin-left:-10px;" href="http://{{ publicUrl }}{{ confirmUrl }}">Clicking Here</a><br/><br/>

                    Thanks,<br/><br/>

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

