<head>
    <style type="text/css">
        body {
            background: white;
        }
        .even_row {
            background-color: rgb(239,239,239);
        }
        .odd_row {
            background-color: rgb(250,250,250);
        }
        .header_row {
            background-color: rgb(239,239,239);
        }

        .container {
            width: 1024px;
            border: 1px solid black;
            margin-left: auto;
            margin-right: auto;
        }

        .header {
            width: 1024px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer {
            width: 1024px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .logo {
            float: left;
            display: block;
        }

        .title {
            float: right;
        }

        .employee_table {
            margin-top: 20px;
            margin-left: 20px;
            margin-bottom: 20px;
            margin-right: 20px;
        }

        .table_header {
            margin-left: 20px;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="http://<?=$objBusiness->custom_domain; ?>.getmobilereviews.com/img/agency_logos/<?=$objBusiness->logo_path;?>" />
        <div class="title"><h3><?=$objBusiness->name; ?></h3></div>
    </div>
    <div style="clear: both;"></div>
    <div class="container">
        <div class="table_header">
            <h4><?=date('F'); ?> Employee Ranking Report<br />As Of <?=date("m/d/Y"); ?></h4><br />
        </div>
        <table class="employee_table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Employee</th>
                    <th>Total Feedback</th>
                    <th>Average Satisfaction</th>
                </tr>
            </thead>
            <tbody>
            <?php $Count = 1; ?>
            {% for Employee in dbEmployees %}
                <?php
                    switch($Count) {
                        case 1:
                            $Icon = "<img src='" . $objBusiness->custom_domain . ".getmobilereviews.com/img/gold_medal.png' />";
                            break;
                        case 2:
                                $Icon = "<img src='" . $objBusiness->custom_domain . ".getmobilereviews.com/img/silver_medal.png' />";
                            break;
                        case 3:
                                $Icon = "<img src='" . $objBusiness->custom_domain . ".getmobilereviews.com/img/bronze_medal.png' />";
                            break;
                        default:
                            // Fully aware this only works up to 110 employees.  GARY_TODO:  Fix this if it becomes an issue.
                            $Icon = "{$Count}";
                            if($Count > 20 && $Count % 10 == 1)
                                $Icon .= "st";
                            elseif($Count > 20 && $Count % 10 == 2)
                                $Icon .= "st";
                            elseif($Count > 20 && $Count % 10 == 3)
                                $Icon .= "st";
                            else
                                $Icon .= "th";
                            break;
                    }
                ?>

                <tr>
                    <td><?=$Icon; ?></td>
                    <td><?=$Employee->name; ?></td>
                    <td><?=$Employee->sms_sent_all_time ?: 0; ?></td>
                    <td><?=$Employee->avg_feedback ?: 0; ?></td>
                </tr>
                <?php $Count++; ?>
            {% endfor %}
            </tbody>
        </table>
    </div>
    <div class="footer">
        <p><b><?=$objBusiness->name; ?></b> | <a href="<?=$objBusiness->website; ?>"><?=$objBusiness->website; ?></a> | Like us on Facebook</p>
        <p><?=$objBusiness->address; ?> <?=$objBusiness->address2; ?>, <?=$objBusiness->state_province; ?>, <?=$objBusiness->postal_code; ?></p>
        <p><?=$objBusiness->phone; ?></p>
        <br />
        Powered by <?=$objAgency->name; ?>
    </div>
</body>