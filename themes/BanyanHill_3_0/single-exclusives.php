<?php get_header(); ?>

<?php


// Get xcode value from the backend
if ( get_post_meta(get_the_ID(), 'site_xcode', true) ) {
	$site_xcode = get_post_meta(get_the_ID(), 'site_xcode', true);
} else {
	$site_xcode = '';
}

if ( get_post_meta(get_the_ID(), 'is_indexed', true) === '1' ) {
	add_filter( 'wpseo_robots', function() { return 'index, follow'; } );
}

wp_enqueue_style( 'exclusives', get_stylesheet_directory_uri() . '/css/exclusives.css' );
?>
<script type="text/javascript">
	var exclusive_post_site_excode = '<?php echo $site_xcode; ?>';
</script>
<div class="ad_txt"><?php the_field('advertorialtxt'); ?></div>
<div class="exclusives_div"></div>
<div id="main-content">
	<?php
		if ( et_builder_is_product_tour_enabled() ):

			while ( have_posts() ): the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry-content">
					<?php
						the_content();
					?>
					</div><!-- .entry-content -->

				</article> <!-- .et_pb_post -->

		<?php endwhile;
		else:
	?>
	<div class="container">
		<div id="content-area" class="<?php extra_sidebar_class(); ?> clearfix">
			<div class="et_pb_extra_column_main et_pb_extra_column_main-na">

				<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post(); ?>				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?>>
					<div class="post-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php 
						$exclusive_author = get_user_by('slug', str_replace('/ /g', '-', strtolower( get_post_meta(get_the_ID(), 'exclusive_author', true) ) ) );

						if ($exclusive_author) {
					?>
						<div class="post-meta vcard">
							<p>Written by <a href="<?php echo get_author_posts_url($exclusive_author->ID); ?>" class="url fn" title="Posts by <?php echo $exclusive_author->display_name; ?>" rel="author"><?php echo $exclusive_author->display_name; ?></a><span> | <?php echo date('M j, Y'); ?></span>
							</p>
						</div>
					<?php } ?>
					</div>					
					<div class="post-wrap">						
						<div class="post-content entry-content">
							<?php the_content(); ?> 
							<?php
								wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extra' ),
									'after'  => '</div>',
								) );
							?>
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
				?>
			</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->

			<?php
				// This is dependend on Wordpress plugins:
				// https://wordpress.org/plugins/custom-post-type-ui/
				// https://wordpress.org/plugins/content-aware-sidebars/
				if ( get_post_meta(get_the_ID(), 'exclusive_sidebar', true) ) {
					echo do_shortcode( '[ca-sidebar id="' . get_post_meta(get_the_ID(), 'exclusive_sidebar', true) . '"]' );
				} else {
					echo do_shortcode( '[ca-sidebar id="358761"]' );
					//get_sidebar();
				}
			?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->
	<?php endif; ?>
</div> <!-- #main-content -->
<?php get_footer();
