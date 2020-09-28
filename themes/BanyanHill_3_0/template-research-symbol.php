<?php
/*
Template Name: Research Symbol
*/

parse_str($_SERVER['QUERY_STRING'], $query);

$symbol = empty( $query['id'] ) ? $query['tvwidgetsymbol'] : $query['id'];

// Use TSLA if no ticker is passed
$symbol = empty( $symbol ) ? 'TSLA' : strtoupper($symbol);

if (strpos($symbol, ':') !== false) {
	$symbol = explode(':', $symbol)[1];
}

$symbol = preg_replace('/[^\w.-]/', '', $symbol);
if($_GET['checkSymbol'] == 'true') {
	$check_symbol = 'true';
} else {
	$check_symbol = 'false';
}

//https://stackoverflow.com/a/17908219
add_filter( 'wpseo_title', function( $title ) use ( $symbol ) {
		return $symbol . ' Latest Quotes, Charts & Stock Information - Banyan Hill Publishing';
}, 10, 1 );

add_filter( 'wpseo_canonical', '__return_false' );

get_header();

wp_enqueue_style( 'research-symbol', get_stylesheet_directory_uri() . '/css/research-symbol.css?date=2019-12-19-500' );

wp_enqueue_script( 'jquery-ui-autocomplete' );

wp_enqueue_script('jquery-modal', (get_stylesheet_directory_uri() . '/js/jquery.modal.min.js'));
wp_enqueue_style('modal-styles', (get_stylesheet_directory_uri() . '/css/modal-styles.css'));

wp_enqueue_script( 'bh-stock-ticker', get_stylesheet_directory_uri() . '/js/stock-ticker.js?date=2020-02-18-10', array( 'jquery', 'jquery-ui-autocomplete' ), true );
wp_localize_script( 'bh-stock-ticker', 'bhTicker', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'topstock' => bh_stock_ticker_top(),
		'checkSymbol' => $check_symbol,
		'symbol' => $symbol,
	)
);
?>
<div id="modal-not-found" class="modal" style="">
	<div id="bh-modal-header" style="border-bottom: 1px solid #d3d3d3;">
		<h3 style="text-align:center;">Company not found</h3>
	</div>
	<div id="bh-modal-content">
		<p style="text-align:center;">Please use the Find By Symbol to continue your search <br> or <br> Select from suggestions below</p>
		<div style="text-align:center">
			<div class="suggested-symbol-search-item" style="width:15%;"><strong>Symbol</strong></div>
			<div class="suggested-symbol-search-item" style="width:65%;"><strong>Company</strong></div>
			<div class="suggested-symbol-search-item" style="width:20%;"><strong>Cap</strong></div>
		</div>
		<?php
			$stocks = bh_stock_ticker_top_five();
			foreach ($stocks as $stock) { ?>
				<div class="suggested-symbol-search" data-symbol="<?php echo $stock['symbol']; ?>">
					<div class="suggested-symbol-search-item" style="width:15%;"><?php echo $stock['symbol']; ?></div>
					<div class="suggested-symbol-search-item" style="width:65%;"><?php echo $stock['name']; ?></div>
					<div class="suggested-symbol-search-item" style="width:20%;"><?php echo $stock['marketcap']; ?></div>
				</div>
		<?php } ?>
		<div style="text-align: center; border-top: 1px solid #d3d3d3; margin-top: 5pt; padding-top: 5pt;">
			<button class="form-control" id="modal-not-found-close" style="margin-top: 5pt;">Continue</button>
		</div>
	</div>
