<?php
/*
Template Name: Research
*/

//parse_str($_SERVER['QUERY_STRING'], $query);
//
//$symbol = empty( $query['id'] ) ? $query['tvwidgetsymbol'] : $query['id'];
//
//// Use AAPL if no ticker is passed
//$symbol = empty( $symbol ) ? 'AAPL' : strtoupper($symbol);
//
//if (strpos($symbol, ':') !== false) {
//	$symbol = explode(':', $symbol)[1];
//}fin
//
//add_filter( 'wpseo_title', function( $title ) {
//		return strtoupper($_GET['id']) . ' Latest Quotes, Charts & Stock Information - Banyan Hill Publishing';
//}, 10, 1 );

add_filter( 'wpseo_canonical', '__return_false' );

get_header();

wp_enqueue_style( 'research-symbol', get_stylesheet_directory_uri() . '/css/research-symbol.css?date=2019-12-19-500' );

wp_enqueue_script( 'jquery-ui-autocomplete' );
wp_enqueue_script( 'bh-stock-ticker', get_stylesheet_directory_uri() . '/js/stock-ticker.js?date=2020-02-18-10', array( 'jquery', 'jquery-ui-autocomplete' ), true );
wp_localize_script( 'bh-stock-ticker', 'bhTicker', array(
		'url' => admin_url( 'admin-ajax.php' ),
		'topstock' => bh_stock_ticker_top(),
		'checkSymbol' => 'false',
		'symbol' => '',
	)
);
?>
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
										<form role="search" action="" title="Find by Symbol">
											<label for="find-by-symbol" form="find-by-symbol" style="display:none;">Find by Symbol</label>
											<input placeholder="Find by Symbol" id="find-by-symbol" name="Find by Symbol">
											<button class="symbol-search-submit" title="Symbol Search Submit" aria-label="Symbol Search Submit"></button>
										</form>
									</span>
								</div>
							</div>
							<div id="widget_Tape">
								<!-- TradingView Widget BEGIN -->
								<div class="tradingview-widget-container">
								  <div class="tradingview-widget-container__widget"></div>
								  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
								  {
								  "symbols": [
									{
									  "proName": "OANDA:SPX500USD",
									  "title": "S&P 500"
									},
									{
									  "proName": "OANDA:NAS100USD",
									  "title": "Nasdaq 100"
									},
									{
									  "proName": "FX_IDC:EURUSD",
									  "title": "EUR/USD"
									},
									{
									  "proName": "BITSTAMP:BTCUSD",
									  "title": "BTC/USD"
									},
									{
									  "proName": "BITSTAMP:ETHUSD",
									  "title": "ETH/USD"
									}
								  ],
								  "colorTheme": "light",
								  "isTransparent": false,
								  "largeChartUrl": "https://banyanhill.com/research/symbol/",
								  "displayMode": "adaptive",
								  "locale": "en"
								}
								  </script>
								</div>
								<!-- TradingView Widget END -->
							</div>
							<div class="widget-with-headline col-1-of-2">
								<h4>Market Glance</h4>
								<div id="widget_Overview">

									<!-- TradingView Widget BEGIN -->
									<div class="tradingview-widget-container">
									  <div class="tradingview-widget-container__widget"></div>
									  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
									  {
									  "colorTheme": "light",
									  "dateRange": "12m",
									  "showChart": true,
									  "locale": "en",
									  "width": "100%",
									  "height": "100%",
									  "largeChartUrl": "https://banyanhill.com/research/symbol/",
									  "isTransparent": false,
									  "plotLineColorGrowing": "rgba(33, 150, 243, 1)",
									  "plotLineColorFalling": "rgba(33, 150, 243, 1)",
									  "gridLineColor": "rgba(233, 233, 234, 1)",
									  "scaleFontColor": "rgba(120, 123, 134, 1)",
									  "belowLineFillColorGrowing": "rgba(33, 150, 243, 0.12)",
									  "belowLineFillColorFalling": "rgba(33, 150, 243, 0.12)",
									  "symbolActiveColor": "rgba(33, 150, 243, 0.12)",
									  "tabs": [
										{
										  "title": "Indices",
										  "symbols": [
											{
											  "s": "OANDA:SPX500USD",
											  "d": "S&P 500"
											},
											{
											  "s": "OANDA:NAS100USD",
											  "d": "Nasdaq 100"
											},
											{
											  "s": "FOREXCOM:DJI",
											  "d": "Dow 30"
											},
											{
											  "s": "INDEX:NKY",
											  "d": "Nikkei 225"
											},
											{
											  "s": "INDEX:DEU30",
											  "d": "DAX Index"
											},
											{
											  "s": "OANDA:UK100GBP",
											  "d": "FTSE 100"
											}
										  ],
										  "originalTitle": "Indices"
										},
										{
										  "title": "Commodities",
										  "symbols": [
											{
											  "s": "CME_MINI:ES1!",
											  "d": "E-Mini S&P"
											},
											{
											  "s": "CME:6E1!",
											  "d": "Euro"
											},
											{
											  "s": "COMEX:GC1!",
											  "d": "Gold"
											},
											{
											  "s": "NYMEX:CL1!",
											  "d": "Crude Oil"
											},
											{
											  "s": "NYMEX:NG1!",
											  "d": "Natural Gas"
											},
											{
											  "s": "CBOT:ZC1!",
											  "d": "Corn"
											}
										  ],
										  "originalTitle": "Commodities"
										},
										{
										  "title": "Bonds",
										  "symbols": [
											{
											  "s": "CME:GE1!",
											  "d": "Eurodollar"
											},
											{
											  "s": "CBOT:ZB1!",
											  "d": "T-Bond"
											},
											{
											  "s": "CBOT:UB1!",
											  "d": "Ultra T-Bond"
											},
											{
											  "s": "EUREX:FGBL1!",
											  "d": "Euro Bund"
											},
											{
											  "s": "EUREX:FBTP1!",
											  "d": "Euro BTP"
											},
											{
											  "s": "EUREX:FGBM1!",
											  "d": "Euro BOBL"
											}
										  ],
										  "originalTitle": "Bonds"
										},
										{
										  "title": "Forex",
										  "symbols": [
											{
											  "s": "FX:EURUSD"
											},
											{
											  "s": "FX:GBPUSD"
											},
											{
											  "s": "FX:USDJPY"
											},
											{
											  "s": "FX:USDCHF"
											},
											{
											  "s": "FX:AUDUSD"
											},
											{
											  "s": "FX:USDCAD"
											}
										  ],
										  "originalTitle": "Forex"
										}
									  ]
									}
									  </script>
									</div>
									<!-- TradingView Widget END -->
								</div>
							</div>
							<div class="widget-with-headline col-1-of-2">
								<h4>Market Activity</h4>
								<div id="widget_Movers">
									<!-- TradingView Widget BEGIN -->
									<div class="tradingview-widget-container">
									  <div class="tradingview-widget-container__widget"></div>
									  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-hotlists.js" async>
									  {
									  "colorTheme": "light",
									  "dateRange": "12m",
									  "exchange": "US",
									  "showChart": true,
									  "locale": "en",
									  "width": "100%",
									  "height": "100%",
									  "largeChartUrl": "https://banyanhill.com/research/symbol/",
									  "isTransparent": false,
									  "plotLineColorGrowing": "rgba(33, 150, 243, 1)",
									  "plotLineColorFalling": "rgba(33, 150, 243, 1)",
									  "gridLineColor": "rgba(240, 243, 250, 1)",
									  "scaleFontColor": "rgba(120, 123, 134, 1)",
									  "belowLineFillColorGrowing": "rgba(33, 150, 243, 0.12)",
									  "belowLineFillColorFalling": "rgba(33, 150, 243, 0.12)",
									  "symbolActiveColor": "rgba(33, 150, 243, 0.12)"
									}
									  </script>
									</div>
									<!-- TradingView Widget END -->
								</div>
							</div>
							<div class="widget-with-headline col-1-of-1">
								<h4>Crypto Exchange</h4>
								<div id="widget_Crypto">
									<!-- TradingView Widget BEGIN -->
									<div class="tradingview-widget-container">
									  <div class="tradingview-widget-container__widget"></div>
									  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
									  {
									  "width": "100%",
									  "height": "100%",
									  "defaultColumn": "overview",
									  "screener_type": "crypto_mkt",
									  "displayCurrency": "USD",
									  "colorTheme": "light",
									  "locale": "en"
									}
									  </script>
									</div>
									<!-- TradingView Widget END -->
								</div>
							</div>
							<div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/" rel="noopener" target="_blank"><span class="blue-text">Charts &amp; data provided</span></a> by TradingView</div>
						</div>
					</div>
				</article>
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
