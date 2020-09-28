<?php get_header(); ?>

<?php
// Get xcode value from the backend
if ( get_post_meta(get_the_ID(), 'site_xcode', true) ) {
	$site_xcode = get_post_meta(get_the_ID(), 'site_xcode', true);
} else {
	$site_xcode = '';
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
				<p id="breadcrumbs">
					<span xmlns:v="http://rdf.data-vocabulary.org/#">
						<span typeof="v:Breadcrumb"><a href="<?php echo '//' . $_SERVER['HTTP_HOST']; ?>" rel="v:url" property="v:title">Home</a> » 
							<span rel="v:child" typeof="v:Breadcrumb">
								<a href="/exclusives/" rel="v:url" property="v:title">Exclusives</a> » 
								<span class="breadcrumb_last"><?php the_title(); ?></span>
							</span>
						</span>
					</span>
				</p>				
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?>>
					<div class="post-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php 
						$exclusive_author = get_user_by('slug', str_replace(' ', '-', strtolower( get_post_meta(get_the_ID(), 'exclusive_author', true) ) ) );

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
					<!--div class="exNotice">
						<p><strong>Legal Notice:</strong> This work is based on what we’ve learned as financial journalists. It may contain errors and you should not base investment decisions solely on what you read here. It’s your money and your responsibility. Nothing herein should be considered personalized investment advice. Although our employees may answer general customer service questions, they are not licensed to address your particular investment situation. Our track record is based on hypothetical results and may not reflect the same results as actual trades. Likewise, past performance is no guarantee of future returns. Don’t trade in these markets with money you can’t afford to lose. Investing in stock markets involves the risk of loss. Before investing you should consider carefully the risks involved, if you have any doubt as to suitability or the taxation implications, seek independent financial advice. Banyan Hill Publishing expressly forbids its writers from having a financial interest in their own securities or commodities recommendations to readers. Such recommendations may be traded, however, by other editors, Banyan Hill Publishing, its affiliated entities, employees, and agents, but only after waiting 24 hours after an internet broadcast or 72 hours after a publication only circulated through the mail.</p>
						<p><strong>Banyan Hill Publishing</strong> &#169; <?php echo date('Y'); ?>. All Rights Reserved. Protected by copyright laws of the United States and treaties. This Newsletter may only be used pursuant to the subscription agreement. Any reproduction, copying, or redistribution, (electronic or otherwise) in whole or in part, is strictly prohibited without the express written permission of Banyan Hill Publishing. P.O. Box 8378, Delray Beach, FL 33482. (TEL: 866-584-4096)</p>
					</div-->
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
