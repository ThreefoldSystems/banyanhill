<?php if ( !empty( $return_values ) ) {
    $graph_data = array (
        'JAN 2019' => null,
        'FEB 2019' => null,
        'MAR 2019' => null,
        'APR 2019' => null,
        'MAY 2019' => null,
        'JUN 2019' => null,
        'JUL 2019' => null,
        'AUG 2019' => null,
        'SEP 2019' => null,
        'OCT 2019' => '0.04',
        'NOV 2019' => '0.01'
    );

    if( !empty( $dynamic_graph_data ) ) {
        foreach( $dynamic_graph_data as $key => $value ) {
            $graph_data[$key] =
                floatval(number_format($value * 100, 2, '.', ''));
        }
    }
} ?>
    <div id="tfs-portfolio">
        <h2 id="tfs-portfolio-header">Portfolio</h2>
        <table id="tfs-portfolio-table" class="display compact" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="portfolio-ticker">Ticker</th>
                <th class="portfolio-buy-price portfolio-right-align">Buy Price</th>
                <th class="portfolio-last-price portfolio-right-align">Last Price</th>
                <th class="portfolio-recommendation">Recommendation</th>
                <th class="portfolio-return portfolio-right-align">Return</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(!is_wp_error($content['portfolios'])) :
                foreach($content['portfolios'] as $portfolio) :
                    if ( !empty($portfolio->PositionSetting->Published) && $portfolio->PositionSetting->Published === true &&
                        !empty($portfolio->TradeStatus) && $portfolio->TradeStatus === 1 ) :
                        if (isset($portfolio->OpenDate))
                            $opendate = date("M j, Y", strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T'))));
                        ?>
                        <tr>
                            <td class="portfolio-ticker">
                                <span class="portfolio-bold"><?php echo $portfolio->Symbol; ?></span>
                                <span class="portfolio-sub-span"><?php echo $portfolio->Name; ?></span>
                            </td>
                            <td class="portfolio-buy-price portfolio-left-align">
                    <span class="portfolio-semi-bold">
                        <?php echo isset($portfolio->OpenPrice) ?
                            $portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''; ?>
                    </span>
                                <span class="portfolio-sub-span"><?php echo isset($opendate) ? $opendate : ''; ?></span>
                            </td>
                            <td class="portfolio-last-price portfolio-semi-bold portfolio-right-align">
                                <?php echo isset($portfolio->CurrentPrice) ?
                                    $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''; ?>
                            </td>
                            <td class="portfolio-recommendation">
                                <?php echo $portfolio->PositionSetting->Text1; ?>
                            </td>
                            <td class="portfolio-return portfolio-right-align portfolio-return-<?php echo ($portfolio->OverallGain > 0 ? 'positive' : 'negative'); ?>">
                                <?php echo floatval(number_format($portfolio->OverallGain * 100, 2, '.', '')) . '%'; ?>
                            </td>
                        </tr>
                    <?php endif;
                endforeach;
            endif; ?>
            </tbody>
            <?php if ( !empty( $graph_data ) ) { ?>
                <tfoot>
                <tr>
                    <td colspan="100%">
                        Total Portfolio Return for <?php echo date('F', strtotime(end(array_keys($graph_data)))); ?>:
                        <span class="portfolio-bold">
                    <?php echo end($graph_data) . '%'; ?>
                </span>
                    </td>
                </tr>
                </tfoot>
            <?php } ?>
        </table>
    </div>

<?php if ( !empty( $return_values ) ) { ?>
    <div id="tfs-portfolio-graph">
        <h2 id="tfs-portfolio-header">Performance</h2>
        <div style="width:100%; height: 400px;">
            <canvas id="canvas"></canvas>
        </div>
    </div>

    <?php $graph_data = array_slice($graph_data, -12, 12, true) ?>
    <script>
        var config = {
            type: 'line',
            data: {
                labels: [
                    <?php foreach( $graph_data as $key => $value ) {
                    echo "'" . $key . "',";
                } ?>
                ],
                datasets: [{
                    backgroundColor: '#fff',
                    borderColor: '#000',
                    pointRadius: 6,
                    pointBorderWidth: 2,
                    pointHoverRadius: 10,
                    pointHoverBorderWidth: 8,
                    pointHoverBackgroundColor: '#fff',
                    steppedLine: false,
                    data: [
                        <?php foreach( $graph_data as $key => $value ) {
                        echo $value . ",";
                    } ?>
                    ],
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                legend: {
                    display: false,
                    labels: {
                        fontSize: 18
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(value){return value+ "%"},
                            padding: 20,
                            min: -2,
                            max: 2
                        },
                        scaleLabel: {
                            display: false
                        },
                        gridLines: {
                            borderDash: [10,10],
                            zeroLineBorderDash: [10,10],
                            color: 'rgba(48,49,51,0.2)',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            padding: 20
                        },
                        gridLines: {
                            borderDash: [10, 10],
                            zeroLineBorderDash: [10,10],
                            color: 'rgba(48, 49, 51, 0.2)',
                            drawBorder: false
                        }
                    }]
                },
                tooltips: {
                    custom: function(tooltip) {
                        tooltip.displayColors = false;
                    },
                    callbacks: {
                        label: function(tooltipItem) {
                            return Number(tooltipItem.yLabel) + "% return";
                        },
                        title: function() {
                            return false;
                        }
                    },
                    legendColorBackground: 'transparent'
                }
            }
        };

        window.onload = function() {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myLine = new Chart(ctx, config);
        };

    </script>
<?php } ?>