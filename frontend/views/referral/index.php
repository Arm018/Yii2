<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $user */

$this->title = 'Referrals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-settings">
    <h2 class="mt-3">Referral Instruction</h2>
    <div class="alert alert-info">
        <strong>Referral Link Instructions:</strong>
        <p>
            Your referral link is a unique URL that you can share with others. When someone signs up using your link,
            you will receive a 10% commission on their purchases.
            Make sure to share your link widely to maximize your earnings!
        </p>
        <p>
            Your referral link: <strong><a href="<?= Html::encode($user->referral_link) ?>" target="_blank"><?= Html::encode($user->referral_link) ?></a></strong>
        </p>
    </div>

    <div id="container" style="height: 400px; width: 100%;"></div>

</div>

<?php
$this->registerJsFile('https://code.highcharts.com/highcharts.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs('
    Highcharts.chart("container", {
        chart: {
            type: "column"
        },
        title: {
            text: "Referral Amount Received Per Month in 2024",
            align: "left"
        },
        xAxis: {
            categories: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: "Amount Received ($)"
            }
        },
        tooltip: {
            headerFormat: "<span style=\'font-size:10px\'>{point.key}</span><table>",
            pointFormat: "<tr><td style=\'color:{series.color};padding:0\'>{series.name}: </td>" +
                "<td style=\'padding:0\'><b>${point.y:.2f}</b></td></tr>",
            footerFormat: "</table>",
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: "Referral Amount",
            data: ' . json_encode($referralData) . '
        }]
    });
');
?>

