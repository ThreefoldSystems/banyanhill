<?php
/**
 * Template Name: Archive Content Template
 *
 */

global $post;
$is_archive = true;
$auth_container = check_auth_by_post_id( $post->ID, get_permalink( $post->ID ) );

get_header(); ?>
<div class="bootstrap-wrapper subscription-template">
	<div class="container">
		<div class="row">
		<?php
		//TODO: Move to function
		//TODO: Get accurate breadcrumb list with all parents
		$archive_terms = get_the_terms(get_the_ID(), 'archives-category')[0];
		$product_page_args = array(
						'posts_per_page' => 1,
						'post_type' => 'page',
						'order' => 'ASC',
						'orderby' => 'menu_order',
						'tax_query' => array(
							array(
								'taxonomy' => 'archives-category',
								'field' => 'id',
								'terms' => $archive_terms->term_id,
								'include_children' => false,
							),
						),	
					);

		$breadcrumb_loop = new WP_Query( $product_page_args );
		if ( $breadcrumb_loop->have_posts() ) {
			while ( $breadcrumb_loop->have_posts() ) {
				$breadcrumb_loop->the_post();
				
				if ($breadcrumb_loop->post->post_parent) {
					$ancestors = get_post_ancestors($breadcrumb_loop->post->ID);
					$root = count( $ancestors ) - 1;
					$parent = $ancestors[$root];
				} else {
					$parent = $breadcrumb_loop->post->ID;
				}				
			}
		}

		wp_reset_postdata();			
		?>			
			
		<?php
		if ( have_posts() ) {
			while (have_posts()) {
				the_post();
		?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-12">
				<p id="breadcrumbs">
					<span xmlns:v="http://rdf.data-vocabulary.org/#">
						<span typeof="v:Breadcrumb"><a href="<?php echo '//' . $_SERVER['HTTP_HOST']; ?>" rel="v:url" property="v:title">Home</a> » 
							<span rel="v:child" typeof="v:Breadcrumb">
								<a href="<?php echo esc_url( get_permalink( $breadcrumb_loop->post->ID ) ) ?>" rel="v:url" property="v:title"><?php echo $breadcrumb_loop->post->post_title ?></a> » 
								<span class="breadcrumb_last"><?php the_title(); ?></span>
							</span>
						</span>
					</span>
				</p>						
				<div id="primary" class="content-area">
					<?php
						$is_archive = true;
						include(locate_template( 'template-parts/sub-service-header.php' ));  
					?>	
					<main id="main" class="site-main" role="main">
						<section id="archivePg">
							<div class="row">
								<div class="col-lg-9 col-md-9 col-sm-9 col-12">
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php if ( is_post_extra_title_meta_enabled() ) { ?>
										<div class="post-header">
											<h1 class="entry-title"><?php the_title(); ?></h1>
											<div class="post-meta vcard">
												<p><i class="fa fa-clock-o"></i> <?php echo get_post_meta(get_the_ID(), 'archieve_date', true); ?></p>
											</div>
										</div>
										<?php } ?>
										<div class="post-content">
											<?php 
												the_content(); 

												$author_signup_box = get_the_author_meta( 'tfs_bh_author_signup_box' );

												if ( $author_signup_box ) {
													?>
													<div class="front_tfs_bh_author_signup_box">
														<?php echo $author_signup_box; ?>
													</div>
													<?php
												}													
											?>
										</div>
									</article>													
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-12">
									<!-- Spacer -->
								</div>											
							</div>
						<?php
						// Enable Comments for Archives Category - Alpha Investor Report (3-1-Q) only
						if ( ( comments_open() || get_comments_number() ) && $archive_terms->term_id === 33991 ) {
						?>
							<div class="row">
								<div class="col-lg-9 col-md-9 col-sm-9 col-12">
									<style>
										#bh-3-1-q-comment-hr {
											margin-top: 37px;
										}
										.bh-3-1-q-comment {
											padding: 23px; 
											background: #ffed95;
										}
									</style>
									<hr id="bh-3-1-q-comment-hr">
									<div class="bh-3-1-q-comment"><p><strong>Comment Rules</strong>: I’m a New Yorker, born and bred … so you could say that I love to argue. But like most New Yorkers, we here at Alpha Investor Report draw the line at being disrespectful. That’s something I won’t tolerate, and neither should you. So please keep your comments educational and informative. You can disagree with someone — but if you’re rude, we’ll delete your stuff. Have fun, and thanks for adding to the Alpha Investor community!</p></div>									
									<h2>Comments</h2>
									<?php comments_template( '', true ); ?>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-12">
									<!-- Spacer -->
								</div>								
							</div>
						<?php } ?>					
						</section>									
					</main>
					<script type="text/javascript">
						jQuery(document).ready(function() {
							jQuery('.post-content a:contains("larger")').attr('data-featherlight', 'image');
						});
					</script>
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
