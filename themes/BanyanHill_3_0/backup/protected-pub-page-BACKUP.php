<?php
/**
 * Template Name: Protected PUB Content Template
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
			<div class="col-lg-12 col-md-12 col-sm-12 col-12">
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
								<div class="col-lg-9 col-md-9 col-sm-9 col-12 specialRprts">
									<?php the_content(); ?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-12">
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
<?php get_footer(); ?>
