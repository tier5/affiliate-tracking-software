<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD><TITLE></TITLE>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META content="MSHTML 6.00.6001.18319" name=GENERATOR>
</HEAD>
<BODY bgColor=#ffffff>
<STYLE type=text/css>
    <!--
    p.one
    {border-left-color:#397dd0;
        border-bottom-color:#397dd0;
        margin-left:50px;
        width:500px;
        border-top-style:solid;
        border-top-color:#397dd0;
        border-right-style:solid;
        border-left-style:solid;
        height:225px;
        border-right-color:#397dd0;
        border-bottom-style:solid}
    -->
</STYLE>
<P class=one style="WIDTH: 444px;
   HEIGHT: 257px"><BR>
<TABLE name="ApprovalInfo">
    <COLGROUP width=25></COLGROUP>
    <TBODY>
    <TR>
        <TD colspan="2">Dear {{ $affiliateUser->name }}</TD>
    </TR>
    <TR>
    <TR>
        <TD></TD>
        <TH><SPAN style="FONT-FAMILY: Arial Narrow">
   Approval Information:</SPAN></TH></TR>
    <TR>
        <TD>Campaign Name</TD>
        <TD><SPAN style="FONT-FAMILY: Arial Narrow">
   {{ $campaign->name }}
   </SPAN></TD></TR>
    <TR>
        <TD></TD>
        <TD><SPAN style="FONT-FAMILY: Arial Narrow">Form:
   </SPAN>{{ $user->name }}</TD></TR>
    <TR>
        <TD></TD>
        <TD><SPAN style="FONT-FAMILY: Arial Narrow">-----------------------------------------
   </SPAN></TD></TR>
    <TR>
        <TD></TD>
        <TD><SPAN style="FONT-FAMILY: Arial Narrow">
<HR>
</SPAN></TD></TR>
    <TR>
        <TD></TD>
        <TD><SPAN style="FONT-FAMILY: Arial Narrow">
   Affiliate id: {{ $affiliate->key }}</SPAN></TD>
    </TR>
    <TR></TR>
    <TR></TR>
    </TBODY>
</TABLE>
</BODY></HTML>
