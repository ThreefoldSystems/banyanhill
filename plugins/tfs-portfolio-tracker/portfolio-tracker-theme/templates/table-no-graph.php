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
    <div class="tfs-portfolio">
        <h2 class="tfs-portfolio-header">Portfolio</h2>
        <table class="tfs-portfolio-table display compact" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th class="portfolio-ticker">Ticker</th>
                <th class="portfolio-buy-price portfolio-left-align">Buy Price</th>
                <th class="portfolio-last-price portfolio-left-align">Last Price</th>
                <th class="portfolio-return portfolio-left-align">Return</th>                
                <th class="portfolio-recommendation desktop">Recommendation</th>
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
								<span>
									<?php echo isset($portfolio->OpenPrice) ?
										$portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''; ?>
								</span>
                                <span class="portfolio-sub-span"><?php echo isset($opendate) ? $opendate : ''; ?></span>
                            </td>
                            <td class="portfolio-last-price  portfolio-left-align">
                                <?php echo isset($portfolio->CurrentPrice) ?
                                    $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''; ?>
                            </td>
                            
                            <td class="portfolio-return portfolio-left-align portfolio-return-<?php echo ($portfolio->OverallGain > 0 ? 'positive' : 'negative'); ?>">
                                <?php echo floatval(number_format($portfolio->OverallGain * 100, 2, '.', '')) . '%'; ?>
                            </td>

                            <td class="portfolio-recommendation desktop">
                                <?php echo $portfolio->PositionSetting->Text1; ?>
                            </td>
                        </tr>				
						<tr class="mobile">
                            <td class="portfolio-recommendation mobile" colspan="4">
								<span class="portfolio-bold">Recommendation</span>
                                <span class="full-recommendation"><?php echo $portfolio->PositionSetting->Text1; ?></span>
                            </td>						
						</tr>
                    <?php endif;
                endforeach;
            endif; ?>
            </tbody>
            <?php //if ( !empty( $graph_data ) ) { ?>
			<!--
                <tfoot>
                <tr>
                    <td colspan="100%">
                        Total Portfolio Return for <?php //echo date('F', strtotime(end(array_keys($graph_data)))); ?>:
                        <span class="portfolio-bold">
                    <?php //echo end($graph_data) . '%'; ?>
                </span>
                    </td>
                </tr>
                </tfoot>
			-->
            <?php //} ?>
        </table>
    </div>