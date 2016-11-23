<style>
.alert{   font-size: 20px !important; }
#alert_msg {   font-size: 20px !important;background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d; }
@media screen and (max-width:767px){
  .alert{   font-size: 40px !important; }
  #alert_msg {   font-size: 40px !important; background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;}
}


</style>

 {{ flashSession.output() }} 
 <div id="alert_msg"></div>


<input type="hidden" id="link_id" value="<?php echo $linkId;?>">


<link href="/css/admin.css" rel="stylesheet">
<link href="/css/bootstrap.min_v1.css" rel="stylesheet">


<script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
<script>


var msg=$('.alert').html();
$('.alert').hide();
$('#alert_msg').html(msg);
 jQuery(document).ready(function($){
/*
 var link_id=$('#link_id').val();
 		setTimeout(function() {
  window.location.href = "/link/createlink/"+link_id;
}, 5000);*/
 });
</script>