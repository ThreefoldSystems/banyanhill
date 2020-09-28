<?php
/**
 * Template Name: ePortfolio Template
 *
 * @package DW Focus
 * @since DW Focus 1.3.1
 */

global $post;

check_auth_by_post_id( $post->ID, get_permalink( $post->ID ) );

get_header(); ?>
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
						<!-- End Header and Sidebar -->
						<main id="main" class="site-main" role="main">
							<section id="modelPort">									
								<div class="row">
									<div class="col-lg-12 col-md-12 col-12">
										<?php the_content(); ?>
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
<?php get_footer(); ?>
