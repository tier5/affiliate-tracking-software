<form action="thankyou" method="post">
    <?php
        if($_GET['sbyp'] || $_POST['sbyp'])
            $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
    ?>
    <input type="hidden" name="sbyp" value="{{ sbyp }}" />

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <h2 class="section-header">Stripe</h2>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <span class="sub-section-header">Stripe Integration</span>
            <br />
            Click on the following link for the Stripe integration training <a href="http://training.reviewvelocity.co/knowledge-base/stripe-integration-training/">http://training.reviewvelocity.co/knowledge-base/stripe-integration-training/</a>
        </div>
    </div>
    <hr>

    <div class="row form-group small-vertical-margins">
        <div class="col-xs-6">
            <label>Stripe Secret Key</label><input class="form-control" type="text" name="AgencyStripeSecretKey" value="{{ AgencyStripeSecretKey }}" />
        </div>
        <div class="col-xs-6">
            <label>Stripe Publishable Key</label><input class="form-control" type="text" name="AgencyStripePublishableKey" value="{{ AgencyStripePublishableKey }}" />
        </div>
    </div>

    <div class="row medium-vertical-margins form-group">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <div class="col-xs-6">
                <a href="step3"><button class="btn btn-primary" type="button" style="width: 100%">Back</button></a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-primary" style="width: 100%">Next Step</button>
            </div>
        </div>
    </div>
    <input id="sign_up" class="form-control"  name="sign_up" type="hidden" value=""/>
</form>