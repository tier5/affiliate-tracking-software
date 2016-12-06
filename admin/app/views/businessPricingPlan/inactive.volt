<style type="text/css">


</style>
<div class="login">
    <div class="content">
        <div class="alert alert-danger">
            <strong>In-active subscription</strong>
        </div>
        <p>The subscription you've reached has been removed, or it is no longer active.</p>
        <p>We are taking you to the new subscription page. If you don't want to wait, <a href="/session/invite/{{ short_code }}">click here</a>.</p>
    </div>
</div>

<script type="text/javascript">
    setTimeout(function(){
        window.location = '/session/invite/{{ short_code }}';
    },8000);
</script>