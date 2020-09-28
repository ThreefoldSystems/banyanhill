<?php
/**
 * Template Name: Video List Orville Page Template
 *
 * @package DW Focus
 * @since DW Focus 1.3.1
 */

global $post;

if ( $post->post_parent ) {
	$ancestors = get_post_ancestors( $post->ID );
	$root = count( $ancestors) - 1;
	$parent = $ancestors[$root];
} else {
	$parent = $post->ID;
}

check_auth_by_post_id( $parent, get_permalink( $post->ID ) );

wp_enqueue_style( 'modal-styles', get_stylesheet_directory_uri() . '/css/modal-styles.css' );

get_header();

$terms = wp_get_post_terms($post->ID, 'library-category');

$terms_id = $terms[0]->term_id;
$archivedate = $_GET[ 'librarydate' ] ? $_GET[ 'librarydate' ] : $_GET[ 'archivedate' ];
$ajaxaction = 'load_more_webinar';
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

										if($_GET['srh'] == 'search' && $archivedate != '') {
											$arg =array(
												'posts_per_page' => '40',
												'post_type' => 'library',
												'meta_key' => 'order_date',
												'orderby' => 'meta_value_num',
												'order' => 'DESC',
												'tax_query' => array(
													array(
														'taxonomy' => 'library-category',
														'field' => 'id',
														'terms' => $terms_id,
														'include_children' => false,
													),
												),
												'meta_query' => array(
													array(
														'key' => 'date_for_search_library',
														'value' => $archivedate,
														'compare' => 'LIKE',
													),
												)
											);
										} else {
											$arg =array(
												'tax_query' => array(
													array(
														'taxonomy' => 'library-category',
														'field' => 'id',
														'terms' => $terms_id,
														'include_children' => false
													)
												),
												'post_type' => 'library',
												'posts_per_page' => '40',
												'order' => 'DESC',
												'orderby' => 'meta_value_num',
												'meta_key' => 'order_date'
											);
										}

										query_posts($arg);

										$total_post = query_posts($arg);

										$count = count($total_post);
										?>
										<div class="archive-list">
											<?php
											if (have_posts()) {
												$i = 0;
												while (have_posts()) : the_post(); 
											
												$postBckgndImg = strtolower(get_post_meta(get_the_ID(), 'webinar_media_type', true));

												$postBckgndStyle = '';

												if ($postBckgndImg) {
													$postBckgndStyle = ' style="background-image: url(https://banyanhill.s3.amazonaws.com/icons/icon-' . $postBckgndImg . '-sml.png); background-repeat: no-repeat; padding-left: 28px;" ';
												}											
											?>

											<?php if ($i % 2 == '0') { ?>
											<div class="archive-block">
												<div class="row">
											<?php } ?>

													<div class="col-md-6 col-xs-12 cf <?php if ($i % 2 == '1') {
														echo "last";
													} ?>">
														<div class="archive_item">
															<div class="mob_inner">
																<h2 <?php echo $postBckgndStyle ?>>
																	<a <?php if ( get_post_meta(get_the_ID(), 'event_tracking_code', true) ) { ?>
																	   onclick="<?php echo get_post_meta(get_the_ID(), 'event_tracking_code', true); ?>" 
																	   <?php } ?>
																	   rel="modal:open"
																	   href="#<?php echo strtotime( get_post_meta(get_the_ID(), 'webinar_library_date', true) ); ?>">
																		<?php if ( get_post_meta(get_the_ID(), 'webinar_library_description', true) ) {
																			echo get_post_meta(get_the_ID(), 'webinar_library_description', true);
																		} else {
																			the_title();
																		} ?>
																	</a>
																</h2>
																<div class="date_block">
																	<i class="fa fa-clock-o"></i> <span 
																		class="archive_date"><?php if ( get_post_meta(get_the_ID(), 'webinar_library_date', true) ) {
																			echo get_post_meta(get_the_ID(), 'webinar_library_date', true);
																		} else {
																			the_time( 'F j, Y' );
																		} ?>
																	</span>
																</div>

																<p><a <?php if ( get_post_meta(get_the_ID(), 'event_tracking_code', true) ) { ?>
																	   onclick="<?php echo get_post_meta(get_the_ID(), 'event_tracking_code', true); ?>" 
																	   <?php } ?> 
																	  href="<?php if(get_post_meta(get_the_ID(), 'transcrip_pdf_link', true)) { 
																			echo get_post_meta(get_the_ID(), 'transcrip_pdf_link', true); 
																		} else { 
																			echo "javascript:;"; 
																		} ?>" 
																	  class="readMore" 
																	  target="_blank"><i class="fa fa-file-pdf-o"></i> 
																	Read the transcript
																	</a>
																</p>
															</div>
														</div>
														<div id="<?php echo strtotime( get_post_meta(get_the_ID(), 'webinar_library_date', true) ); ?>" class="modal">
															<div class="modalHeader">
																<div>
																	<h1 class="sectionHead"><?php echo get_post_meta(get_the_ID(), 'webinar_library_description', true); ?></h1>
																</div>
																<div>
																	<a href="<?php echo esc_url( get_post_meta(get_the_ID(), 'webinar_library_embed_link', true) ) ?>" target="_blank">(view page)</a>
																</div>
																<div class="clear"></div>
															</div>
															<hr class="sectionHR">
															<div id="modalContainer">
																<iframe class="loading-iframe" data-src="<?php if( get_post_meta(get_the_ID(), 'webinar_library_embed_link', true) ) { 
																			echo str_replace('http:', 'https:', get_post_meta(get_the_ID(), 'webinar_library_embed_link', true) ); 
																		} else { 
																			echo "javascript:;"; 
																		} ?>" 
																		width="100%" 
																		height="450" 
																		frameborder="0"></iframe>
															</div>
														</div>														
													</div>

											<?php
											if ($i % 2 == '1' || ($i + 1) == $count) {
												?>
												</div>
											</div>
											<?php
											}
												$i++;
												endwhile;
												wp_reset_query();
											} else {
											?>
												<h4>No posts found. Please select different year.</h4>
											<?php } ?>
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
<script type="application/javascript">
	jQuery(document).ready(function() {		
		jQuery('.modal').on(jQuery.modal.OPEN, function(event, modal) {
			var bLazy = new Blazy({ 
				selector: '.loading-iframe', //ad iframes
				success: function(element){
					setTimeout(function(){
						element.className = element.className.replace(/\bloading-iframe\b/,'');
					}, 200);
				}				
			});
		});
	});
</script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/jquery.modal.min.js"></script>
<?php 
	include(locate_template( 'template-parts/sub-service-script.php' )); 
	get_footer(); 
?>



