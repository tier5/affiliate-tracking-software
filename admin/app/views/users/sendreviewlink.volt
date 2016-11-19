<?php  if ($this->session->has('auth-identity')) {?>

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="">
        
             <a style="float: right; padding-left: 35px; padding-right: 35px; line-height: 17px;" class="btnLink btnSecondary" href="/users<?=($profilesId==3?'':'/admin')?>">Back</a>
           
            <span style="display: inline-block; margin-left: 8px; margin-top: 11px;" class="caption-subject bold uppercase"> Send Review Link </span>
        </div>
    </div>
    <div class="portlet-body form">
    
        {{ content() }}
       


<section class="main-content">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="form">
        <form action="/location/send_review_invite_employee" role="form" method="post" autocomplete="off" id="link_review">
          
          <?php if(!empty($userlocations) && count($userlocations)>1){ ?>
          <div class="form-group">
            <div class="select">
              <select class="form-control" name="location_id" id="location_id">
               <option value="">Select</option>
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>"><?php echo $name;?></option>
              <?php endforeach; ?>
              </select>
              <input type="hidden" name="location_name" id="location_name" value="">
            </div>
            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>

          <?php } else if(!empty($userlocations) && count($userlocations)==1) {  ?>

            <div class="form-group">
            <div class="select">
              <select class="form-control" name="location_id" id="location_id">
               
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>" selected><?php echo $name;?></option>
            <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
              <?php endforeach; ?>
              </select>
             
            </div>
            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>
        <?php }?>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Name" name="name" id="smsrequestformname" title="Name is required.">
            <div class="left-icon">
              <img src="/img/human.jpg" class="img-responsive">
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Cell Phone" name="phone" id="smsrequestformphone" title="Cell Phone Number is required.">
            <div class="left-icon">
              <img src="/img/phone.jpg" class="img-responsive">
            </div>
          </div>
            <textarea 
                                                  style="display:none;" 
                                                  class="form-control placeholder-no-fix" name="SMS_message">{% if location.SMS_message %}{{ location.SMS_message }}{% else %}{location-name}: Hi {name}, We'd realllly appreciate your feedback by clicking the link. Thanks! {link}{% endif %}</textarea>

          <div class="form-group">
            <input type="submit" value="Submit">
          </div>

          </form>

          <p>By clicking submit you confirm you have received permission to send a text messages.</p>
        </div>
      </div>
      <div class="col-md-6"></div>
    </div>
  </div>
</section>
      
    </div>
</div>

 <?php } else {?>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <meta content="" name="description"/>
    <meta content="" name="author"/>
<title>Review Velocity</title>

<link href="/css/bootstrap.min_v1.css" rel="stylesheet">
 <link href="/css/style_review_new.css" rel="stylesheet" />

 <link rel="shortcut icon" href="favicon.ico"/>
   
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

</head>
<body>
<!-- Main Content Start -->
<section class="main-content">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="form">
        <form action="/link/send_review_invite_employee" role="form" method="post" autocomplete="off" id="link_review">
          
          <?php if(!empty($userlocations) && count($userlocations)>1){ ?>
          <div class="form-group">
            <div class="select">
              <select class="form-control" name="location_id" id="location_id">
               <option value="">Select</option>
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>"><?php echo $name;?></option>
              <?php endforeach; ?>
              </select>
              <input type="hidden" name="location_name" id="location_name" value="">
            </div>
            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>

          <?php } else if(!empty($userlocations) && count($userlocations)==1) {  ?>

            <div class="form-group">
            <div class="select">
              <select class="form-control" name="location_id" id="location_id">
               
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>" selected><?php echo $name;?></option>
            <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
              <?php endforeach; ?>
              </select>
             
            </div>
            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>
        <?php }?>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Name" name="name" id="smsrequestformname" title="Name is required.">
            <div class="left-icon">
              <img src="/img/human.jpg" class="img-responsive">
            </div>
          </div>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Cell Phone" name="phone" id="smsrequestformphone" title="Cell Phone Number is required.">
            <div class="left-icon">
              <img src="/img/phone.jpg" class="img-responsive">
            </div>
          </div>
            <textarea 
                                                  style="display:none;" 
                                                  class="form-control placeholder-no-fix" name="SMS_message">{% if location.SMS_message %}{{ location.SMS_message }}{% else %}{location-name}: Hi {name}, We'd realllly appreciate your feedback by clicking the link. Thanks! {link}{% endif %}</textarea>

          <input type="hidden" name="agency_id" value="<?php echo $agency;?>">

          <div class="form-group">
            <input type="submit" value="Submit">
          </div>

          </form>

          <p>By clicking submit you confirm you have received permission to send a text messages.</p>
        </div>
      </div>
      <div class="col-md-6"></div>
    </div>
  </div>
</section>
<div class="clear"></div>
</body>
</html>

 <?php }?>






<script type="text/javascript">
   
    jQuery(document).ready(function ($) {

       $('#location_id').change(function () {
            elem = $(this);
            if(elem.val()!='')
            {
              $('#location_name').val($('#location_id option:selected').text());
            }

            });

            $('#link_review').validate();
    });

    </script>