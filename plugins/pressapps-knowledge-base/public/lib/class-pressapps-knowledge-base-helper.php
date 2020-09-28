<?php

class PAKB_Helper {

	/**
	 * Search function.
	 *
	 * @since 1.0.0
	 */
	public function the_search() {
		global $pakb, $pakb_loop;

		$search_ptxt          = trim( strip_tags( $pakb->get( 'searchbox_placeholder' ) ) );
		?>
			<div class="uk-margin-medium-bottom">
				<form role="search" class="uk-search uk-search-large uk-search-default" method="post" id="kbsearchform" action="<?php echo home_url( '/' ); ?>">
					<button type="submit" id="searchsubmit" data-uk-search-icon></button><input type="text" value="<?php if ( is_search() ) { echo get_search_query(); } ?>" name="s" placeholder="<?php echo ( ! empty( $search_ptxt ) ) ? $search_ptxt : ''; ?>" id="kb-s" class="uk-search-input <?php echo ( $pakb->get( 'live_search' ) ) ? 'autosuggest' : ''; ?>"/><input type="hidden" name="post_type" value="knowledgebase"/><?php wp_nonce_field( 'knowedgebase-search', 'search_nonce', false ); ?>
				</form>
			</div>
		<?php
	}

	/**
	 * Includes the file.
	 *
	 * @since 1.0.0
	 *
	 * @param $filename
	 *
	 * @return string
	 */
	public function load_file( $filename ) {
		ob_start();
		include $filename;

		return ob_get_clean();
	}

	/**
	 * Getting template files.
	 *
	 * @since 1.0.0
	 *
	 * @param string $case
	 *
	 * @return string
	 */
	public function get_template_files( $case = 'single' ) {

		$default_path = plugin_dir_path( dirname( __FILE__ ) ) . 'partials/';
		$theme_path   = get_stylesheet_directory() . '/pakb/';

		switch ( $case ) {
			case 'search':
				$filename = 'knowledgebase-search.php';
				break;
			case 'archive':
				$filename = 'knowledgebase-archive.php';
				break;
			case 'single':
			default :
				$filename = 'knowledgebase-single.php';
				break;
			case 'category':
				$filename = 'knowledgebase-category.php';
				break;
			case 'knowledgebase':
				$filename = 'knowledgebase.php';
				break;
		}

		$default_file = $default_path . $filename;
		$theme_file   = $theme_path . $filename;

		// Modification ref issue #15 to support plugin overrides
		// return ( ( file_exists( $theme_file ) ) ? $theme_file : $default_file );

		$located_file = ( ( file_exists( $theme_file ) ) ? $theme_file : $default_file );
		return apply_filters( 'pakb_load_template', $located_file, $filename );

	}

	/**
	 * Overriding variable.
	 *
	 * @since 1.0.0
	 */
	public function override_is_var() {
		global $wp_query;

		$wp_query->is_tax               = false;
		$wp_query->is_archive           = false;
		$wp_query->is_search            = false;
		$wp_query->is_single            = false;
		$wp_query->is_post_type_archive = false;
		$wp_query->is_404 = false;
		$wp_query->is_singular = true;
		$wp_query->is_page     = true;
	}

