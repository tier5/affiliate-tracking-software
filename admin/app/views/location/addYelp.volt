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
            <form id="hiddenLocationForm" method="POST">
            <input type="hidden" name="YelpBusinessName" value="{{ location.name }}">

            <input type="hidden" name="YelpPostalCode" value="{{ location.postal_code }}">
            </form>

            <!-- END FORM -->

            <div style="clear: both;">&nbsp;</div>
            </form>



            <!-- YELP BLOCK -->

            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <button type="button" class="connect-btn btn-lg btn-block center-block uppercase" onclick="PickYelpBusiness()" style="font-size: 28px;">Connect My Yelp Business Page</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <img class="img-responsive center-block" style="margin-top: 20px; margin-bottom: 20px;" src="/img/yelpreviews.png">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <button type="button" onclick="window.location.href = '/session/signup3';" id="register-submit-btn" class="btnsignup uppercase center-block" style="width: 80%">I DON'T HAVE A YELP BUSINESS PAGE</button>
                </div>
            </div>
    </div>
</div>

<div style="clear: both;">&nbsp;</div>

</div>
</div>

<script src="/js/location.js" type="text/javascript"></script>
<script src="/js/browser-deeplink.js" type="text/javascript"></script>

<script type="text/javascript">
    function PickYelpBusiness() {
        $('#hiddenLocationForm').attr('action', '/location/getYelpPages/{{ location.location_id }}/1');
        $('#hiddenLocationForm').submit();

        // location->name

        // location->postal_code

        $.post({

        });

    }
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