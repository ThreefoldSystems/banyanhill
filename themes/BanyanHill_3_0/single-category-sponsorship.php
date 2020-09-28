<?php 
add_filter( 'wpseo_robots', function() { return 'noindex, nofollow'; } );
get_header( 'category-sponsorship' ); 

if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		$sponsored_image = get_the_post_thumbnail_url($post->ID, array(627,376));
		$sponsored_excerpt = trim(strip_tags(apply_filters('the_excerpt', get_post_field('post_excerpt', $post->ID)), '<a>'));
		$sponsored_link = get_post_meta($post->ID, 'sponsorshipLink', true);
		$sponsored_title = str_replace('Private: ', '', get_the_title());
		$sponsored_post_meta = et_extra_display_post_meta( array(
			'post_id'      => $post->ID,
			'rating_stars' => false,
			'categories'   => true,
			'comment_count'	   => false,
			'post_date'	=> false
		) );
		$sponsored_post_parts = explode('|', $sponsored_post_meta);
		$sponsored_post_meta = $sponsored_post_parts[0] . ' | <span id="todaysDate"></span> | ' . $sponsored_post_parts[1];

		if ( empty( $sponsored_excerpt ) ) {
			$sponsored_excerpt = wp_kses_post( wp_trim_words( $sponsored_content_post->post_content, 13 ) );
		}
	?>
	<div id="sponsored-container">
		<div id="sponsored-post" class="post category-sponsorship">
			<div class="header">
				<a href="<?php echo $sponsored_link; ?>" title="<?php echo $sponsored_title; ?>" class="featured-image" target="_blank">
					<img src="<?php echo $sponsored_image; ?>" alt="<?php echo $sponsored_title; ?>">
					<span class="et_pb_extra_overlay"></span>
				</a>		
			</div>
			<div class="post-content">
				<h2 class="post-title entry-title">
					<a href="<?php echo $sponsored_link; ?>" title="<?php echo $sponsored_title; ?>" class="et-accent-color" target="_blank"><?php echo $sponsored_title; ?></a>
				</h2>
				<div class="post-meta vcard">
					<p><?php echo $sponsored_post_meta; ?></p>
				</div>
				<div class="excerpt">
					<p>Special: <?php echo $sponsored_excerpt; ?></p>
				</div>
			</div>
		</div>
	</div>
	<button id="copy">Copy to Clipboard</button>
	<script>
		var trackingUrl = '<?php echo $sponsored_link; ?>';
		var targetUrls = document.getElementsByTagName("a");

		function updateUrls(trackingUrl) {
			for (var index = 0; index < targetUrls.length; index++) {
				if (targetUrls[index].getAttribute("href") !== undefined) {
					targetUrls[index].setAttribute("href", trackingUrl);
					targetUrls[index].setAttribute("target", "_blank");
				}
			}
		}

		updateUrls(trackingUrl);
	</script>
	<script type="text/javascript">
		// Copies a string to the clipboard. Must be called from within an event handler such as click.
		// May return false if it failed, but this is not always
		// possible. Browser support for Chrome 43+, Firefox 42+, Edge and IE 10+.
		// No Safari support, as of (Nov. 2015). Returns false.
		// IE: The clipboard feature may be disabled by an adminstrator. By default a prompt is
		// shown the first time the clipboard is used (per session).
		function copyToClipboard(text) {
			if (window.clipboardData && window.clipboardData.setData) {
				// IE specific code path to prevent textarea being shown while dialog is visible.
				return clipboardData.setData("Text", text); 

			} else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
				var textarea = document.createElement("textarea");
				textarea.textContent = text;
				textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
				document.body.appendChild(textarea);
				textarea.select();
				try {
					return document.execCommand("copy");  // Security exception may be thrown by some browsers.
				} catch (ex) {
					console.warn("Copy to clipboard failed.", ex);
					return false;
				} finally {
					document.body.removeChild(textarea);
				}
			}
		}

		document.querySelector("#copy").onclick = function() {
			var result = copyToClipboard(document.querySelector('#sponsored-container').innerHTML);
			console.log("copied?", result);
		};
	</script>
<?php endwhile;
else :
	?>
	<h2><?php esc_html_e( 'Post not found', 'extra' ); ?></h2>
	<?php
endif;
wp_reset_query();
?>