<?php
/**
 * Template Name: Multipart List Page Template
 *
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

$terms = wp_get_post_terms($post->ID, 'archives-category');

$terms_id = $terms[0]->term_id;
$archivedate = $_GET[ 'archivedate' ];

$is_meeting_of_the_minds_archive = false;

foreach ( $terms as $term ) {
	if ( $term->slug == 'meeting-of-the-minds-archive' ) {
		$is_meeting_of_the_minds_archive = true;
	}
}
?>
<div class="bootstrap-wrapper subscription-template">
	<div class="container">
		<div class="row">
			<?php
			if ( have_posts() ) {
				while (have_posts()) {
					the_post();
				?>
				<div class="col-lg-12 col-md-12 col-12">
					<?php
						if ( function_exists('yoast_breadcrumb') ) {
							yoast_breadcrumb('<p id="breadcrumbs">','</p>');
						}
					?>
					<div id="primary" class="content-area">

						<?php

							include(locate_template( 'template-parts/sub-service-header.php' ));
						?>

						<main id="main" class="site-main" role="main">
							<section id="archivePg">
								<div class="row">
									<div class="col-lg-9 col-md-9 col-sm-12 col-12">
										<h2 style="padding-left: 10px;">Issues</h2>
										<?php

										if ($_GET['srh'] == 'search' && $_GET['archivedate'] != '') {
											$arg = array(
												'posts_per_page' => '10',
												'post_status' => 'publish',
												'post_type' => 'archives',
												'meta_key' => 'order_date',
												'orderby' => 'meta_value_num',
												'order' => 'DESC',
												'tax_query' => array(

													array(
														'taxonomy' => 'archives-category',
														'field' => 'id',
														'terms' => $terms_id,
														'include_children' => false,
													)
												),
												'meta_query' => array(
													array(
														'key' => 'date_for_search_archive',
														'value' => $_GET['archivedate'],
														'compare' => 'LIKE',
													),
												)
											);
										} else {
											$arg = array(
												'tax_query' => array(
													array(
														'taxonomy' => 'archives-category',
														'field' => 'id',
														'terms' => $terms_id,
														'include_children' => false
													)
												),
												'post_status' => 'publish',
												'post_type' => 'archives',
												'posts_per_page' => '10',
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

											<?php if (have_posts()) {
												$i = 0;
												while (have_posts()) : the_post(); ?>

													<?php if ($i % 2 == '0') { ?>
														<div class="archive-block">

														<div class="row">

													<?php } ?>


													<div class="col-md-6 col-xs-12 cf <?php if ($i % 2 == '1') {
														echo "last";
													} ?>">


													<div class="archive_item <?php echo get_the_terms( get_the_ID(), 'pubcode' ) ? '' : 'missing-pubcode' ?>">

														<div class="mob_inner">

															<h2>
																<a onclick="<?php echo get_post_meta(get_the_ID(), 'event_tracking_code', true); ?>"
																   href="<?php the_permalink(); ?>">
																	<?php if ( get_post_meta(get_the_ID(), 'page_title', true) ) {
																		echo get_post_meta(get_the_ID(), 'page_title', true);
																	} else {
																		the_title();
																	} ?>
																</a>
															</h2>
															<div class="date_block">
																<i class="fa fa-clock-o"></i> <span
																	class="archive_date"><?php if ( get_post_meta(get_the_ID(), 'archieve_date', true) ) {
																		echo get_post_meta(get_the_ID(), 'archieve_date', true);
																	} else {
																		the_time('F j, Y');
																	} ?>
																</span>
															</div>
															<p>
																<?php echo wp_strip_all_tags( get_the_excerpt() ); ?> <a href="<?php the_permalink(); ?>" class="readMore">Read More</a>
															</p>
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
												<h4>No archives found. Please select different year.</h4>
												<?php
											}
											?>
										</div>
										<button class="show-more" data-action="load_more_archives" data-offset="10" data-increment="10" data-terms="<?php echo $terms_id; ?>"><span>SHOW MORE</span></button>
									</div>
									<div class="col-lg-3 col-md-3">
										<!-- Spacer -->
									</div>
								</div>
								<div class="row spacer">
									<div class="col-lg-9 col-md-9 col-sm-12 col-12">
										<!-- Spacer -->
									</div>
									<div class="col-lg-3 col-md-3">
										<!-- Spacer -->
									</div>
								</div>
								<div class="row">
									<div class="col-lg-9 col-md-9 col-sm-12 col-12">
										<h2 style="padding-left: 10px;">Reports</h2>
										<?php
										//RESET VARS FOR PORTFOLIO QUERY
										$terms = wp_get_post_terms( $post->ID , 'portfolio-category' );

										$terms_id = $terms[0]->term_id;
										$archivedate = $_GET[ 'portfoliodate' ] ? $_GET[ 'portfoliodate' ] : $_GET[ 'archivedate' ];

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
										<?php if (have_posts()) {
												$i = 0;
												while (have_posts()) : the_post(); ?>

													<?php if ($i % 2 == '0') { ?>
														<div class="archive-block">

														<div class="row">

													<?php } ?>


													<div class="col-md-6 col-xs-12 cf <?php if ($i % 2 == '1') {
														echo "last";
													} ?>">

													<div class="archive_item <?php echo get_the_terms( get_the_ID(), 'pubcode' ) ? '' : 'missing-pubcode' ?>">
														<div class="mob_inner">
															<h2>
																<a class="pdf-block"
																	 onclick="<?php echo get_post_meta(get_the_ID(), 'event_tracking_code', true); ?>"
																	 href="<?php echo get_post_meta(get_the_ID(), 'pdf_link', true) ?>"><?php if ( get_post_meta(get_the_ID(), 'pdf_title', true) ) {
																		echo get_post_meta(get_the_ID(), 'pdf_title', true);
																	} else {
																		the_title();
																	} ?>
																</a>
															</h2>
															<div class="row archive-details">
																<div class="col-lg-6 col-md-6 col-sm-12 col-12 date-container">
																	<div class="date_block">
																		<i class="fa fa-clock-o"></i> <span class="archive_date"><?php if ( get_post_meta(get_the_ID(), 'pdf_date', true) ) {
																		echo get_post_meta(get_the_ID(), 'pdf_date', true);
																	} else {
																		the_time('F j, Y');
																	} ?></span>
																	</div>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<p><a onclick="<?php echo get_post_meta(get_the_ID(), 'event_tracking_code', true); ?>"
																			href="<?php echo get_post_meta(get_the_ID(), 'pdf_link', true) ?>"
																			class="readMore"
																			target="_blank"><i class="fa fa-file-pdf-o"></i>
																		Read the report
																		</a>
																	</p>
																</div>
															</div>
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
												<h4>No archives found. Please select different year.</h4>
												<?php
											}
											?>
										</div>

										<button class="show-more" data-action="load_more_portfolio" data-offset="50" data-increment="10" data-terms="<?php echo $terms_id; ?>"><span>SHOW MORE</span></button>
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
<script type="text/javascript">
	var page = 0, page_increment = 0;
	var srh = '<?php echo $_GET['srh']; ?>';
	var archivedate = '<?php echo $archivedate; ?>';

	(function ($) {
		if (archivedate !== '') {
			$('form select[name="archivedate"]>option[value="' + archivedate + '"]').prop('selected', true);
		}

		$(document).on('click', '.show-more', function(){
			var params = {
					'offset': $(this).data('offset'),
					'term_id': $(this).data('terms'),
			<?php if($_GET['srh'] == 'search' && $archivedate != '') { ?>
					'srh': srh,
					'archivedate': archivedate,
			<?php } ?>
					'action': $(this).data('action')
				};

			var site_ajax_url = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
			var target_elem = $(this);

			target_elem.toggleClass('loading').text('LOADING');

			$.post(site_ajax_url, params, function(data) {
				if (data.trim()) {
					target_elem.data( 'offset', parseInt(target_elem.data('offset')) + parseInt(target_elem.data('increment')) ).siblings('.archive-list').append(data);
				}

				target_elem.toggleClass('loading').text('SHOW MORE');
			});
		});
	}(jQuery));
</script>
<?php
	get_footer();
?>
