<form action="/agencysignup/step2" method="post" id="Step1Form">
    <?php
        if($_GET['sbyp'] || $_POST['sbyp'])
            $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
    ?>
    <input type="hidden" name="sbyp" value="{{ sbyp }}" />

    <div class="row">
        <div class="col-xs-12 text-center">
            <span class="sub-section-header"><h1 class="bold">One Time Offer</h1></span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <span class="sub-section-header"><h3 class="bold">Double The Amount Of Accounts & Get 20% Off For Life!</h3></span>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            <span class="sub-section-header"><h3 class="blue slight-bold">Upgrade To 20 Accounts For $160 Per Month & All New Additional Accounts Will Be $8 For Life.</h3></span>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <button class="btn btn-primary center-block" type="button" id="UpgradeButton">Upgrade Now</button>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12 text-center">
            <span class="sub-section-header"><h5 ><a class="tertiary-text" id="NoThanks">No Thanks I Don't Want To Save 20% For Life!</a></h5></span>
        </div>
    </div>
     <input id="sign_up" class="form-control"  name="sign_up" type="hidden" value="2"/>

    <input type="hidden" value="0" name="Upgrade" id="Upgrade" />
</form>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#UpgradeButton').click(function() {
            $('#Upgrade').val(1);
            $('#Step1Form').submit();
        });

        $('#NoThanks').click(function() {
            $('#Upgrade').val(0);
            $('#Step1Form').submit();
        });
    });
</script>