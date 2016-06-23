<form action="step2" method="post">
    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <h2 class="section-header">Tell Us About Your Business</h2>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <span class="sub-section-header">Contact Info&nbsp;&nbsp;</span><span class="sub-section-description">(This is used for all the disclaimers on your website and footers)</span>
        </div>
    </div>
    <hr>

    <div class="row form-group small-vertical-margins">
        <div class="col-xs-4">
            <label>Business Name<span class="required">*</span></label><input class="form-control" type="text" name="BusinessName" value="{{ BusinessName }}" />
        </div>
        <div class="col-xs-4">
            <label>Address</label><span class="required">*</span><input class="form-control" type="text" name="Address" value="{{ Address }}" required />
        </div>
        <div class="col-xs-2">
            <label>City</label><span class="required">*</span><input class="form-control" type="text" name="City" value="{{ City }}" required />
        </div>
        <div class="col-xs-1">
            <label>State</label><span class="required">*</span><input class="form-control" type="text" name="State" value="{{ State }}" required />
        </div>
        <div class="col-xs-1">
            <label>Zip</label><span class="required">*</span><input class="form-control" type="text" name="Zip" value="{{ Zip }}" required />
        </div>
    </div>
.
    <div class="row form-group small-vertical-margins">
        <div class="col-xs-4">
            <label>Phone</label><span class="required">*</span><input class="form-control" type="text" name="Phone" value="{{ Phone }}" required />
        </div>
        <div class="col-xs-4">
            <label>Email</label><span class="required">*</span><input class="form-control" type="email" name="Email" value="{{ Email }}" required />
        </div>
        <div class="col-xs-4">
            <label>Website</label><span class="required">*</span><input class="form-control" type="url" name="Website" value="{{ Website }}" required />
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <span class="sub-section-header">Email Notifications&nbsp;&nbsp;</span><span class="sub-section-description">(This is the email and name you want to use when someone registers for the software)</span>
        </div>
    </div>
    <hr>

    <div class="row form-group small-vertical-margins">
        <div class="col-xs-6">
            <label>From Name</label><input class="form-control" type="email" name="EmailFromName" value="{{ EmailFromName }}" />
        </div>
        <div class="col-xs-6">
            <label>From Email Address</label><input class="form-control" type="email" name="EmailFromAddress" value="{{ EmailFromAddress }}" />
        </div>
    </div>

    <div class="row form-group small-vertical-margins">
        <div class="col-xs-10">
        </div>
        <div class="col-xs-2">
            <button class="btn btn-primary" type="submit" style="width: 100%">Next Step</button>
        </div>
    </div>
</form>