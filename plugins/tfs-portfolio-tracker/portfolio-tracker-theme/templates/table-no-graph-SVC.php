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
}

if( !is_wp_error($content['portfolios']) ) {
	$portfolio_groups = array();
	$portfolio_returns = array();
	$portfolio_hold = array();
	$portfolio_last_update = '';
	$portfolio_group_index = 0;

	foreach($content['portfolios'] as $portfolio) {
		$trade_status = $portfolio->TradeStatus ? $portfolio->TradeStatus : ($portfolio->IsOpened ? 1 : 2);
		
		if ($portfolio->TradeGroup->{'$id'}) {
			$portfolio_groups[$portfolio->TradeGroup->{'$id'}] = array();
							
			array_push($portfolio_groups[$portfolio->TradeGroup->{'$id'}], $portfolio);
		} else {
			if ( !empty($portfolio->PositionSetting->Published) && $portfolio->PositionSetting->Published === true &&
				 !empty($portfolio->TradeStatus) && $portfolio->TradeStatus === $trade_status ) {			
				array_push($portfolio_groups[$portfolio->TradeGroup->{'$ref'}], $portfolio);
			}
		}	
		
		if ( !empty($portfolio->PositionSetting->Published) && $portfolio->PositionSetting->Published === true &&
			 !empty($portfolio->TradeStatus) && $portfolio->TradeStatus === $trade_status ) {
			array_push($portfolio_returns, floatval(number_format($portfolio->OverallGain * 100, 2, '.', '')));
			array_push($portfolio_hold, $portfolio->HoldPeriod);
		}
	}	
		
	//Sort groups by Group Order 
	usort($portfolio_groups, function($item1, $item2){
		if( $item1[0]->TradeGroup->TradeGroupOrder == $item2[0]->TradeGroup->TradeGroupOrder ) return 0;
		return $item1[0]->TradeGroup->TradeGroupOrder < $item2[0]->TradeGroup->TradeGroupOrder ? -1 : 1;
	});
		
	$portfolio_last_update = date("F j, Y", strtotime(substr($portfolio_groups[0][0]->LastUpdateDate, 0, strpos($portfolio_groups[0][0]->LastUpdateDate, 'T'))));	
}
?>
<div class="tfs-portfolio-container">
	<div id="tfs-portfolio-summary">
		<h5><?php echo $content['config']['name']; ?> </h5>
		<div><span>Positions: <?php echo count($portfolio_returns); ?></span> <span class="tfs-portfolio-summary-spacer">|</span> <span>Average Hold: <?php echo floor(array_sum($portfolio_hold) / count($content['portfolios'])) . ' Days' ?></span></div>
		<div>Last Update: <?php echo $portfolio_last_update; ?></div>
	</div>
	<ul class="nav nav-tabs nav-tfs-portfolio" role="tablist">
	<?php foreach($portfolio_groups as $portfolio_group) {
		$published_items = array();
		//TODO: avoid nested loop if possible
		foreach($portfolio_group as $item) {
			if ($item->PositionSetting->Published) {
				array_push($published_items, $item->PositionSetting->Published);
			}
		}
	
		echo '<li><a href="#' . $content['config']['id'] . '-portfolio-group-' . $portfolio_group[0]->TradeGroup->{'$id'} . '" data-toggle="tab" >' . $portfolio_group[0]->TradeGroup->Name . ' <span>(' . count($published_items) . ')</span></a></li>';
	} ?>
	</ul>
	<div class="tfs-portfolio">
	<?php foreach($portfolio_groups as $portfolio_group) { 
		$trade_status = $portfolio_group[0]->TradeStatus ? $portfolio_group[0]->TradeStatus : ($portfolio_group[0]->IsOpened ? 1 : 2);
		$trade_group_data = $portfolio_group[0]->TradeGroup;
	
		//Sort Sold/Closed group by close date
		if ($trade_status === 2) {
			usort($portfolio_group, function($item1, $item2){	
				return $item2->CloseDate <=> $item1->CloseDate;
			});
		}
	
	?>		
		<table id="<?php echo $content['config']['id']; ?>-portfolio-group-<?php echo $trade_group_data->{'$id'} ?>" class="tfs-portfolio-table" data-table-order="<?php echo $trade_group_data->TradeGroupOrder; ?>" data-table-group-id="<?php echo $trade_group_data->{'$id'} ?>" data-table-group-index="<?php echo $portfolio_group_index; ?>" style="width: 100%;">
			<thead>
				<tr>
					<th class="portfolio-security">Symbol<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
				<?php if ( !cf_is_mobile() ) { ?>					
					<th class="portfolio-buy-date portfolio-right-align">Open<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<th class="portfolio-last-price portfolio-right-align"><?php echo $trade_status === 1 ? 'Current' : 'Close'; ?><span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<th class="portfolio-dividend-yield portfolio-right-align">Dividend Yield<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<th class="portfolio-dividend-yield-2 portfolio-right-align">Yield on Cost<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<?php if ( $portfolio_group[0]->TradeGroup->{'$id'} === '516' ) { ?>
					<th class="portfolio-type">Type<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<?php } ?>
				<?php } ?>
					<th class="portfolio-return portfolio-right-align">Adjusted<br />Total Return<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
					<th class="portfolio-advice">Advice<span class="portfolio-sort-icon"><i class="fa fa-fw fa-sort"></i></span></th>
				<?php if ( cf_is_mobile() ) { ?>	
					<th></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php foreach($portfolio_group as $portfolio) {
				if ( !empty($portfolio->PositionSetting->Published) && $portfolio->PositionSetting->Published === true &&
					!empty($portfolio->TradeStatus) && $portfolio->TradeStatus === $trade_status ) {
					
					if ( !cf_is_mobile() ) { ?>
					<tr class="portfolio-main-row">
						<!-- TICKER & SECURITY -->
						<th class="portfolio-ticker">
							<div class="portfolio-bold portfolio-security" data-pid="<?php echo $content['config']['id']; ?>" data-symbol="<?php echo $portfolio->Symbol; ?>"><a class="portfolio-symbol-link" href="https://banyanhill.com/stock/symbol/?id=<?php echo explode('.', $portfolio->Symbol)[0]; ?>&checkSymbol=false" target="_blank"><?php echo $portfolio->Symbol; ?></a>
								<span class="portfolio-column-note">
								<?php if (strlen($portfolio->Name) > 20) { ?>
									<span class="portfolio-symbol-info-widget" data-security-fullname="<?php echo $portfolio->Name; ?>"><?php echo substr($portfolio->Name, 0, 20) . ' ...'; ?><span class="portfolio-symbol-name"><?php echo $portfolio->Name; ?></span></span>
								<?php } else { 
									echo $portfolio->Name;
								} ?>
								</span>
							</div>
						</th>						
						<!-- OPEN -->
						<td class="portfolio-buy-date portfolio-right-align" data-sort="<?php echo $portfolio->OpenPrice; ?>">
							<span><?php echo isset($portfolio->OpenPrice) ? $portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''; ?></span>
							<?php if ( !empty($portfolio->PositionSetting->OpenDateLink) ) { ?>
							<a href="<?php echo $portfolio->PositionSetting->OpenDateLink; ?>" target="_blank">
							<?php } ?>
							<span class="portfolio-column-note"><?php echo isset($portfolio->OpenDate) ? date("m/d/Y", strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T')))) : ''; ?></span>
							<?php if ( !empty($portfolio->PositionSetting->OpenDateLink) ) { ?>
							</a>
							<?php } ?>
						</td>
						<?php if ( $trade_status === 1 ) { ?>
						<!-- LAST PRICE -->														
						<td class="portfolio-last-price portfolio-right-align" data-sort="<?php echo $portfolio->CurrentPrice; ?>">
							<span><?php echo isset($portfolio->CurrentPrice) ? $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''; ?></span>						
						</td>
						<?php } else { ?>
						<!-- CLOSE PRICE -->
						<td class="portfolio-last-price portfolio-right-align" data-sort="<?php echo $portfolio->ClosePrice; ?>">
							<span><?php echo isset($portfolio->ClosePrice) ? $portfolio->CurrencySygn . number_format($portfolio->ClosePrice, 2, '.', '') : ''; ?></span>
							<?php if ( !empty($portfolio->PositionSetting->CloseDateLink) ) { ?>
							<a href="<?php echo $portfolio->PositionSetting->CloseDateLink; ?>" target="_blank">
							<?php } ?>
							<span class="portfolio-column-note"><?php echo isset($portfolio->CloseDate) ? date("m/d/Y", strtotime(substr($portfolio->CloseDate, 0, strpos($portfolio->CloseDate, 'T')))) : ''; ?></span>
							<?php if ( !empty($portfolio->PositionSetting->CloseDateLink) ) { ?>
							</a>
							<?php } ?>
						</td>						
						<?php } ?>
						<!-- DIVIDEND YIELD -->														
						<td class="portfolio-dividend-yield portfolio-right-align">
							<span><?php echo isset($portfolio->DividendYield) ? floatval(number_format($portfolio->DividendYield, 2, '.', '')) . '%' : ''; ?></span>
						</td>	
						<!-- DIVIDEND YIELD 2 -->														
						<td class="portfolio-dividend-yield portfolio-right-align">
							<span><?php echo isset($portfolio->Subtrades[0]->YieldOnCost) ? floatval(number_format($portfolio->Subtrades[0]->YieldOnCost * 100, 2, '.', '')) . '%' : ''; ?></span>
						</td>						
						<?php if ( $portfolio->TradeGroup->{'$id'} === '516' || $portfolio->TradeGroup->{'$ref'} === '516' ) { ?>
						<td class="portfolio-type">
							<span><?php echo $portfolio->PositionSetting->Text3; ?></span>
							<span class="portfolio-column-note">Risk Type: <span class="portfolio-bold"><?php echo $portfolio->PositionSetting->Text2; ?></span></span>
						</td>						
						<?php } ?>
						<!-- RETURN -->						
						<td class="portfolio-return portfolio-right-align portfolio-return-<?php echo ($portfolio->OverallGain > 0 ? 'positive' : 'negative'); ?>">
							<span><?php echo floatval(number_format($portfolio->OverallGain * 100, 2, '.', '')) . '%'; ?></span>
						</td>
						<!-- RECOMMENDATION -->
						<td class="portfolio-advice">
							<?php if (strlen($portfolio->PositionSetting->Text1) > 4) { ?>
								<?php echo trim(str_replace('.', '', substr($portfolio->PositionSetting->Text1, 0, 4))); ?>
								<span class="portfolio-column-note">
								<span class="portfolio-symbol-info-widget" data-security-fullname="<?php echo $portfolio->PositionSetting->Text1; ?>">Details<span class="portfolio-symbol-name left"><?php echo $portfolio->PositionSetting->Text1; ?></span></span></span>
							<?php } else { 
								echo $portfolio->PositionSetting->Text1;
							} ?>							
							<div class="portfolio-chart-container"></div>
						</td>						
					</tr>
				<?php 
					} else { 
					// Mobile Version
				?>
					<tr class="portfolio-main-row">
						<!-- TICKER & SECURITY -->
						<td class="portfolio-ticker">
							<div class="portfolio-bold portfolio-security" data-pid="<?php echo $content['config']['id']; ?>" data-symbol="<?php echo $portfolio->Symbol; ?>"><i class="fa fa-caret-right"></i><a class="portfolio-symbol-link" href="https://banyanhill.com/stock/symbol/?id=<?php echo explode('.', $portfolio->Symbol)[0]; ?>&checkSymbol=false" target="_blank"><?php echo $portfolio->Symbol; ?></a>
								<span class="portfolio-column-note">
								<?php if (strlen($portfolio->Name) > 10) { ?>
									<span class="portfolio-symbol-info-widget" data-security-fullname="<?php echo $portfolio->Name; ?>"><?php echo substr($portfolio->Name, 0, 10) . ' ...'; ?><span class="portfolio-symbol-name"><?php echo $portfolio->Name; ?></span></span>
								<?php } else { 
									echo $portfolio->Name;
								} ?>
								</span>
							</div>							
						</td>												
						<!-- RETURN -->
						<td class="portfolio-return portfolio-right-align portfolio-return-<?php echo ($portfolio->OverallGain > 0 ? 'positive' : 'negative'); ?>">
							<?php echo floatval(number_format($portfolio->OverallGain * 100, 2, '.', '')) . '%'; ?>
						</td>
						<td class="portfolio-advice">
						<?php if (strlen($portfolio->PositionSetting->Text1) > 4) { ?>
							<?php echo trim(str_replace('.', '', substr($portfolio->PositionSetting->Text1, 0, 4))); ?>
							<span class="portfolio-column-note">
							<span class="portfolio-symbol-info-widget" data-security-fullname="<?php echo $portfolio->PositionSetting->Text1; ?>">Details<span class="portfolio-symbol-name left"><?php echo $portfolio->PositionSetting->Text1; ?></span></span></span>
						<?php } else { 
							echo $portfolio->PositionSetting->Text1;
						} ?>						
						</td>
						<!-- RECOMMENDATION & DETAILS-->
						<td colspan="3">
							<table>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-buy-price">
										<span class="portfolio-row-title">Buy</span>
									<!-- BUY PRICE -->							
										<span class="portfolio-row-date portfolio-right-align">
											<?php echo isset($portfolio->OpenPrice) ? $portfolio->CurrencySygn . number_format($portfolio->OpenPrice, 2, '.', '') : ''; ?>
											<?php if ( !empty($portfolio->PositionSetting->OpenDateLink) ) { ?>
											<a href="<?php echo $portfolio->PositionSetting->OpenDateLink; ?>" target="_blank">
											<?php } ?>
											<span class="portfolio-column-note"><?php echo isset($portfolio->OpenDate) ? date("m/d/Y", strtotime(substr($portfolio->OpenDate, 0, strpos($portfolio->OpenDate, 'T')))) : ''; ?></span>
											<?php if ( !empty($portfolio->PositionSetting->OpenDateLink) ) { ?>
											</a>
											<?php } ?>
										</span>
									</td>								
								</tr>
								<?php if ( $trade_status === 1 ) { ?>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-last-price">
										<span class="portfolio-row-title">Current</span>
									<!-- LAST PRICE -->	
										<span class="portfolio-row-date portfolio-right-align">
											<?php echo isset($portfolio->CurrentPrice) ? $portfolio->CurrencySygn . number_format($portfolio->CurrentPrice, 2, '.', '') : ''; ?>
											<?php if ( !empty($portfolio->PositionSetting->CloseDateLink) ) { ?>
											<a href="<?php echo $portfolio->PositionSetting->CloseDateLink; ?>" target="_blank">
											<?php } ?>
											<span class="portfolio-column-note"><?php echo isset($portfolio->LastUpdateDate) ? date("m/d/Y", strtotime(substr($portfolio->LastUpdateDate, 0, strpos($portfolio->LastUpdateDate, 'T')))) : ''; ?></span>
											<?php if ( !empty($portfolio->PositionSetting->CloseDateLink) ) { ?>
											</a>
											<?php } ?>
										</span>										
									</td>								
								</tr>
								<?php }

								if ( isset($portfolio->ClosePrice) ) { ?>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-last-price">
										<span class="portfolio-row-title">Close</span>
									<!-- CLOSE PRICE -->	
										<span class="portfolio-row-date">
											<?php echo isset($portfolio->ClosePrice) ? $portfolio->CurrencySygn . number_format($portfolio->ClosePrice, 2, '.', '') : ''; ?>
											<span><?php echo isset($portfolio->CloseDate) ? date("m/d/Y", strtotime(substr($portfolio->CloseDate, 0, strpos($portfolio->CloseDate, 'T')))) : ''; ?></span>
										</span>										
									</td>								
								</tr>
								<?php }
								
								if ( isset($portfolio->DividendYield) ) {
								?>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-dividend-yield">
										<span class="portfolio-row-title">Dividend Yield</span>
									<!-- DIVIDEND YIELD -->	
										<span class="portfolio-row-date">
											<?php echo isset($portfolio->DividendYield) ? floatval(number_format($portfolio->DividendYield, 2, '.', '')) . '%' : ''; ?>
										</span>										
									</td>								
								</tr>
								<?php }
							  	if ( isset($portfolio->Subtrades[0]->YieldOnCost) ) { 
								?>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-dividend-yield">
										<span class="portfolio-row-title">Yield On Cost</span>
									<!-- YIELD ON COST -->	
										<span class="portfolio-row-date">
											<?php echo isset($portfolio->Subtrades[0]->YieldOnCost) ? floatval(number_format($portfolio->Subtrades[0]->YieldOnCost * 100, 2, '.', '')) . '%' : ''; ?>
										</span>										
									</td>								
								</tr>
								<?php  }
							  	if ( $portfolio->TradeGroup->{'$id'} === '516' || $portfolio->TradeGroup->{'$ref'} === '516' ) { ?>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-type">
										<span class="portfolio-row-title">Type</span>
										<span>
											<?php echo $portfolio->PositionSetting->Text3; ?>
										</span>
									</td>
								</tr>
								<tr class="portfolio-recommendation-row-mobile">
									<td class="portfolio-type">
										<span class="portfolio-row-title">Risk Level</span>
										<span>
											<?php echo $portfolio->PositionSetting->Text2; ?>
										</span>
									</td>
								</tr>								
								<?php } ?>								
							</table>							
						</td>						
					</tr>
				<?php }
				}
			}	
			?>
			</tbody>
		</table>
	<?php 
			$portfolio_group_index ++;
		} 
	?>		
	</div>
</div>
<script>
jQuery(document).ready(function() {	
	var dataTableArray = [];

	jQuery('.tfs-portfolio-table:visible').each(function(){		
		if (jQuery(this).data('tableGroupId') === 516) {
			dataTableArray.push(jQuery(this).DataTable({
				"paging": false,
				"info": false,
				"searching": false,
				"columnDefs": [ 
		<?php if ( !cf_is_mobile() ) { ?>				
					{
						"targets": [1,2],
						"type": "num-fmt"
					},
					{
						"targets": [-1,0],
						"width": "110px",
						"max-width": "110px"
					}					
		<?php } else { ?>					
					{
						"targets": [1],
						"type": "num-fmt"
					},
					{
						"targets": [ -1 ],
						"className": 'closed'
					}													   
		<?php } ?>
				],				
				"order": [ 	<?php echo !cf_is_mobile() ? '6' : '1' ?>, "desc" ],
				"initComplete": function( settings ) {
					jQuery('#'+settings.sTableId).parents('.tfs-portfolio').children(":first").addClass('active');
					jQuery('#'+settings.sTableId).parents('.tfs-portfolio').prev().find('li').first().addClass('active');
				}
				})
			);			
		} else {
			dataTableArray.push(jQuery(this).DataTable({
				"paging": false,
				"info": false,
				"searching": false,
				"columnDefs": [ 
		<?php if ( !cf_is_mobile() ) { ?>				
					{
						"targets": [1,2],
						"type": "num-fmt"
					},				
					{
						"targets": [-1,0],
						"width": "110px",
						"max-width": "110px"
					}
		<?php } else { ?>
					{
						"targets": [1],
						"type": "num-fmt"
					},
					{
						"targets": [ -1 ],
						"className": 'closed'
					}					
		<?php } ?>				
				],
				"order": [ 	<?php echo !cf_is_mobile() ? '5' : '1' ?>, "desc" ],
				"initComplete": function( settings ) {
					var portfolioContainer = jQuery('#'+settings.sTableId).parents('.tfs-portfolio');
					portfolioContainer.children(":first").addClass('active');
					portfolioContainer.prev().find('li').first().addClass('active');
	
					<?php if ( cf_is_mobile() ) { ?>
					if (portfolioContainer.parent().find('select').length > 0) return;

					portfolioContainer.prev().each(function() {
						var select = jQuery(document.createElement('select')).addClass('tfs-portfolio-select').insertBefore(jQuery(this).hide());
						jQuery(portfolioContainer.prev(), this).find('>li a').each(function() {
							var option = jQuery(document.createElement('option')).appendTo(select).val(this.hash).html(jQuery(this).html());
						});

						jQuery(select).change(function(){
							jQuery('.nav-tfs-portfolio a[href="' + jQuery(this).val() + '"]').click();
							
							return false;							
						});
					});	
					<?php } ?>	
				}
				})
			);			
		}
	});
	
	jQuery('.nav-tfs-portfolio a').on('click', function(){
		jQuery(jQuery(this).attr('href')).parents('.tfs-portfolio').prev().find('li').removeClass('active');
		jQuery(jQuery(this).attr('href')).parents('.tfs-portfolio').find('.dataTables_wrapper').removeClass('active');
		
		jQuery(this).parent().addClass('active');
		jQuery(jQuery(this).attr('href') + '_wrapper').addClass('active');
		jQuery(jQuery(this).attr('href')).DataTable().columns.adjust();

		return false;
	});	
});
</script>