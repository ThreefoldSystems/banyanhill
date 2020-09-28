<?php $type = strtolower( et_get_option( 'archive_list_style', 'standard' ) ); ?>
<div class="posts-blog-feed-module <?php echo esc_attr( $type ); ?> post-module et_pb_extra_module module">
	<div class="paginated_content">
		<div class="paginated_page" <?php echo 'masonry' == $type ? 'data-columns' : ''; ?>>
		<?php	
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				$post_format = et_get_post_format();
				$post_format_class = !empty( $post_format ) ? 'et-format-' . $post_format : '';
				$is_protected = false;
			
				if ( has_excerpt() ) {
					$post_content = get_the_excerpt();
				} else {
					$excerpt_length = get_post_thumbnail_id() ? '100' : '230';
					$post_content = et_truncate_post( $excerpt_length, false );
				}
				// TODO: Find a better way to determine authorized content
				// This searches the content string for the login blocker
				if (strpos($post_content, 'Log In') === 0 || strpos($post_content, 'Hi ') === 0) $is_protected = true;
			
				if ($is_protected) {
					$post_format_class .= 'blurred';
					
					// Get meta fields for protected posts
					$service_pubcodes = new WP_Query(
						array(
							'post_type' => 'subscriptions',
							'post_status' => array('publish', 'private'),
							'posts_per_page' => 1,
							'meta_query' => array(
								array(
									'key' => 'tfs_subs_pubcode',
									'value' => wp_get_post_terms($post->ID, 'pubcode')[0]->name
								)
							)
						)
					);

					if ( $service_pubcodes->have_posts() ) {
						// For each subscription
						while ($service_pubcodes->have_posts()) {
							$service_pubcodes->the_post();
							$service_info_home = get_post_meta($service_pubcodes->post->ID, 'tfs_subs_home_link', true);
							$service_purchase = get_post_meta($service_pubcodes->post->ID, 'tfs_subs_purchase_link', true);
							$service_renewal_price = get_post_meta($service_pubcodes->post->ID, 'tfs_subscription_renewal_price', true);
							$service_image_url = wp_get_attachment_url( get_post_thumbnail_id( $service_pubcodes->post->ID ) );
						}
						
						// Set default image if not found
						if (!$service_image_url) {
							$service_image_url = 'https://cdn.banyanhill.com/wp-content/uploads/2014/10/06072331/banyan-logo-New.png';
						}						

						wp_reset_postdata();
					}
					
					// Get main page for service
//					$subscriptions_query = new WP_Query(
//						array(
//							'cat'	=> 209, // Subscription Category
//							'post_type' => 'page',
//							'post_status' => 'publish',
//							'posts_per_page' => 1,
//							'tax_query' => array(
//								array(
//									'taxonomy' => 'pubcode',
//									'field' => 'name',
//									'terms' => get_the_terms( $post->ID, 'pubcode' )[0]->name,
//									'include_children' => false
//								)
//							)					
//						)
//					);	
//
//					if ( $subscriptions_query->have_posts() ) {
//						// For each subscription
//						while ($subscriptions_query->have_posts()) {
//							$subscriptions_query->the_post();
//							$service_image_url = wp_get_attachment_url( get_post_thumbnail_id( $subscriptions_query->post->ID ) );
//						}
//						
//						// Set default image if not found
//						if (!$service_image_url) {
//							$service_image_url = 'https://cdn.banyanhill.com/wp-content/uploads/2014/10/06072331/banyan-logo-New.png';
//						}						
//
//						wp_reset_postdata();
//					}
					
					$protected_overlay = '<a href="' . $service_info_home . '" target="_blank">';
					$protected_overlay .= '<div class="subscription-block-container">';
					$protected_overlay .= '<div class="subscription-image"><img src="' . $service_image_url . '" /></div>';
					$protected_overlay .= '<div class="subscription-text">';
					$protected_overlay .= '<div class="subscription-copy">Want Access? <span>Subscribe Now!</span></div>';
					$protected_overlay .= '<div class="subscribe-button">GET ACCESS</div>';	
					$protected_overlay .= '</div>';
					$protected_overlay .= '</div>';
					$protected_overlay .= '</a>';
				}			
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry ' . $post_format_class ); ?> data-pubcode="<?php echo strtolower( get_the_terms( get_the_ID(), 'pubcode' )[0]->name ); ?>">
						<?php
						if ( !in_array( $post_format, array( 'quote', 'link' ) ) ) {
							if (!$is_protected) {
						?>
						<div class="header">
							<?php
							$thumb_args = array(
								'size'      => 'extra-image-medium',
								'img_after' => '<span class="et_pb_extra_overlay"></span>',
							);
							require locate_template( 'post-top-content.php' );
							?>
						</div>
						<?php } ?>
						<div class="post-content">							
							<div class="subscription-content">
								<?php $color = extra_get_post_category_color(); ?>
								<h2 class="post-title entry-title"><a class="et-accent-color" style="color:<?php echo esc_attr( $color ); ?>;" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<div class="post-meta vcard">
								<?php
									if ( in_array( 'archives', get_post_class($post->ID) ) ) {
										$archive_args = array(
											'author_link'	=> false,
											'comment_count'  => false,
											'rating_stars'   => false,										
										);
								?>
									<p><?php echo et_extra_display_post_meta($archive_args) . ' | ' . '<a href="' . get_the_permalink( $subscriptions_query->post->ID ) . '">' . wp_get_post_terms($post->ID, 'archives-category')[0]->name . '</a>'; ?></p>
								<?php 
									} else {
										$standard_args = array(
											'author_link'	=> true,
											'comment_count'  => false,
											'rating_stars'   => false,										
										);									
								?>
									<p><?php echo et_extra_display_post_meta($standard_args); ?></p>
								<?php
									}
								?>
								</div>									
								<div class="excerpt entry-summary">
									<p><?php echo $post_content; ?></p>
								</div>
							</div>
							<?php
							if ($is_protected) echo $protected_overlay;
							?>							
						</div>
						<?php } ?>
					</article>
				<?php
			endwhile;
		else :
			?>
			<article class='nopost'>
				<h5><?php esc_html_e( 'Sorry, No Posts Found', 'extra' ); ?></h5>
			</article>
			<?php
		endif;
		?>
		</div><!-- .paginated_page -->
	</div><!-- .paginated_content -->

	<?php global $wp_query; ?>
	<?php if ( $wp_query->max_num_pages > 1 ) { ?>
	<div class="archive-pagination">
		<?php echo extra_archive_pagination(); ?>
	</div>
	<?php } ?>
</div><!-- /.posts-blog-feed-module -->
