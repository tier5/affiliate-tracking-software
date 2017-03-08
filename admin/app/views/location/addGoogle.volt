<!-- BEGIN SAMPLE FORM PORTLET-->
<style type="text/css">
    .pnlAddLocation.short {
        min-height: 230px !important;
    }

    .backBtn {
        float: right;
        padding-left: 35px;
        padding-right: 35px;
        line-height: 17px;
    }

    .editBtn {
        display: inline-block;
        margin-left: 8px;
        margin-top: 11px;
    }

    .connect-btn {
        background-color: #c01209;
        border: medium none;
        border-radius: 26px !important;
        color: #ffffff;
        font-weight: bold;
        height: 77px;
        /*width: 483px;*/
        font-size: 30px;
        background-color: #449d44;
        border-color: #398439;
    }
</style>
<div class="portlet light bordered">
    <div class="portlet-body form">
        {{ content() }}
            <!-- BEGIN FORM -->
                
            <div style="display: none;">
            Google My Business is a free tool from Google to help you manage your business's online presence across Google, including Search and Maps.

            If you dpn't already have it you can sign up here google.com/business.
            </div>

            <!-- GOOGLE BLOCK -->

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <button type="button" class="connect-btn btn-lg btn-block center-block uppercase" onclick="window.location.href = '{{ authUrl }}';">Connect Google My Business</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <img class="img-responsive center-block" style="margin-top: 20px; margin-bottom: 20px;" src="/img/gmb.png">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <button type="button" onclick="window.location.href = '/location/addFacebook/{{ location_id }}';" id="register-submit-btn" class="btnsignup uppercase center-block" style="width: 80%">I DON'T HAVE GOOGLE MY BUSINESS</button>
                </div>
            </div>

    </div>
</div>

<script src="/js/location.js" type="text/javascript"></script>
<script src="/js/browser-deeplink.js" type="text/javascript"></script>

<script type="text/javascript">

    jQuery(document).ready(function ($) {

        // Scanning form validation and ajax submit
        $('#register-submit-btn').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                beforeSend: function () {
                    $("#loader").show();
                    $("#relevant-result-list").hide();
                    $("#scan-tool-landing-page").hide();
                    $(".footerscan").hide();
                    $("#hiddenForm").hide();
                },
                complete: function () {
                    $("#loader").hide();
                    $("#relevant-result-list").show();
                    $(".footerscan").show();
                    // $("#relevant-result-list").empty();
                },
                url: '/session/googlesearchapi/',
                data: {
                    location_name: $.trim($('#location_name').val()),
                    zip: $.trim($('#zip_code').val())
                },
                dataType: 'json',
                //async  : false,
                success: function (response) {
                    if (response.errorMsg) {
                        $("#relevant-result-list").html(response.errorMsg);
                    }
                    else {
                        $("#relevant-result-list").html(response.HTML);
                    }
                }
            });
        });

        $('#urllink, #urllinkyelp').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').show();
            $('.overlay').show();
        });
        $('.overlay, .closelink').on('click', function (e) {
            e.preventDefault();
            $('#page-wrapper').hide();
            $('.overlay').hide();
        });

    });


</script>