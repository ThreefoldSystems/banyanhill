<div id="lazyload-<?php the_ID(); ?>" data-attop="false">
	<?php
	do_action( 'et_before_post' );
	//$breadcrumb_title = get_option( 'wpseo_taxonomy_meta' )['category'][get_the_category( $post->ID )[0]->term_id]['wpseo_bctitle'] ? get_option( 'wpseo_taxonomy_meta' )['category'][get_the_category( $post->ID )[0]->term_id]['wpseo_bctitle'] : get_the_category( $post->ID )[0]->name;
	
	//$category_parent_id = get_the_category( $id )[0];
	//var_dump(get_the_category( $id )[0]);
	
	$post_categories = get_post_primary_category($post->ID); 
	$primary_category = $post_categories['primary_category']->name;
	
	parse_str($qs_source, $query_str);
	
	?>
	<p id="breadcrumbs">
		<span xmlns:v="http://rdf.data-vocabulary.org/#">
			<span typeof="v:Breadcrumb"><a href="<?php echo '//' . $_SERVER['HTTP_HOST']; ?>" rel="v:url" property="v:title">Home</a> » 
				<span rel="v:child" typeof="v:Breadcrumb">
					<?php
						echo get_category_parents( $post_categories['primary_category']->term_id, true, ' &raquo; ' );
					?>
					<span class="breadcrumb_last"><?php the_title(); ?></span>
				</span>
			</span>
		</span>
	</p>
	
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'module single-post-module' ); ?> data-url="<?php echo get_post($post->ID)->post_name; ?>" data-title="<?php echo get_post($post->ID)->post_title; ?>" data-category="<?php echo $primary_category; ?>">
		<?php if ( is_post_extra_title_meta_enabled() ) { ?>
		<div class="post-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<div class="post-meta vcard">
				<?php
					$post_expert_slug = strtolower( get_the_author_meta( 'first_name' ) . '-' . get_the_author_meta( 'last_name' ) );
					$post_expert_ID = get_page_by_path( $post_expert_slug, OBJECT, 'expert' )->ID;

					if ( get_the_post_thumbnail_url( $post_expert_ID, array( 150, 150 ) ) !== false && !empty($post_expert_ID) ) {
						$post_expert_thumbnail = '<img src="' . get_the_post_thumbnail_url( $post_expert_ID, array( 150, 150 ) ) . '" alt="' . get_the_title( $post_expert_ID ) .'" />';
					} else {
						$post_expert_thumbnail = get_avatar( get_the_author_meta( 'ID' ), 150 );
					}
				?>
				<p class="post-meta-author-avatar"><?php echo $post_expert_thumbnail; ?></p>
				<div>
					<p><?php echo extra_display_single_post_meta(); ?></p>
					<p><?php echo BH_reading_time(); ?> read</p>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if ( ( et_has_post_format() && et_has_format_content() ) || ( has_post_thumbnail() && is_post_extra_featured_image_enabled() ) ) { ?>
		<div class="post-thumbnail header">
			<?php
			$score_bar = extra_get_the_post_score_bar();
			$thumb_args = array( 'size' => 'extra-image-huge', 'link_wrapped' => false );
			require locate_template( 'post-top-content.php' );
			?>
		</div>
		<?php } ?>

		<?php $post_above_ad = extra_display_ad( 'post_above', false ); ?>
		<?php if ( !empty( $post_above_ad ) ) { ?>
		<div class="et_pb_extra_row etad post_above">
			<?php echo $post_above_ad; ?>
		</div>
		<?php } ?>

		<div class="post-wrap post-listing">
			<?php if ( !extra_is_builder_built() ) { ?>
			<div class="post-content entry-content">
				<?php the_content(); ?>
				<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'extra' ),
					'after' => '</div>',
				) );
				?>
			</div>
			<?php } else { ?>
			<?php et_builder_set_post_type(); ?>
			<?php the_content(); ?>
			<?php } ?>
			<!--ins data-revive-zoneid="22" data-revive-id="623abf93e179094d5059d128355ac65e" data-revive-keyword="<?php echo get_post_meta(get_the_ID(), 'mas_keywords', true); ?>"></ins-->						
			<script type="text/javascript">
				//document.querySelector('#post-<?php the_ID(); ?> .post-content.entry-content').insertBefore(document.querySelector('#post-<?php the_ID(); ?> ins[data-revive-zoneid="22"]'), document.querySelector('#post-<?php the_ID(); ?> .entry-content > *:nth-child(4)'));
				
				//inject video wrapper
				jQuery(document).ready(function($){
					$('#post-<?php the_ID(); ?> iframe[src*="youtube.com"]').wrap(function() {
						if ($(this).parent('fluid-width-video-wrapper').length === 0) return '<div class="fluid-width-video-wrapper" style="padding-top: 56.2963%;"></div>'
					});
				});
			</script>			
		</div>
		<?php if ( $review = extra_post_review() ) { ?>
		<div class="post-wrap post-listing post-wrap-review">
			<div class="review">
				<div class="review-title">
					<h3>
						<?php echo esc_html( $review['title'] ); ?>
					</h3>
				</div>
				<div class="review-content">
					<div class="review-summary clearfix">
						<div class="review-summary-score-box" style="background-color:<?php echo esc_attr( $post_category_color ); ?>">
							<h4>
								<?php printf( et_get_safe_localization( __( '%d%%', 'extra' ) ), absint( $review['score'] ) ); ?>
							</h4>
						</div>
						<div class="review-summary-content">
							<?php if ( !empty( $review['summary'] ) ) { ?>
							<p>
								<?php if ( !empty( $review['summary_title'] ) ) { ?>
								<strong>
									<?php echo esc_html( $review['summary_title'] ); ?>
								</strong>
								<?php } ?>
								<?php echo $review['summary']; ?>
							</p>
							<?php } ?>
						</div>
					</div>
					<div class="review-breakdowns">
						<?php foreach ( $review['breakdowns'] as $breakdown ) { ?>
						<div class="review-breakdown">
							<h5 class="review-breakdown-title">
								<?php echo esc_html( $breakdown['title'] ); ?>
							</h5>
							<div class="score-bar-bg">
								<span class="score-bar" style="background-color:<?php echo esc_attr( $post_category_color ); ?>; width:<?php printf( '%d%%', max( 4, absint( $breakdown['rating'] ) ) );  ?>">
									<span class="score-text"><?php printf( et_get_safe_localization( __( '%d%%', 'extra' ) ), absint( $breakdown['rating'] ) ); ?></span>
								</span>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php $post_below_ad = extra_display_ad( 'post_below', false ); ?>
		<?php if ( !empty( $post_below_ad ) ) { ?>
		<div class="et_pb_extra_row etad post_below">
			<?php echo $post_below_ad; ?>
		</div>
		<?php } ?>
	</article>
	<?php
	if ( extra_is_post_author_box() ) {
		?>
	<div class="et_extra_other_module author-box vcard">
		<div class="author-box-header">
			<h3>
				<?php esc_html_e( 'About The Author', 'extra' ); ?>
			</h3>
		</div>
		<div class="author-box-content clearfix">
			<div class="author-box-avatar">
				<?php /* echo get_avatar( get_the_author_meta( 'user_email' ), 170, 'mystery', esc_attr( get_the_author() ) ); */ ?>
				<?php echo $post_expert_thumbnail; ?>
			</div>
			<div class="author-box-description">
				<h4><a class="author-link url fn" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( et_get_safe_localization( __( 'View all posts by %s', 'extra' ) ), get_the_author() ); ?>"><?php echo get_the_author(); ?></a></h4>
				<p class="note">
					<?php the_author_meta( 'description' ); ?>
				</p>
				<ul class="social-icons">
					<?php foreach ( extra_get_author_contact_methods() as $method ) { ?>
					<li><a href="<?php echo esc_url( $method['url'] ); ?>" target="_blank"><span class="et-extra-icon et-extra-icon-<?php echo esc_attr( $method['slug'] ); ?> et-extra-icon-color-hover"></span></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>

	<?php } ?>
	<?php if ( empty($_COOKIE["is_signed_up"]) ) { ?>
	<div class="Newsletter_new well" style="margin-bottom: 30px;" data-qs="<?php echo isset($query_str['utm_campaign']) ? $query_str['utm_campaign'] : ''; ?>">
		<h3>Get Our Best Newsletters, Absolutely FREE!</h3>
		<div class="Newsletter_copy">
			<p>Sign up for FREE access to our daily newsletters <strong><em>Money and Markets</em></strong>, <strong><em>Investor's Daily Edge</em></strong> and <strong><em>Today's Profits</em></strong>, and join over 100,000 fellow Americans who have become insiders and have access to exclusive content!</p>
		</div>
		<?php echo do_shortcode( '[bh_signup_form buttontext="Sign Up" xcode="X190U407" position="well"]' ); ?>
	</div>
	<?php } ?>

	<!-- Related Posts -->
	<div class="et_extra_other_module related-posts post-<?php the_ID(); ?>">
		<?php 
			function getRandomId($exclude_ids) {
				$args = array( 
					'numberposts' => 10,
					'category_name' => get_the_category( $post->ID )[0]->cat_name,
					'exclude'		=> $exclude_ids
				);
				$posts = get_posts( $args );

				// Get IDs of posts retrieved from get_posts
				$ids = array();
				foreach ( $posts as $thepost ) {
					$ids[] = $thepost->ID;	
				}

				return $ids[array_rand($ids)];
			}							
			// Get and echo previous and next post in the same category
			// If we are at the beginning/end of the category get a random post
			$previd    = get_adjacent_post( true, '', true, 'category' )->ID;
			$nextid    = get_adjacent_post( true, '', false, 'category' )->ID;
			$excluded_ids = array(get_previous_post()->ID, get_next_post()->ID, $previd, $nextid, $post->ID);
			$previd    = isset( $previd ) && $previd !== $excluded_ids[0] ? $previd : getRandomId($excluded_ids);							
			$nextid    = isset( $nextid ) && $nextid !== $excluded_ids[1] ? $nextid : getRandomId($excluded_ids);							

			if ($primary_category !== 'Sponsorship') {

		?>
		<div class="related-posts-header">
			<h3>Recommended For You</h3>
		</div>
	<div class="related-posts-content clearfix">
		<div class="related-post">
			<div class="featured-image">
				<a href="<?php echo get_permalink($previd) ?>" rel="bookmark" title="<?php echo get_the_title($previd); ?>" class="post-thumbnail">
					<?php 
					$prev_related_thumb = get_the_post_thumbnail($previd, 'extra-image-small', array ( 'class' => 'attachment-extra-image-small size-extra-image-small loading' ));
					if ($prev_related_thumb) {
						$prev_related_thumb = str_replace('src=', 'data-src=', $prev_related_thumb);
						$prev_related_thumb = substr_replace($prev_related_thumb, 'src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==" ', 5, 0);
						$prev_related_thumb = str_replace('srcset=', 'data-srcset=', $prev_related_thumb);

						echo $prev_related_thumb;						
					}
					?>
					<span class="et_pb_extra_overlay"></span>
				</a>
			</div>
			<h4 class="title">
				<a href="<?php echo get_permalink($previd) ?>" rel="bookmark" title="<?php echo get_the_title($previd); ?>"><?php echo get_the_title($previd); ?></a>
			</h4>
		</div>							
		<!--ins class="related-post" data-revive-zoneid="29" data-revive-id="623abf93e179094d5059d128355ac65e"></ins-->
		<div class="related-post">
			<div class="featured-image">
				<a href="<?php echo get_permalink($nextid) ?>" rel="bookmark" title="<?php echo get_the_title($nextid); ?>" class="post-thumbnail">
					<?php 
					$next_related_thumb = get_the_post_thumbnail($nextid, 'extra-image-small', array ( 'class' => 'attachment-extra-image-small size-extra-image-small loading' ));
					if ($next_related_thumb) {
						$next_related_thumb = str_replace('src=', 'data-src=', $next_related_thumb);
						$next_related_thumb = substr_replace($next_related_thumb, 'src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==" ', 5, 0);
						$next_related_thumb = str_replace('srcset=', 'data-srcset=', $next_related_thumb);

						echo $next_related_thumb;						
					}
					?>
					<span class="et_pb_extra_overlay"></span>
				</a>
			</div>
			<h4 class="title">
				<a href="<?php echo get_permalink($nextid) ?>" rel="bookmark" title="<?php echo get_the_title($nextid); ?>"><?php echo get_the_title($nextid); ?></a>
			</h4>
		</div>
		<!--ins class="related-post" data-revive-zoneid="30" data-revive-id="623abf93e179094d5059d128355ac65e"></ins-->
		</div>
		<?php } ?>
	</div>
	<?php
		do_action( 'et_after_post' );
	?>
	<div class="disqus_thread_holder"></div>
</div>