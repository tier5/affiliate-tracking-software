<form action="step4" method="post" enctype="multipart/form-data" id="Step3Form">
    <?php
        if($_GET['sbyp'] || $_POST['sbyp'])
            $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
    ?>
    <input type="hidden" name="sbyp" value="{{ sbyp }}" />

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
        <div class="col-xs-4 col-xs-offset-2"><!--<a href="https://{{Subdomain}}.{{TLDomain}}/?name={{Name|url_encode}}&phone={{Phone|url_encode}}&logo_path={{logo_path|url_encode}}&primary_color={{PrimaryColorNohash|url_encode}}&secondary_color={{SecondaryColorNohash|url_encode}}" target="_blank"><button class="btn btn-primary center-block" type="button" id="PreviewButton">Preview Landing Page</button></a>-->

        <a href="https://{{Subdomain}}.{{TLDomain}}/?name={{BusinessName|url_encode}}&phone={{Phone|url_encode}}&logo_path={{logo_path|url_encode}}&primary_color={{PrimaryColorNohash|url_encode}}&secondary_color={{SecondaryColorNohash|url_encode}}" target="_blank" id="land_link"><button class="btn btn-primary center-block" type="button" id="PreviewButton">Preview Landing Page</button></a>
        </div>
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
                        {% if logo_path %}
                            <?php
                            $logo_filename = array_pop(explode('/', $logo_path));
                         ?>
                            value="{{ logo_filename }}"
                        {% else %}
                            value="No file chosen"
                        {% endif %}
                       readonly>
            </div>
        </div>
        <input id="sign_up" class="form-control"  name="sign_up" type="hidden" value="4"/>

         <?php
       if (substr($PrimaryColor, 0, 1) === '#') {
            $PrimaryColor=$PrimaryColor;
        }
        else
        {
        $PrimaryColor='#'.$PrimaryColor;
        }
        ?>
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
        <?php
       if (substr($SecondaryColor, 0, 1) === '#') {
            $SecondaryColor=$SecondaryColor;
        }
        else
        {
        $SecondaryColor='#'.$SecondaryColor;
        }
        ?>
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

             
            $.ajax({
            type: 'POST',
             url: "/agencysignup/loadingpage", 
            data:{primarycolor : $(this).val()},
            
             success: function(result){
                        //alert(result);

                        var prm=result.split('-');
                        var prm_clr=prm[0];
                       //alert(result);
                       $("a").attr("href", "https://{{Subdomain}}.{{TLDomain}}/?name={{BusinessName|url_encode}}&phone={{Phone|url_encode}}&logo_path={{logo_path|url_encode}}&primary_color="+prm_clr+"&secondary_color="+prm[1]);
                    }

                    });

        });

        $('#file-selector').change(function(){
            var image=$('#upload-file-info').val();

             $.ajax({
            type: 'POST',
             url: "/agencysignup/loadingpageimage", 
            data:{image :image},
            
             success: function(result){
                 var prm=result.split('-');

                  $("a").attr("href", "https://{{Subdomain}}.{{TLDomain}}/?name={{BusinessName|url_encode}}&phone={{Phone|url_encode}}&logo_path="+prm[2]+"&primary_color="+prm[0]+"&secondary_color="+prm[1]);

             }
             });

        })

        $('#SecondaryColor').change(function() {
            $('#SecondarySquare').css('background-color', $(this).val());
            $('#SecondarySquare').css('border-color', $(this).val());

             $.ajax({
            type: 'POST',
             url: "/agencysignup/loadingpage", 
            data:{secondarycolor : $(this).val()},
            
             success: function(result){
                        //alert(result);

                         var prm=result.split('-');
                        var sec_clr=prm[1];
                       //result=result.replace("#", "");
                       alert(result);
                       $("a").attr("href", "https://{{Subdomain}}.{{TLDomain}}/?name={{BusinessName|url_encode}}&phone={{Phone|url_encode}}&logo_path={{logo_path|url_encode}}&primary_color="+prm[0]+"&secondary_color="+sec_clr);
                    }

                    });
        });

        $('#SaveButton').click(function() {
            $('#Step3Form').attr("action", '/agencysignup/step3');
            $('#Step3Form').submit();
        });

        $('#NextStep').click(function() {
            $('#Step3Form').attr("action", '/agencysignup/step4');
            $('#Step3Form').submit();
        });
    });
</script>