	/**
	 * Template function for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function page_template( $template ) {
		global $pakb;

		$kb_page = get_post( $pakb->get( 'kb_page' ) );

		$id       = $kb_page->ID;

		if ( is_pakb_main() ) {
			$template = get_page_template_slug( $kb_page->ID );
		}

		$pagename = $kb_page->post_name;

		$templates = array();

		if ( $template && 0 === validate_file( $template ) ) {
			$templates[] = $template;
		}

		//check if skelet option page template has been set and will use that template
		if ( $pakb->get( 'kb_template' ) && $pakb->get( 'kb_template' ) !== 'page.php' ) {
			$template_name = str_replace( '.php', '', basename( $pakb->get( 'kb_template' ) ) );

			$templates[] = $pakb->get( 'kb_template' );

			return get_query_template( $template_name, $templates );

		} else {
			if ( $pagename ) {
				$templates[] = "page-$pagename.php";
			}
			if ( $id ) {
				$templates[] = "page-$id.php";
			}

			$templates[] = 'page.php';

			return get_query_template( 'page', $templates );
		}



	}

	/**
	 * Post data function.
	 *
	 * @since 1.0.0
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public function get_dummy_post_data( $args ) {

		return array_merge( array(
			'ID'                    => 0,
			'post_status'           => 'publish',
			'post_author'           => 0,
			'post_parent'           => 0,
			'post_type'             => 'page',
			'post_date'             => 0,
			'post_date_gmt'         => 0,
			'post_modified'         => 0,
			'post_modified_gmt'     => 0,
			'post_content'          => '',
			'post_title'            => '',
			'post_excerpt'          => '',
			'post_content_filtered' => '',
			'post_mime_type'        => '',
			'post_password'         => '',
			'post_name'             => '',
			'guid'                  => '',
			'menu_order'            => 0,
			'pinged'                => '',
			'to_ping'               => '',
			'ping_status'           => '',
			'comment_status'        => 'closed',
			'comment_count'         => 0,
			'filter'                => 'raw',
		), $args );
	}

	/**
	 * Function for casting votes.
	 *
	 * @since 1.0.0
	 *
	 * @param bool|false $is_ajax
	 */
	public function the_votes( $id = '', $is_ajax = false ) {

		global $post, $pakb;
		if( !$id ){
			$id = $post->ID;
		}

		$votes_like        = (int) get_post_meta( $id, '_votes_likes', true );
		$votes_dislike     = (int) get_post_meta( $id, '_votes_dislikes', true );
		$voted_like        = sprintf( _n( '%s person found this helpful', '%s people found this helpful', $votes_like, 'pressapps-knowledge-base' ), $votes_like );
		$voted_dislike     = sprintf( _n( '%s person did not find this helpful', '%s people did not find this helpful', $votes_dislike, 'pressapps-knowledge-base' ), $votes_dislike );
		$vote_like_link    = __( "I found this helpful", 'pressapps-knowledge-base' );
		$vote_dislike_link = __( "I did not find this helpful", 'pressapps-knowledge-base' );
		$cookie_vote_count = '';
		$vote_title        = trim( strip_tags( $pakb->get( 'vote_title' ) ) );
		$vote_thanks       = trim( strip_tags( $pakb->get( 'vote_thanks' ) ) );

		if ( isset( $_COOKIE['vote_count'] ) ) {
			$cookie_vote_count = @unserialize( base64_decode( $_COOKIE['vote_count'] ) );
		}

		if ( ! is_array( $cookie_vote_count ) && isset( $cookie_vote_count ) ) {
			$cookie_vote_count = array();
		}

		echo( ( $is_ajax ) ? '' : '<div class="votes uk-margin-large-top">' );

		if ( ! empty ( $vote_title ) ) {
			echo '<div class="uk-text-center mb-l text-l">' . $vote_title . '</div>';
		}

		if ( is_user_logged_in() || $pakb->get( 'voting' ) == 1 ) :

			if ( is_user_logged_in() ) {
				$vote_count = (array) get_user_meta( get_current_user_id(), 'vote_count', true );
			} else {
				$vote_count = $cookie_vote_count;
			}

			if ( ! in_array( $id, $vote_count ) ) {
				echo '<div class="uk-flex uk-flex-center">';
				echo '<div class="uk-text-right"><a title="' . esc_attr( $vote_like_link ) . '" class="pakb-like-btn pakb-accent-color" data-uk-tooltip href="#" onclick="return false" post_id="' . esc_attr( $id ) . '"><i class="'. esc_attr( $pakb->get( 'vote_up_icon' ) ) .'"></i></a></div>';
				echo '<div class="uk-text-left"><a title="' . esc_attr( $vote_dislike_link ) . '" class="pakb-dislike-btn pakb-accent-color" data-uk-tooltip href="#" onclick="return false" post_id="' . esc_attr( $id ) . '"><i class="'. esc_attr( $pakb->get( 'vote_down_icon' ) ) .'"></i></a></div>';
				echo '</div>';
			} else {
				// already voted
				echo '<div class="uk-flex uk-flex-center">';
				echo '<div title="' . esc_attr( $voted_like ) . '" class="uk-text-right pakb-like-btn" data-uk-tooltip><i class="'. esc_attr( $pakb->get( 'vote_up_icon' ) ) .'"></i></div>';
				echo '<div title="' . esc_attr( $voted_dislike ) . '" class="uk-text-left pakb-dislike-btn" data-uk-tooltip><i class="'. esc_attr( $pakb->get( 'vote_down_icon' ) ) .'"></i></div>';
				echo '</div>';
				if ( ! empty ( $vote_thanks ) ) {
					echo '<div class="uk-text-center mt-l">' . $vote_thanks . '</div>';
				}
			}

		else :
			// not logged in
			echo '<div class="uk-flex uk-flex-center">';
			echo '<div title="' . esc_attr( $voted_like ) . '" class="uk-text-right pakb-like-btn" data-uk-tooltip><i class="'. esc_attr( $pakb->get( 'vote_up_icon' ) ) .'"></i></div>';
			echo '<div title="' . esc_attr( $voted_dislike ) . '" class="uk-text-left pakb-dislike-btn" data-uk-tooltip><i class="'. esc_attr( $pakb->get( 'vote_down_icon' ) ) .'"></i></div>';
			echo '</div>';
		endif;

		//echo '</div>';

		echo( ( $is_ajax ) ? '' : '</div>' );
	}

