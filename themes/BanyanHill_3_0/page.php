<?php get_header(); ?>
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
							<?php the_content(); ?>
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
