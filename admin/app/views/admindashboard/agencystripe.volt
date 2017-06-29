<?php $objValidationService = new \Vokuro\Services\StripeValidationService(); ?>
<style type="text/css">
    .title {
        width: 100% !important;
    }
</style>
<div class="row">
    <div class="col-md-5 col-sm-5">
        <h3 class="page-title">Stripe And System Comparison</h3>
    </div>
</div>
<div class="portlet light bordered dashboard-panel">
    <div class="table-header">
        <div class="title">Agency List (Last Processed {{ LastProcessed }})</div>
    </div>

    <form method="POST" action="/admindashboard/processstripe">
        <div class="panel-default toggle panelMove panelClose panelRefresh" id="locationlist">
            <div class="customdatatable-wrapper">
                <table class="customdatatable table table-striped table-bordered table-responsive table_user" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Agency ID</th>
                            <th>Agency</th>
                            <th>Stripe Customer ID</th>
                            <th>System Customer ID</th>
                            <th>Stripe Subscription ID</th>
                            <th>System Subscription ID</th>
                            <th>Agency Status</th>
                            <th>Stripe Status</th>
                            <th>Actions</th>
                            <th>Approve</th>
                            <th>Processing Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for objValidation in tValidationInfo %}
                        <?php
                            $Disabled = ($objValidation['ProcessingStatus'] == 'Processed' || $objValidation['Action'] == 0) ? 'disabled checked' : '';
                        ?>
                        <tr>
                            <td>{{ objValidation['AgencyID'] }}</td>
                            <td>{{ objValidation['AgencyName'] }}</td>
                            <td>{{ objValidation['StripeCustomerID'] }}</td>
                            <td>{{ objValidation['AgencyCustomerID'] }}</td>
                            <td>{{ objValidation['StripeSubscriptionID'] }}</td>
                            <td>{{ objValidation['AgencySubscriptionID'] }}</td>
                            <td>{{ objValidation['AgencyStatus'] }}</td>
                            <td>{{ objValidation['StripeStatus'] }}</td>
                            <td><?=$objValidationService->GenerateProcessString($objValidation['Action']); ?></td>
                            <td><input type="checkbox" name="approve_{{ objValidation['ValidationID'] }}" <?=$Disabled; ?> /></td>
                            <td>{{ objValidation['ProcessingStatus'] }}</td>
                        </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
        <input type="submit" class="btn btnLink btnSecondary" value="Process Approved Rows" />
    </form>
</div>