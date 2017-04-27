<?php  if ($this->session->has('auth-identity')) {?>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <title>Mobile Review</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png" />
 

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-icon-144x144.png" />

<link href="/css/bootstrap.min_v1.css" rel="stylesheet">
 <link href="/css/style_review_new.css" rel="stylesheet" />

 <link rel="shortcut icon" href="favicon.ico"/>
   
 <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
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
              <select class="form-control required" name="location_id" id="location_id">
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
            
            <!--   <select class="form-control" name="location_id" id="location_id">
               
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>" selected><?php echo $name;?></option>
            <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
              <?php endforeach; ?>
              </select> -->
             
            <?php foreach($userlocations as $key=>$name):?>
               <input type="text" class="form-control" value="<?php echo $name;?>" readonly id="select_list" style="">

                <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
           <input type="hidden" name="location_id" id="location_id" value="<?php echo $key;?>">
               <?php endforeach; ?>


            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>
        <?php } else {?>
           <input type="hidden" name="location_name" id="location_name" value="">
           <input type="hidden" name="location_id" id="location_id" value="">
        <?php }?>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Name" name="name" id="smsrequestformname" title="Name is required.">
            <div class="left-icon">
              <img src="/img/human.jpg" class="img-responsive">
            </div>
          </div>
          <div class="form-group">
            <input type="tel" class="form-control required" placeholder="Cell Phone" name="phone" id="smsrequestformphone" title="Cell Phone Number is required.">
            <div class="left-icon">
              <img src="/img/phone.jpg" class="img-responsive">
            </div>
          </div>
            <textarea 
                                                  style="display:none;" 
                                                  class="form-control placeholder-no-fix" name="SMS_message">{% if location.SMS_message %}
                                                    {{ location.SMS_message }}{% elseif agency_sms %}{{ agency_sms }}{% else %}Hi {name}, thanks for visiting {location-name} we'd really appreciate your feedback by clicking the following link {link}. Thanks! {% endif %}</textarea>

          <input type="hidden" name="agency_id" value="<?php echo $agency;?>">
          <input type="hidden" name="user_id" value="<?php echo $user_id;?>">

          <input type="hidden" name="userID" value="<?php echo $userID;?>">

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

 <?php } else {?>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta content="" name="description"/>
<meta content="" name="author"/>
<title>Mobile Review</title>


  <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png" />
 

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="/apple-icon-57x57.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-icon-114x114.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-icon-144x144.png" />
    
 <link href="/css/bootstrap.min_v1.css" rel="stylesheet">
 <link href="/css/style_review_new.css" rel="stylesheet" />

 <link rel="shortcut icon" href="favicon.ico"/>
   
 <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
<script src="/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
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
              <select class="form-control required" name="location_id" id="location_id">
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
            
            <!--   <select class="form-control" name="location_id" id="location_id">
               
               

              <?php foreach($userlocations as $key=>$name):?>
             <option value="<?php echo $key;?>" selected><?php echo $name;?></option>
            <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
              <?php endforeach; ?>
              </select> -->
             
            <?php foreach($userlocations as $key=>$name):?>
               <input type="text" class="form-control" value="<?php echo $name;?>" readonly id="select_list" style="">

                <input type="hidden" name="location_name" id="location_name" value="<?php echo $name;?>">
           <input type="hidden" name="location_id" id="location_id" value="<?php echo $key;?>">
               <?php endforeach; ?>


            <div class="left-icon">
              <img src="/img/location.jpg" class="img-responsive">
            </div>
          </div>
        <?php } else {?>
           <input type="hidden" name="location_name" id="location_name" value="">
           <input type="hidden" name="location_id" id="location_id" value="">
        <?php }?>
          <div class="form-group">
            <input type="text" class="form-control required" placeholder="Name" name="name" id="smsrequestformname" title="Name is required.">
            <div class="left-icon">
              <img src="/img/human.jpg" class="img-responsive">
            </div>
          </div>
          <div class="form-group">
            <input type="tel" class="form-control required" placeholder="Cell Phone" name="phone" id="smsrequestformphone" title="Cell Phone Number is required.">
            <div class="left-icon">
              <img src="/img/phone.jpg" class="img-responsive">
            </div>
          </div>
            <textarea 
                                                  style="display:none;" 
                                                  class="form-control placeholder-no-fix" name="SMS_message">{% if location.SMS_message %}{{ location.SMS_message }}{% else %}Hi {name}, thanks for visiting {location-name} we'd really appreciate your feedback by clicking the following link {link}. Thanks! {% endif %}</textarea>

          <input type="hidden" name="agency_id" value="<?php echo $agency;?>">
           <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
          <input type="hidden" name="userID" value="<?php echo $userID;?>">

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

            $('input[readonly]').focus(function(){
    this.blur();
});
    });

    </script>