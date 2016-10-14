{{ content() }}
<?php if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) { ?>
<header class="jumbotron subhead" id="dashboard">
    <div class="hero-unit">
        <!-- END PAGE TITLE-->
        <div class="row">
            <div class="col-md-5 col-sm-5">
                <h3 class="page-title"> Dashboard
                    <small>dashboard & statistics</small>
                </h3>
            </div>
            <?php
            if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
            if ($is_upgrade) {
            $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) * 100, 0, '.', ''):100);
            if ($percent > 100) $percent = 100;
            ?>
            <div class="col-md-7 col-sm-7">
                <div class="sms-chart-wrapper">
                    <div class="title">SMS Messages Sent</div>
                    <div class="bar-wrapper">
                        <div class="bar-background"></div>
                        <div class="bar-filled" style="width: <?=$percent?>%;"></div>
                        <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
                        <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month_total?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>
                    </div>
                    <div class="end-title"><?=$total_sms_month?><br /><span class="goal">Allowed</span></div>
                </div>
            </div>
            <?php
            } else {
            $percent = ($total_sms_needed > 0 ? number_format((float)($sms_sent_this_month / $total_sms_needed) * 100, 0, '.', ''):100);
            if ($percent > 100) $percent = 100;
            ?>
            <div class="col-md-7 col-sm-7">
                <div class="sms-chart-wrapper">
                    <div class="title">SMS Messages Sent</div>
                    <div class="bar-wrapper">
                        <div class="bar-background"></div>
                        <div class="bar-filled" style="width: <?=$percent?>%;"></div>
                        <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
                        <div class="bar-number" style="margin-left: <?=$percent?>%;"><div class="ball"><?=$sms_sent_this_month?></div><div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div></div>
                    </div>
                    <div class="end-title"><?=$total_sms_needed?><br /><span class="goal">Goal</span></div>
                </div>
            </div>
            <?php
            }
            } //end checking for business vs agency
            ?>
        </div>

        <?php
        //check if the user should upgrade
        if ($is_upgrade) {
        ?>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet dark bordered discount">
                    <img src="/img/20-percent-off.gif" id="percentoff" alt="20% Off" />
                    <a href="#"><img src="/img/btn-upgrade-now.gif" id="btnupgradenow" alt="Upgrade Now" /></a>
                    <div class="upgrade-middle">
                        <div class="upgrade-top">Hey <?=$this->session->get('auth-identity')['name']?>!  Upgrade Your Account Today To Send Unlimited Text Message</div>
                        <div class="upgrade-bottom">Add unlimited text message so you can send out unlimited feedback request each month.  Save 20% today!</div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } // end checking for need to upgrade
        ?>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet light bordered">
                    <div class="portlet-body" id="reportwrapper">
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="share-text"><b>Get <?=$additional_allowed?> additional SMS messages for every 1 referral that sign up.</b>  Use the following links to share your referral URL: </div>
                                    <?php
                                    if ($num_signed_up > 0) {
                                    ?>
                                    <div class="share-text"><?=$num_signed_up?> friends have signed up using your link. You are entitled to <?=($additional_allowed * $num_discount)?> additional SMS messages per month for a total of <?=$total_sms_month?> SMS messages per month.</div>
                                    <?php
                                    }
                                    ?>
                                    <div class="share-inner">
                                        <a id="maillink" class="share-link" href="mailto:?&subject=<?=$share_subject?>&body=<?=$share_message?>">Send Email <img src="/img/icon_sm_email.gif" /></a>
                                        <a target="_blank" class="share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?=$share_link?>">Share on Facebook <img src="/img/icon_sm_facebook.gif" /></a>
                                        <a target="_blank" class="share-link" href="https://twitter.com/home?status=<?=$share_message?>">Share on Twitter  <img src="/img/icon_sm_twitter.gif" /></a>
                                        <a target="_blank" class="share-link" href="https://plus.google.com/share?url=<?=$share_link?>">Share on Google+  <img src="/img/icon_sm_google.gif" /></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="referral-link"><b>Personalized Referral Link:</b> <?=$share_link?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-md-2 col-sm-2">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Rating</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="number">
                            <span data-value="<?=number_format((float)$average_rating, 1, '.', '')?>" data-counter="counterup"><?=number_format((float)$average_rating, 1, '.', '')?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-sm-2">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Total Reviews</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="number">
                            <span data-value="<?=(isset($total_reviews)?$total_reviews:0)?>" data-counter="counterup"><?=(isset($total_reviews)?$total_reviews:0)?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4">
                <div class="portlet light bordered dashboard-panel Monthly-Goal-New-Reviews">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Monthly Goal New Reviews</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="top-part">
                            <div class="col-md-6 col-sm-6">
                                <div class="number">
                                    <span data-value="<?=(isset($review_goal)?$review_goal:0)?>"><?=(isset($review_goal)?$review_goal:0)?></span>
                                </div>
                                <div class="desc">Goal</div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="number">
                                    <span data-value="<?=(isset($total_reviews_this_month)?$total_reviews_this_month:0)?>"><?=(isset($total_reviews_this_month)?$total_reviews_this_month:0)?></span>
                                </div>
                                <div class="desc" style="min-height: 36px;">Reviews Received</div>
                            </div>
                        </div>
                        <div class="bottom-part">
                            <span class="text-wrapper text-center" style="width: 100%">
                                You Must Send <span class="feedback_requests"><?=$total_sms_needed?></span> Feedback Requests To Reach Your Goal Of <span class="feedback_requests"><?=$review_goal ?: 0; ?></span> New Reviews
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4">
                <div class="portlet light bordered dashboard-panel" style="overflow: hidden; height: 193px;">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Feedback Requests (This Month)</span>
                        </div>
                    </div>
                    <?php
                    $percent = ($total_sms_needed > 0 ? number_format((float)($sms_sent_this_month / $total_sms_needed) * 100, 0, '.', ''):0);
                    if ($percent > 100) $percent = 100;
                    //$percent = 0;
                    ?>
                    <div class="portlet-body circle-chart">
                        <div class="left-header"><?=$percent?>%</div>
                        <div class="right-header"><div class="num"><?=$total_sms_needed?></div>Goal</div>
                        <div id="piechart" style="height: 300px;"></div>
                        <div class="bottom-header"><div class="num"><?=$sms_sent_this_month?></div>Messages Sent</div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="growth-bar">
                    <img src="/img/icon-growth.gif" /> Growth
                </div>
            </div>
        </div>
        <div class="row growth-row">

            <div class="col-md-2 col-sm-2">

                <div class="portlet light bordered dashboard-panel half-height">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">New Reviews</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="number">
                            <span data-value="<?=$total_reviews_this_month?>" style="color: #2A3644;"><?=$total_reviews_this_month?></span>
                        </div>
                        <div class="text">
                            This Month
                        </div>
                    </div>
                </div>

                <div class="portlet light bordered dashboard-panel half-height">
                    <div class="portlet-body">
                        <div class="number">
                            <span data-value="<?=$total_reviews_location?>" style="color: #2A3644;"><?=$total_reviews_location?></span>
                        </div>
                        <div class="text">
                            New Reviews Since Joining Review Velocity
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-5 col-sm-5">
                <div class="portlet light bordered dashboard-panel tall">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">New Reviews</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <?php
                        if ($new_reviews->count() > 0) {
                        ?>
                        <div id="barchart_div"></div>
                        <?php
                        } else {

                        }
                        ?>
                    </div>
                </div>
            </div>


            <div class="col-md-5 col-sm-5">
                <div class="portlet light bordered dashboard-panel tall">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Negative Reviews Saved</span>
                            <div class="number"><span data-value="<?=$negative_total?>" data-counter="counterup"><?=$negative_total?></span></div>
                        </div>
                    </div>
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Revenue At Risk</span>
                            <div class="number">$<span data-value="<?=number_format($negative_total * $location->lifetime_value_customer)?>" data-counter="counterup"><?=number_format($negative_total * $location->lifetime_value_customer)?></span></div>
                        </div>
                    </div>
                    <div class="portlet-title last">
                        <div class="caption">
                            <span class="">Revenue Retained</span>
                            <div class="number green">$<span data-value="<?=number_format($revenue_retained)?>" data-counter="counterup"><?=number_format($revenue_retained)?></span></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <?php if (isset($employee_conversion_report)) { ?>
            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Employee Leaderboard</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-hover table-light employee_conversion_report">
                            <thead>
                                <tr class="uppercase">
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Reviews Sent</th>
                                    <th>Reviews Received</th>
                                    <th>Customer Satisfaction</th>
                                </tr>
                            </thead>
                            <?php
                            $i = 0;
                            $class = '';
                            foreach($employee_conversion_report as $data) {
                            $i++;
                            if ($class == '') { $class = 'darker'; } else { $class = ''; }
                          	?>
                            <tr>
                                <td class="<?=$class?>"><?=$i?><?=($i==1?'st':($i==2?'nd':($i==3?'rd':'th')))?></td>
                                <td class="<?=$class?>"><?=$data->name?></td>
                                <td class="<?=$class?>"><?=$data->sms_sent_this_month?></td>
                                <td class="<?=$class?>"><?=($data->sms_received_this_month)?></td>
                                <td class="<?=$class?>"><?=($data->sms_sent_this_month > 0?(number_format(($data->positive_feedback_this_month / $data->sms_received_this_month) * 100, 1) . '%'):'0.0%')?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>


            <?php if (isset($review_report)) { ?>
            <div class="col-md-6 col-sm-6">
                <div class="portlet light bordered dashboard-panel">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="">Recent Reviews</span>
                        </div>
                    </div>
                    <div class="portlet-body" id="reportwrapper">
                        <?php
                        foreach($review_report as $data) {
                        ?>
                        <div class="review">
                            <div class="top">
                                <div class="logo"><a href="<?=($data->rating_type_id==1?'https://www.yelp.com/biz/'.$yelp_id:($data->rating_type_id==2?'http://facebook.com/'.$facebook_page_id:'https://www.google.com/search?q='.urlencode($location->name.', '.$location->address.', '.$location->locality.', '.$location->state_province.', '.$location->postal_code.', '.$location->country).'&ludocid='.$google_place_id.'#'))?>" target="_blank"><img src="/img/logo/icon-<?=($data->rating_type_id==1?'yelp':($data->rating_type_id==2?'facebook':'google'))?>.gif" /></a></span></div>
                                <div class="rating col-md-3"><input value="<?=$data->rating?>" class="rating-loading starfield" data-size="xxs" data-show-clear="false" data-show-caption="false" data-readonly="true" /></div>
                                <div class="name col-md-5"><?=$data->user_name?></div>
                                <div class="date col-md-3"><?=date("m/d/Y", strtotime($data->time_created))?></div>
                            </div>
                            <div class="content">
                                <?php
                                $text = $data->review_text;
                                $text = $text." ";
                                $text = substr($text,0,100);
                                $text = substr($text,0,strrpos($text,' '));
                                $text = $text."...";
                                echo $text;
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>

        </div>
    </div>
</header>
<div class="overlay" style="display: none;"></div>
<div id="page-wrapper" style="display: none;">
    <div class="closelink close"></div>
    <h1>Send Email</h1>

    <div id="form-messages"></div>

    <form id="ajax-contact" method="post" action="/location/send_email">
        <div class="field">
            <label for="name">Subject:</label>
            <input type="text" id="subject" name="subject" value="<?=$share_subject?>" required="required" />
        </div>

        <div class="field">
            <label for="email">To Email:</label>
            <input type="email" id="email" name="email" required="required" />
        </div>

        <div class="field">
            <label for="message">Message:</label>
            <textarea id="message" name="message" required="required"><?=$share_message?></textarea>
        </div>

        <div class="field">
            <input class="btn btn-big btn-success" type="submit" value="Send" />
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        var primary_color = $('#primary_color').val();
        var secondary_color = $('#secondary_color').val();
        console.log(primary_color,secondary_color);
        console.log(secondary_color);

        if(primary_color == "#"){
            delete(primary_color);
        }
        if(secondary_color == "#"){
            delete(secondary_color);
        }
        $('#maillink').on('click', function(e) {
            e.preventDefault();
            $('#page-wrapper').show();
            $('.overlay').show();
        });

        $('.overlay, .closelink').on('click', function(e) {
            e.preventDefault();
            $('#page-wrapper').hide();
            $('.overlay').hide();
        });

        var barColor = primary_color ? primary_color : '#F8CB00';
        $('.easy-pie-chart .number.monthly-review').easyPieChart({
            animate: 1000,
            size: 100,
            lineWidth: 3,
            'barColor': barColor
        });
        //alert('width:'+viewport());

        function viewport() {
            var e = window, a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return 'width : ' + e[ a + 'Width' ] + ': height : ' + e[ a + 'Height' ];
        }

        $('.starfield').rating({displayOnly: true, step: 0.5});
            google.charts.load('current', {packages: ['corechart', 'bar']});
            google.charts.setOnLoadCallback(drawBasic);

            function drawBasic() {
                var color = secondary_color ? secondary_color : '#67cd4d';
                var data = google.visualization.arrayToDataTable([
                    ['Month', 'Density', { role: 'style' }],
                <?php
                    $prevmonth = 0;
                    $count = 0;
                    $strarray = '';
                    $color = ($primary_color) ? $primary_color : '';
                    foreach ($new_reviews as $data) {
                        if ($new_reviews->count() > 6 && $count == 0) {
                        } else {
                            $strarray = $strarray."['".date("M", mktime(0, 0, 0, $data-> month, 1, 2011))."', ".($data-> reviewcount - $prevmonth).", '".$color."'],\n";
                        }
                        $prevmonth = $data->reviewcount;
                        $count++;
                    }
                    echo $strarray;
                ?>
                ]);

                var options = {
                    title: 'New Reviews By Month',
                    legend: {position: 'none'},
                    chartArea: {left:25, top:'auto', width:'100%', height:'auto'},
                    'tooltip': {
                        trigger: 'none'
                    }
                };

                console.log(data)
                var chart = new google.visualization.ColumnChart(document.getElementById('barchart_div'));
                chart.draw(data, options);
            }

            //google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable([
                    ['Task', 'Hours per Day'],
                    ['Sent', <?= $percent ?> ],
                    ['Goal', <?= 100 - $percent ?> ],
                    ['Commute', 100],
                ]);
                var first_color = (primary_color !== '#') ?  primary_color : '#67cd4d';

                var second_color = (secondary_color !== '#' && secondary_color !== '') ?  secondary_color : '#E1F5DA';


                var options = {
                    title: '',
                    legend: {position: 'none'},
                    chartArea: {left:0, top:0, width:'100%', height:'auto'},
                    pieHole: 0.85,
                    pieStartAngle: 270,
                    slices: {
                        0: { color: first_color },
                        1: { color: second_color },
                        2: { color: 'transparent' }
                    },
                    pieSliceTextStyle: {color: 'transparent'},
                        'tooltip': {
                        trigger: 'none'
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }

            // Get the form.
            var form = $('#ajax-contact');
            // Get the messages div.
            var formMessages = $('#form-messages');
            // Set up an event listener for the contact form.
            $(form).submit(function(e) {
                // Stop the browser from submitting the form.
                e.preventDefault();
                // Serialize the form data.
                var formData = $(form).serialize();
                // Submit the form using AJAX.
                $.ajax({
                type: 'POST',
                        url: $(form).attr('action'),
                        data: formData
                })
                .done(function(response) {
                    // Make sure that the formMessages div has the 'success' class.
                    $(formMessages).removeClass('error');
                    $(formMessages).addClass('success');

                    // Set the message text.
                    $(formMessages).text(response);
                })
                .fail(function(data) {
                    // Make sure that the formMessages div has the 'error' class.
                    $(formMessages).removeClass('success');
                    $(formMessages).addClass('error');
                    // Set the message text.
                    if (data.responseText !== '') {
                        $(formMessages).text(data.responseText);
                    } else {
                        $(formMessages).text('An error occured and your message could not be sent.');
                    }
                });
            });

        });
</script>


<?php
} else {
?>
<header class="jumbotron subhead" id="dashboard">
    <div class="hero-unit">
        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title"> Welcome </h3>
        <?php
        //only the Agency Admin see this
        if ($this->session->get('auth-identity')['profile'] == 'Agency Admin' ||
        $this->session->get('auth-identity')['profile'] == 'Super Admin') {
        ?>
        <div><a href="/location/create">Click here</a> to set up a location to get started.</div>
        <?php
        } else {
        ?>
        <div>To get started: Have the Agency Admin add you to a location.</div>
        <?php
        }
        ?>
    </div>
</header>
<?php
} // end checking for a location
?>
