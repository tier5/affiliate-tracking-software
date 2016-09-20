<form action="step3" method="post" enctype="multipart/form-data" id="Step2Form">
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
        <div class="col-xs-4 col-xs-offset-2"><a href="//{{Subdomain}}.getmobilereviews.com/?name={{Name}}&phone={{Phone}}&logo_path={{LogoFilename}}&primary_color={{PrimaryColorNohash}}&secondary_color={{SecondaryColorNohash}}" target="_blank"><button class="btn btn-primary center-block" type="button" id="PreviewButton">Preview Landing Page</button></a></div>
        <div class="col-xs-4"><button class="btn btn-primary center-block" type="button" id="SaveButton">Save Changes</button></div>
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
            <div class="rounded-square primary-color" id="PrimarySquare" style="float: left; margin-right: 15px; background-color: {{ PrimaryColor }}; border-color: {{ PrimaryColor }}"></div><input id="PrimaryColor" type="color" class="form-control" name="PrimaryColor" value="{{ PrimaryColor }}" style="width: 90%;">
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
            <div class="rounded-square secondary-color" id="SecondarySquare" style="float: left; margin-right: 15px; background-color: {{ SecondaryColor }}; border-color: {{ SecondaryColor }}"></div><input id="SecondaryColor" type="color" class="form-control" name="SecondaryColor" value="{{ SecondaryColor }}" style="width: 90%;">
        </div>
    </div>

    <div class="row medium-vertical-margins form-group">
        <div class="col-xs-6"></div>
        <div class="col-xs-6">
            <div class="col-xs-6">
                <a href="step1"><button class="btn btn-primary" type="button" style="width: 100%">Back</button></a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-primary" style="width: 100%" id="NextStep">Next Step</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $( document ).ready(function() {
        $('#PrimaryColor').change(function() {
            $('#PrimarySquare').css('background-color', $(this).val());
            $('#PrimarySquare').css('border-color', $(this).val());
        });

        $('#SecondaryColor').change(function() {
            $('#SecondarySquare').css('background-color', $(this).val());
            $('#SecondarySquare').css('border-color', $(this).val());
        });

        $('#SaveButton').click(function() {
            $('#Step2Form').attr("action", '/agencysignup/step2');
            $('#Step2Form').submit();
        });

        $('#NextStep').click(function() {
            $('#Step2Form').attr("action", '/agencysignup/step3');
            $('#Step2Form').submit();
        });
    });
</script>