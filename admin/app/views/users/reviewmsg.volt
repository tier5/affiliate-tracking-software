{{ flashSession.output() }}


<input type="hidden" id="link_id" value="<?php echo $linkId;?>">

<link href="/css/bootstrap.min_v1.css" rel="stylesheet">
<link href="/css/admin.css" rel="stylesheet">

<script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
<script>

 jQuery(document).ready(function($){

 var link_id=$('#link_id').val();
 		setTimeout(function() {
  window.location.href = "/link/createlink/"+link_id;
}, 5000);
 });
</script>