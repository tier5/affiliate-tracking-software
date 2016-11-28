<form action="step4" method="post">
    <?php
        if($_GET['sbyp'] || $_POST['sbyp'])
            $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
    ?>
    <input type="hidden" name="sbyp" value="{{ sbyp }}" />

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <h2 class="section-header">Twilio</h2>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <span class="sub-section-header">Twilio Integration</span>
        </div>
    </div>
    <hr>

    <div class="row form-group small-vertical-margins">
        <div class="col-xs-6">
            <label>Twilio API Key</label><input class="form-control" type="text" name="TwilioAPIKey" value="{{ TwilioAPIKey }}" />
        </div>
        <div class="col-xs-6">
            <label>Twilio Token</label><input class="form-control" type="text" name="TwilioToken" value="{{ TwilioToken }}"/>
        </div>
    </div>

    <div class="row form-group">
    	<div class="col-xs-6">
            <label>Twilio Messaging Service SID</label><input class="form-control" type="text" name="TwilioSID" value="{{ TwilioSID }}" />
        </div>

        <div class="col-xs-6">
            <label>Twilio From Number</label><input class="form-control" type="text" name="TwilioFromNumber" value="{{ TwilioFromNumber }}" />
        </div>
    </div>

    <div class="row medium-vertical-margins form-group">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <div class="col-xs-6">
                <a href="step2"><button class="btn btn-primary" type="button" style="width: 100%">Back</button></a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-primary" style="width: 100%">Next Step</button>
            </div>
        </div>
    </div>
</form>