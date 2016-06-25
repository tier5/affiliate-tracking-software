<form action="step3" method="post">
    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <h2 class="section-header">Customized Agency Landing Page</h2>
        </div>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <img class="center-block" src="/img/agencysignup/step2_main_image.png">
        </div>
    </div>
    <div class="row medium-vertical-margins">
        <button class="btn btn-primary center-block">Preview Landing Page</button>
    </div>

    <div class="row small-vertical-margins">
        <div class="col-xs-12">
            <span class="sub-section-header">Logo & Colors</span>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-xs-6">
            <label><h4 id="UploadText" class="tertiary-text">Upload your Logo</h4></label>
        </div>
        <div class="col-xs-6">
            <label><h4 class="tertiary-text">Primary Color</h4></label>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-xs-6">
            <div class="col-xs-4">
                <label class="btn btn-primary btn-file" for="file-selector" style="width: 100%"> Choose File </label><input name="LogoFilename" id="file-selector" style="display: none;" type="file" onchange="$('#upload-file-info').val($(this).val());"></label>
            </div>
            <div class="col-xs-8">
                <input type="text" class="form-control" id="upload-file-info"
                    {% if LogoFilename %}
                        value="{{  LogoFilename }}"
                    {% else %}
                        value="No file chosen"
                    {% endif %}
                    readonly>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="rounded-square primary-color" style="float: left; margin-right: 15px;"></div><input type="text" class="form-control" name="PrimaryColor" value="{{ PrimaryColor }}" style="width: 90%;">
        </div>
    </div>

    <div class="row form-group">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <label><h4 class="tertiary-text">Secondary Color</h4></label>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <div class="rounded-square secondary-color" style="float: left; margin-right: 15px;"></div><input type="text" class="form-control" name="SecondaryColor" value="{{ SecondaryColor }}" style="width: 90%;">
        </div>
    </div>

    <div class="row medium-vertical-margins form-group">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <div class="col-xs-6">
                <a href="step1"><button class="btn btn-primary" type="button" style="width: 100%">Back</button></a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-primary" style="width: 100%">Next Step</button>
            </div>
        </div>
    </div>
</form>