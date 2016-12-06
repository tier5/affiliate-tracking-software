{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$edit_button}</h1>
</div>

{if isset($display_edit_errors)}
<div class="alert alert-danger"><h4>{$error_title}</h4>{$error_list}
</div>
{/if}

{if isset($edit_success)}
<div class="alert alert-success">{$edit_success}
</div>
{/if}

{include file='file:account_edit_custom.tpl'}

<form method="POST" action="account.php" class="form-horizontal" id="account_edit_form">
<input type="hidden" name="edit" value="1">
<input type="hidden" name="page" value="17">

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$account_edit_general_prefs}</h4></div></div>
<div class="portlet-body">

<div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$account_edit_email_language}</label>
    <div class="col-sm-6">                  
      {include file='file:account_edit_email_preferences.tpl'}
    </div>
</div>
{if isset($optionals_used)}
{if isset($row_email)}
       <div class="form-group">
        <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_standard_email}</label>
        <div class="col-sm-6">                  
          <input type="text" class="form-control" name="email" size="30" value="{$postemail}"  tabindex="4">
        </div>
      </div>  
{/if}
 {if isset($row_company)}
      <div class="form-group">
        <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_standard_company}</label>
        <div class="col-sm-6">           
          <input type="text" class="form-control" name="company" size="30" value="{$postcompany}"  tabindex="5">
        </div>
      </div>  
 {/if}
 {if isset($row_checks)}
              <div class="form-group">
                <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_standard_checkspayable}</label>
                <div class="col-sm-6">             
                  <input type="text" class="form-control" name="payable" size="30" value="{$postchecks}"  tabindex="6">
                </div>
              </div>  
{/if}
 {if isset($row_website)}
          <div class="form-group">
            <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_standard_weburl}</label>
            <div class="col-sm-6">           
              <input type="text" class="form-control" name="url" size="30" value="{$postwebsite}"  tabindex="7">
            </div>
          </div>  
{/if}
  {if isset($row_taxinfo)}
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_standard_taxinfo}</label>
    <div class="col-sm-6">             
      <input type="text" class="form-control" name="tax_id_ssn" size="30" value="{$posttax}" tabindex="8">
    </div>
  </div>  
  {/if}
{/if}

</div>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$edit_personal_title}</h4></div></div>
<div class="portlet-body">

   <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_fname}</label>
    <div class="col-sm-6">              
      <input type="text" class="form-control" name="f_name" value="{$postfname}"  tabindex="9">
    </div>
  </div>
   <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_lname}</label>
    <div class="col-sm-6">            
      <input type="text" class="form-control" name="l_name" value="{$postlname}"  tabindex="10">
    </div>
  </div>
      <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_phone}</label>
    <div class="col-sm-6">              
      <input type="text" class="form-control" name="phone" value="{$postphone}"  tabindex="15">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_fax}</label>
    <div class="col-sm-6">             
      <input type="text" class="form-control" name="fax" value="{$postfaxnm}"  tabindex="16">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_addr1}</label>
    <div class="col-sm-6">           
      <input type="text" class="form-control" name="address_one" value="{$postaddr1}"  tabindex="11">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_addr2}</label>
    <div class="col-sm-6">             
      <input type="text" class="form-control" name="address_two" value="{$postaddr2}"  tabindex="12">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_city}</label>
    <div class="col-sm-6">            
      <input type="text" class="form-control" name="city" value="{$postcity}"  tabindex="13">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_state}</label>
    <div class="col-sm-6">              
      <input type="text" class="form-control" name="state" value="{$poststate}"  tabindex="14">
    </div>
  </div>
     <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_zip}</label>
    <div class="col-sm-6">            
      <input type="text" class="form-control" name="zip" value="{$postzip}"  tabindex="17">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label" style="font-weight:normal;">{$edit_personal_country}</label>
    <div class="col-sm-6">
        <select name="country" class="form-control">
            {$c_drop}
        </select>
    </div>
</div>
</div>
</div>
</div>
</div>

 <div class="form-group">
    <label class="col-sm-3 control-label"></label>
    <div class="col-sm-6">
       <input class="btn btn-primary" type="submit" value="{$edit_button}">
    </div>
</div>

</form>