	/**
	 * Filter function to fixed Array to string conversion notice
	 *
	 * @param  string $string
	 *
	 * @param string  $default
	 *
	 * @return string
	 */
	public function filtered_string( $string, $default = "" ) {
		if ( is_string( $string ) && strtolower( $string ) === 'array' ) {
			empty( $default ) ? $string = "" : $string = $default;
		} elseif ( is_array( $string ) ) {
			empty( $default ) ? $string = "" : $string = $default;
		}

		return $string;
	}

	/**
	 * Display related articles on single post
	 *
	 * @param $id
	 */
	public function display_related_articles( $id ) {

		$taxonomy   = 'knowledgebase_category';
		$post_terms = wp_get_post_terms( $id, $taxonomy );
		$post_array = array();

		if ( ! is_wp_error( $post_terms ) ) {

			foreach ( $post_terms as $post_term ) {
				$args = $args = array(
					'post_type'      => 'knowledgebase',
					'posts_per_page' => 6,
					'tax_query'      => array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $post_term->term_id
						)
					)
				);
				$post_array_objects = get_posts( $args );

				foreach ( $post_array_objects as $post_array_object ) {
					$post_array[] = $post_array_object->ID;
				}
			}
			echo '<div class="uk-margin-large-top pakb-primary">';
			printf( '<h3>%s</h3>', __( 'Related Articles', 'pressapps-knowledge-base' ) );
			//will check if the post id exist in the array and will remove
			$post_array = array_unique($post_array);
			echo '<ul class="uk-list uk-list-large pakb-list pakb-primary-color link-icon-right">';
			foreach ( $post_array as $index => $post_id ) {
				$post_object = get_post( $post_id ); ?>
				<li><?php printf( '<a id="%s" href="%s">%s</a>', $post_object->ID, get_permalink( $post_object->ID ), esc_html( $post_object->post_title ) ); ?></li>
			<?php }
			echo '</ul></div>';
		}
	}

	/**
	 * Helper function to check on the reorder option and return a specific orderby
	 *
	 * @param bool|false $is_category
	 *
	 * @return string
	 */
	public function reorder_option( $is_category = false ) {
		global $pakb;

		switch ( $pakb->get( 'reorder' ) ) {
			case 'default':
				$orderby = 'date';
				break;
			case 'reorder':
				$orderby = ( $is_category ) ? 'term_group' : 'menu_order';
				break;
			case 'alphabetically':
				$orderby = ( $is_category ) ? 'name' : 'title';
				break;
			default:
				$orderby = 'date';
				break;
		}

		return $orderby;
	}

}
