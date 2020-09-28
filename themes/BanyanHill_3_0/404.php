<?php 

add_filter( 'wpseo_title', function( $title ) use ( $symbol ) {
		return $symbol . '404 Page Not Found';
}, 10, 1 );

get_header(); 

?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="<?php extra_sidebar_class(); ?> clearfix">
			<div class="et_pb_extra_column_main">
				<?php
				if ( function_exists('yoast_breadcrumb') ) {
				  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
				}
				?>

				<?php echo do_shortcode('[et_pb_section global_module="1154"][/et_pb_section]'); ?>
				<div class="posts-blog-feed-module post-module et_pb_extra_module standard et_pb_posts_blog_feed_standard_1 paginated et_pb_extra_module">
					<div class="paginated_content">
						<div class="paginated_page paginated_page_1 active" data-columns="1">
							<?php
							$args = array( 'numberposts' => 3 );
							$lastposts = get_posts( $args );
							foreach($lastposts as $post) : setup_postdata($post); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?> data-url="<?php echo get_post($post->ID)->post_name; ?>" data-title="<?php echo get_post($post->ID)->post_title; ?>">						
								<div class="header">	
									<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="featured-image"><?php the_post_thumbnail('medium'); ?><span class="et_pb_extra_overlay"></span></a>
								</div>
								<div class="post-content">
									<h2 class="post-title entry-title"><a class="et-accent-color" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<div class="post-meta vcard"><p>by <?php the_author_posts_link(); ?><span class="post-meta-separator"> | </span><span class="updated"><?php echo get_the_date('F j, Y'); ?></span><span class="post-meta-separator"> | </span><?php echo the_category(', '); ?></p></div>
									<div class="excerpt entry-summary"><?php the_excerpt(); ?></div>
								</div>
							</article>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div><!-- /.et_pb_extra_column.et_pb_extra_column_main -->

			<?php get_sidebar(); ?>

		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php get_footer();
