{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$signup_security_title}</h4></div></div>
<div class="portlet-body">

<p>{$signup_security_info}</p>
<br />

 <div class="form-group">
    <label class="col-md-3 control-label">{$signup_security_code} <span style="color:#CC0000;">*</span></label>
    <div class="col-md-6">      
      <input class="form-control" id="security_code" name="security_code" type="text" />
    </div>
  </div>
  
   <div class="form-group">
    <div class="col-md-offset-3 col-md-6">      
      {$captcha_image}
    </div>
  </div>
  
</div>
</div>
</div>
</div>