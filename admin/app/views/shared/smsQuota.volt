<div class="col-md-5 col-sm-5">
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Subscriptions</h3>
    <!-- END PAGE TITLE-->
</div>
<?php if ($this->view->showSmsQuota) {  if ($this->view->smsQuotaParams['hasUpgrade']) { ?>
<!-- <div class="col-md-7 col-sm-7">
    <div class="sms-chart-wrapper">
        <div class="title">SMS Messages Sent</div>
        <div class="bar-wrapper">
            <div class="bar-background"></div>
            <div class="bar-filled" style="width: <?=$percent?>%;"></div>
            <div class="bar-percent" style="padding-left: <?=$percent?>%;"><?=$percent?>%</div>
            <div class="bar-number" style="margin-left: <?=$percent?>%;">
                <div class="ball"><?=$sms_sent_this_month_total?></div>
                <div class="bar-text" <?=($percent>60?'style="display: none;"':'')?>>This Month</div>
            </div>
        </div>
        <div class="end-title"><?=$total_sms_month?><br /><span class="goal">Allowed</span></div>
    </div>
</div> -->
<?php } else { ?>
<div class="col-md-7 col-sm-7">
    <div class="sms-chart-wrapper">
        <div class="title">SMS Messages Sent</div>
        <div class="bar-wrapper">
            <div class="bar-background"></div>
            <div class="bar-filled" style="width: <?=$this->view->smsQuotaParams['percent']?>%;"></div>
            <div class="bar-percent" style="padding-left: <?=$this->view->smsQuotaParams['percent']?>%;"><?=$this->view->smsQuotaParams['percent']?>%</div>
            <div class="bar-number" style="margin-left: <?=$this->view->smsQuotaParams['percent']?>%;">
                <div class="ball"><?=$this->view->smsQuotaParams['smsSentThisMonth']?></div>
                <div class="bar-text" <?=$this->view->showBarText?>>This Month</div>
            </div>
        </div>
        <div class="end-title"><?=$this->view->smsQuotaParams['totalSmsNeeded']?><br /><span class="goal">Goal</span></div>
    </div>
</div>
<?php } }?>
