<?php 
/**
 * Template Name: Survey Results
 *
 * @package DW Focus
 * @since DW Focus 1.3.1
 */

get_header(); 

$category_id = !empty($_GET['cat_id']) && isset($_GET['cat_id']) ? $_GET['cat_id'] : '19151';

?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="<?php extra_sidebar_class(); ?> clearfix">
			<div class="et_pb_extra_column_main">
				<?php
				if ( function_exists('yoast_breadcrumb') ) {
					yoast_breadcrumb('<p id="breadcrumbs">','</p>');
				}				
				if ( have_posts() ) :
					while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-wrap">
						<?php if ( is_post_extra_title_meta_enabled() ) { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
						<?php } ?>
						<div class="post-content entry-content">
							<div id="et-boc" class="et-boc">
								<div class="et_builder_inner_content et_pb_gutters3">
									<div class="et_pb_section et_pb_section_0 et_section_regular">
										<div class="et_pb_row et_pb_row_0">
											<div class="et_pb_column et_pb_column_4_4 et_pb_column_0 et_pb_css_mix_blend_mode_passthrough et-last-child">
												<div class="et_pb_module et_pb_code et_pb_code_0">
													<div class="et_pb_code_inner">							
														<?php 
														echo do_shortcode('[ipt_fsqm_trackback label="Your Code" submit="Submit"]');
														//the_content(); 
														?>
													</div>
												</div>
												<div id="expBlogRoll" class="et_pb_module et_pb_text et_pb_text_0 et_pb_bg_layout_light  et_pb_text_align_left">
													<div class="et_pb_text_inner">
														<h2>Recent <em><?php echo get_the_category_by_ID($category_id); ?></em> Articles</h2>
													</div>
												</div>
												<?php echo do_shortcode('[display-posts image_size="extra-image-small" include_date="true" date_format="F j, Y" category_id="' . $category_id . '" wrapper="div" category_label="" category_display="true" wrapper_class="exp-grid-layout" include_excerpt="true" include_excerpt_dash="false" infinite_scroll="true"]'); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
							if ( ! extra_is_builder_built() ) {
								wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extra' ),
									'after'  => '</div>',
								) );
							}
							?>
						<script type="text/javascript">
							var loadImages = jQuery('img.loading');
							for (var i = 0; i < loadImages.length; i++) {
								if (typeof jQuery(loadImages[i]).attr('src') === 'undefined') {
									jQuery(loadImages[i]).attr('src' , 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==');
								}								
							}
						</script>							
						</div>
					</div><!-- /.post-wrap -->
				</article>
				<?php
					endwhile;
				else :
					?>
					<h2><?php esc_html_e( 'Post not found', 'extra' ); ?></h2>
					<?php
				endif;
				wp_reset_query();
				?>
				<?php
				if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'extra_show_pagescomments', 'on' ) ) {
					comments_template( '', true );
				}
				?>
			</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->

			<?php get_sidebar(); ?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer();
