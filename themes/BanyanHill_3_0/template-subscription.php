<?php
/**
 * Template Name: Subscription
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

get_header(); ?>
<div class="bootstrap-wrapper">
	<div class="container">
		<div class="row">
		<?php
		if ( have_posts() ) {
			while (have_posts()) {
				the_post();
		?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-12">
				<?php
					if ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb('<p id="breadcrumbs">','</p>');
					}
				
					// Check if MW auth plugin is active
					if (class_exists('agora_auth_container')) {
						$auth_container = new agora_auth_container($post->ID);
						$auth_container = apply_filters('agora_middleware_check_permission', $auth_container);
					}
				?>							
				<div id="primary" class="content-area">
					<?php
						include(locate_template( 'template-parts/sub-service-header.php' ));  
					?>	
					<main id="main" class="site-main" role="main">
						<section id="archivePg">
							<div class="row">				
							<?php				
								if ( $auth_container->is_allowed() || in_array( 'administrator', (array) wp_get_current_user()->roles ) ) {
							?>
								<div class="col-lg-9 col-md-9 col-sm-9 col-12 specialRprts">
									<?php the_content(); ?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-12"></div>
								<?php
									} else {
								?>
								<div class="col-lg-9 col-md-9 col-sm-9 col-12 specialRprts">
									<?php the_excerpt(); ?>										
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-12">
										<?php if ( get_post_meta($parent, 'sidebar_button_url_link', true) ) { ?>
											<div class="signup-button">
												<a href="<?php echo get_post_meta($parent, 'sidebar_button_url_link', true); ?>"><?php echo get_post_meta($parent, 'sidebar_button_text', true); ?></a>
											</div>									
										<?php } ?>
								</div>								
			  					<?php } ?>
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
<?php get_footer(); ?>