</div>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div class="et_pb_extra_column_main">
				<?php
				do_action( 'et_before_post' );
				if ( function_exists('yoast_breadcrumb') ) {
					$seo_bc = yoast_breadcrumb('<p id="breadcrumbs">','</p>', false);
					echo str_replace ( 'Symbol', $symbol, $seo_bc );
				} ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?>>
					<div class="post-header"></div>
					<div class="post-wrap">
						<?php et_builder_set_post_type(); ?>
						<?php the_content(); ?>
						<div id="research_container">
							<div class="nav-container-research" role="navigation">
								<div id="research_nav" class="research-nav">
									<!--span data-scroll="widget_RTC">Advanced Chart</span>
									<span data-scroll="widget_Profile">Profile</span>
									<span data-scroll="widget_Fundamentals">Financials</span>
									<span data-scroll="latest">Latest Insights</span-->
									<span id="symbol_Search" class="search">
										<form role="search" action="">
											<input placeholder="Find by Symbol » <?php echo $symbol; ?>" id="find-by-symbol">
											<button class="symbol-search-submit"></button>
										</form>
									</span>
								</div>
							</div>
							<div id="widget_Info">
								<iframe scrolling="no" allowtransparency="true" frameborder="0" src="https://s.tradingview.com/embed-widget/symbol-info/?locale=en&amp;symbol=<?php echo $symbol; ?>#%7B%22symbol%22%3A%22<?php echo $symbol; ?>%22%2C%22width%22%3A%22100%25%22%2C%22colorTheme%22%3A%22light%22%2C%22isTransparent%22%3Afalse%2C%22largeChartUrl%22%3A%22https%3A%2F%2Fbanyanhill.com%2Fresearch%2Fsymbol%2F%22%2C%22height%22%3A206%2C%22utm_medium%22%3A%22widget_new%22%2C%22utm_campaign%22%3A%22symbol-info%22%7D" style="box-sizing: border-box; height: <?php echo wp_is_mobile() ? '265px' : '174px' ?>; width: 100%;"></iframe>
							</div>
							<div id="widget_RTC">
								<!-- TradingView Widget BEGIN -->
								<div class="tradingview-widget-container">
									<iframe id="tradingview_a753a" class="loading" data-src="https://s.tradingview.com/widgetembed/?frameElementId=tradingview_a753a&amp;symbol=<?php echo $symbol; ?>&amp;interval=D&amp;saveimage=0&amp;toolbarbg=f1f3f6&amp;studies=%5B%5D&amp;theme=Light&amp;style=1&amp;timezone=Etc%2FUTC&amp;studies_overrides=%7B%7D&amp;overrides=%7B%7D&amp;enabled_features=%5B%5D&amp;disabled_features=%5B%5D&amp;locale=en&amp;utm_medium=widget&amp;utm_campaign=chart&amp;utm_term=<?php echo $symbol; ?>" style="width: 99.9%; height: 100%; margin: 0 !important; padding: 0 !important;" frameborder="0" allowtransparency="true" scrolling="no" allowfullscreen=""></iframe>
								</div>
								<!-- TradingView Widget END -->
							</div>
							<div class="col-1-of-2">
								<div id="widget_Profile">
									<?php
	//Parse iframe as DOM
	//https://stackoverflow.com/a/5126994
	//									$html_select = file_get_contents( 'https://s.tradingview.com/embed-widget/symbol-profile/?locale=en&symbol=' . $symbol . '#%7B%22width%22%3A%22100%25%22%2C%22height%22%3A%22100%25%22%2C%22colorTheme%22%3A%22light%22%2C%22isTransparent%22%3Afalse%2C%22utm_medium%22%3A%22widget%22%2C%22utm_campaign%22%3A%22symbol-profile%22%7D' );
	//
	//									$dom = new DOMDocument();
	//									$dom->loadHTML($html_select);
	//									$xpath = new DOMXpath($dom);
	//									$result = $xpath->query('//div[@class="tv-symbol-profile__description"]');
	//
	//									if ($result->length > 0) {
	//    									echo $result->item(0)->nodeValue;
	//									}
									?>

									<!-- TradingView Widget BEGIN -->
									<div class="tradingview-widget-container">
									  <div class="tradingview-widget-container__widget"></div>
									  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-symbol-profile.js" async>
									  {
									  "symbol": "<?php echo $symbol; ?>",
									  "width": "100%",
									  "height": "100%",
									  "colorTheme": "light",
									  "largeChartUrl": "https://banyanhill.com/research/symbol/",
									  "isTransparent": false,
									  "locale": "en"
									}
									  </script>
									</div>
									<!-- TradingView Widget END -->
								</div>
							</div>
							<div class="col-1-of-2">
								<div id="widget_Fundamentals">
									<!-- TradingView Widget BEGIN -->
									<div class="tradingview-widget-container">
									  <div class="tradingview-widget-container__widget"></div>
									  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-financials.js" async>
									  {
									  "symbol": "<?php echo $symbol; ?>",
									  "colorTheme": "light",
									  "isTransparent": false,
									  "largeChartUrl": "https://banyanhill.com/research/symbol/",
									  "displayMode": "regular",
									  "width": "100%",
									  "height": "100%",
									  "locale": "en"
									}
									  </script>
									</div>
									<!-- TradingView Widget END -->
								</div>
							</div>
							<div class="tradingview-widget-copyright col-1-of-1"><a href="https://www.tradingview.com/symbols/<?php echo $symbol; ?>/" rel="noopener" target="_blank"><span class="blue-text"><?php echo $symbol; ?> charts &amp; data provided</span></a> by TradingView</div>
						</div>
					</div>
				</article>
				<div id="latest">
					<h2>Latest Insights on <?php echo $symbol; ?></h2>
					<?php
						// Search Results
						echo do_shortcode( '[display-posts s="' . $symbol . '" posts_per_page="5" pagination="true" image_size="extra-image-small" include_excerpt="true" include_excerpt_dash="false" include_author="true" include_date="true" date_format="F j, Y" category_display="true" category_label="" wrapper="div" wrapper_class="symbol-grid-layout"]' );
					?>
				</div>
			</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->
			<script type="text/javascript">
			jQuery('span[data-scroll]').on('click', function(){
				var yOffset = 0;
				if (window.pageYOffset === 0) {
					yOffset = 146;
				}

				jQuery('html, body').animate({scrollTop: jQuery('#' + jQuery(this).data('scroll')).offset().top - 46 - yOffset});
			});
			</script>
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer();
