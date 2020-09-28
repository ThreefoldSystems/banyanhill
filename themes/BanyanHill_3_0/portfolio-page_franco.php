<?php
/**
 * Template Name: PDF Download List Template
 *
 * @package DW Focus
 * @since DW Focus 1.3.1
 */

global $post;

if ( $post->post_parent ) {
	$ancestors = get_post_ancestors( $post->ID );
	$root = count( $ancestors ) - 1;
	$parent = $ancestors[$root];
} else {
	$parent = $post->ID;
}

$auth_container = check_auth_by_post_id( $parent, get_permalink( $post->ID ) );

get_header();

$terms = wp_get_post_terms( $post->ID , 'portfolio-category' );

$terms_id = $terms[0]->term_id;
$archivedate = $_GET[ 'portfoliodate' ] ? $_GET[ 'portfoliodate' ] : $_GET[ 'archivedate' ];
$ajaxaction = 'load_more_portfolio';
?>
<div class="bootstrap-wrapper subscription-template">
	<div class="container">
		<div class="row">
			<?php
			if ( have_posts() ) {
				while (have_posts()) {
					the_post();
				?>
				<div class="col-lg-12 col-md-12 cols-sm-12 col-12">
					<?php
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						}					
					?>								
					<div id="primary" class="content-area">
						<?php
							$has_archive_filter = true;
							include(locate_template( 'template-parts/sub-service-header.php' ));  
						?>						
						<main id="main" class="site-main" role="main">
							<section id="archivePg">
								<div class="row">
									<div class="col-lg-9 col-md-9 col-sm-12 col-12">								
										<?php

										if ( $_GET[ 'srh' ] == 'search' && $archivedate != '' ) {
											$arg = array( 'posts_per_page' => '80' ,
												'post_type' => 'portfolio' ,
												'meta_key' => 'order_date' ,
												'orderby' => 'meta_value_num' ,
												'order' => 'DESC' ,
												'tax_query' => array( 

													array( 
														'taxonomy' => 'portfolio-category',
														'field' => 'id' ,
														'terms' => $terms_id ,
														'include_children' => FALSE , 
													) 
												),
												'meta_query' => array( 
													array( 
														'key' => 'date_for_search_portfolio' ,
														'value' => $archivedate ,
														'compare' => 'LIKE' , 
													)
												) 
											);
										} else {
											$arg = array( 
												'tax_query' => array( 
													array( 
														'taxonomy' => 'portfolio-category' ,
														'field' => 'id' ,
														'terms' => $terms_id ,
														'include_children' => FALSE
													) 
												),
												'post_type' => 'portfolio' ,
												'posts_per_page' => '80' ,
												'order' => 'DESC' ,
												'orderby' => 'meta_value_num',
												'meta_key' => 'order_date'
											);
										}

										query_posts( $arg );

										$total_post = query_posts( $arg );

										$count = count( $total_post );
										?>
										<div class="archive-list">
											<?php if ( have_posts() ) {
											//TODO: Rewrite in PHP and extend to all archives
													?>
						
													<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/js/ajax-search/style.css">
													<div ng-app="instantSearch">
														<div ng-controller="InstantSearchController">
															<div id="searchform" class="row">
																<div class="col-lg-12 col-md-12 col-sm-12 col-12">
																	<input type="text" ng-model="searchString"
																	   class="form-control"
																	   style="width:100%; padding:10px;"
																	   placeholder="Enter your search terms" />
																   	<input type="submit" class="search-submit">
																</div>
															</div>
															<div class="archive-block row">
																<div class="pdf_blocky ng-cloak col-lg-6 col-md-6 col-sm-12 col-12 cf" ng-repeat="i in items | searchFor:searchString" ng-cloak>
																	<div class="archive_item">
																		<div class="mob_inner">
																			<h2>
																				<a class="pdf-block"
																				   onclick="{{i.event_tracking_code}}"
																				   style="height:112px;"
																				   target="_blank"
																				   href="{{i.pdf_link}}">{{i.pdf_title}}
																				</a>
																			</h2>
																			<div class="row archive-details">
																				<div class="col-lg-6 col-md-6 col-sm-12 col-12 date-container">
																					<div class="date_block">
																						<i class="fa fa-clock-o"></i> <span class="archive_date">{{i.pdf_date}}</span>
																					</div>
																				</div>
																				<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																					<p><a {{i.event_tracking_code ? 'onclick="' i.event_tracking_code '"' : '' }} 
																						  href="{{i.pdf_link}}" 
																						  class="readMore" 
																						  target="_blank"><i class="fa fa-file-pdf-o"></i> 
																						Read the report
																						</a>
																					</p>		
																				</div>
																			</div>																			
																		</div>
																	</div>
																	<div id="last" ng-if="$last"></div>
																</div>
																<div class="col-lg-12 col-md-12 col-sm-12 col-12 cf">
																	<h4 class="h4_pdf" style="text-align: center">Nothing found.</h4>
																</div>																
															</div>																
														</div>

														<script src="https://code.angularjs.org/1.1.5/angular.min.js"></script>
														<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/ajax-search/autocomplete.js"></script>
														<script>
															var app = angular.module("instantSearch", []);
															app.filter('searchFor', function () {
																return function (arr, searchString) {
																	if (!searchString) {
																		return arr;
																	}
																	var result = [];
																	searchString = searchString.toLowerCase();
																	searchString = searchString.split(' ');
																	var val = 0;
																	angular.forEach(arr, function (item) {

																		for (var i = 0; i < searchString.length; i++) {
																			if (item.pdf_title.toLowerCase().indexOf(searchString[i]) !== -1 || item.pdf_keyword.toLowerCase().indexOf(searchString[i]) !== -1) {
																				result.push(item);
																				val++;
																			}
																		}

																	});
																	return result;

																};
															});

															function InstantSearchController($scope) {
																var params = {
																	"term_id": <?php echo $terms_id; ?>,
																	"action": "instantSearch_filter",
																	"query_args": <?php echo json_encode($arg); ?>
																};
																var site_ajax_url = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
																jQuery.post(
																	site_ajax_url,
																	params,
																	function (data) {
																		if (data.trim()) {
																			var object = JSON.parse(data);
																			$scope.items = object;
																			$scope.$apply();
																		}
																	});
															}
														</script>
													</div>
													<?php
													wp_reset_query();
											} else {
												?>
												<h4>Nothing found. Please select different year.</h4>
												<?php
											}
											?>
										</div>

										<div id="image-ajax" style="visibility: hidden; text-align: center; padding-top: 20px;">
											<img src="<?php echo get_template_directory_uri(); ?>/images/ajax-loader.gif" />
										</div>

										<script type="text/javascript">
											post_offset = increment = 50;
										</script>
									</div>
									<div class="col-lg-3 col-md-3">
										<!-- Spacer -->
									</div>
								</div>
							</section>									
						</main>
					</div>
				</div>
				<?php
				}
			}
			?>
		</div>
	</div>
</div>

<?php 
	include(locate_template( 'template-parts/sub-service-script.php' )); 
	get_footer(); 
?>