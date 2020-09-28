<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://pressapps.co
 * @since      1.0.0
 *
 * @package    Pressapps_Knowledge_Base
 * @subpackage Pressapps_Knowledge_Base/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pressapps_Knowledge_Base
 * @subpackage Pressapps_Knowledge_Base/public
 * @author     PressApps
 */
class Pressapps_Knowledge_Base_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $skelet_path, $pakb;
		$theme = wp_get_theme(); // gets the current theme

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pressapps-knowledge-base-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'sk-icons', $skelet_path["uri"] . '/assets/css/sk-icons.css', array(), '1.0.0', 'all' );
		wp_add_inline_style( $this->plugin_name, wp_kses( $this->custom_css(), array( '\"', "\'" ) ) );
		if ( 'PressApps Helpdesk' == $theme->name || 'PressApps Helpdesk' == $theme->parent_theme ) {
			wp_enqueue_style( 'helpdesk', plugin_dir_url( __FILE__ ) . 'css/pressapps-knowledge-base-helpdesk.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $pakb;
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pressapps-knowledge-base-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'PAKB', array(
			'base_url' => esc_url( home_url() ),
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'category' => $pakb->get( 'search_show_cat' ),
			'noresult_placeholder' => __( 'No Results Found', 'pressapps-knowledge-base' ),
			'tocscrolloffset' => $pakb->get( 'toc_scroll_offset' )
		) );
	}

	function add_body_class( $classes ) {

		global $pakb;

		if( is_page( $pakb->get( 'kb_page' ) ) && ! is_null(  $pakb->get( 'kb_page' ) ) ) {
	        $classes[] = 'pakb-template-main';
		} elseif ( is_tax( 'knowledgebase_category' ) ) {
	        $classes[] = 'pakb-template-category';
		} elseif ( is_singular( 'knowledgebase' ) ) {
	        $classes[] = 'pakb-template-single';
		}

	    return $classes;

	}

	/**
	 * Registers all shortcodes at once
	 */
	public function register_shortcodes() {
		add_shortcode( 'pakb_articles', array( $this, 'articles_shortcode' ) );
	}

	/**
	 * Processes shortcode
	 */
	public function articles_shortcode( $atts ) {

		ob_start();

		$defaults['posts_per_page']	= '10';
		$defaults['orderby']		= 'date';
		$defaults['order']			= 'DESC';
		$defaults['filter']			= '';
		$defaults['category']		= '';
		$defaults['tag']			= '';
		$defaults['title']			= '';

		$args					= shortcode_atts( $defaults, $atts, 'pakb_articles' );
		$items 					= $this->get_knowledgebase_posts( $args );

		if ( $args['title'] ) {
			if ( $args['category'] != '' ) {
				$title = get_term( $args['category'], 'knowledgebase_category' );
				echo '<h3>' . $title->name . '</h3>';
			} elseif ( $args['tag'] != '' ) {
				$title = get_term( $args['tag'], 'knowledgebase_tags' );
				echo '<h3>' . $title->name . '</h3>';
			}
		}


		if ( is_array( $items ) || is_object( $items ) ) {

			include( plugin_dir_path( __FILE__ ) . 'partials/pressapps-knowledge-base-list-article.php' );

		} else {

			echo $items;

		}

		$output = ob_get_contents();

		ob_end_clean();

		return $output;

	}

	/**
	 * Returns a post object of knowledgebase posts
	 */
	private function get_knowledgebase_posts( $params ) {

		global $pakb;

		$return = '';

		$args['post_type'] 		= 'knowledgebase';
		$args['post_status'] 	= 'publish';
		$args['orderby'] 		= $params['orderby'];
		$args['order'] 			= $params['order'];
		$args['posts_per_page'] = $params['posts_per_page'];

		if ( $args['orderby'] == 'meta_value_num') {
			$args['meta_key']   = '_votes_likes';
		}


		if ( $params['filter'] == 'category' ) {
			$args['tax_query'] 		= array(
				array(
					'taxonomy'         => 'knowledgebase_category',
					'field'            => 'ID',
					'terms'            => $params['category'],
					'include_children' => true
				)
			);
		} elseif ( $params['filter'] == 'tag' ) {
			$args['tax_query'] 		= array(
				array(
					'taxonomy'         => 'knowledgebase_tags',
					'field'            => 'ID',
					'terms'            => $params['tag'],
					'include_children' => true
				)
			);
		}


		$query = new WP_Query( $args );

		if ( 0 == $query->found_posts ) {

			$return = '<p>There are no knowledge base articles.</p>';

		} else {

			$return = $query;

		}

		return $return;

	}

	/**
	 * Register sidebars
	 */
	public function sidebars_init() {

	    register_sidebar(array(
		    'name'          => __('Knowledge Base Main Horizontal', 'pressapps'),
		    'id'            => 'pakb-main',
		    'before_widget' => '<div class="widget %1$s %2$s">',
		    'after_widget'  => '</div>',
		    'before_title'  => '<h3>',
		    'after_title'   => '</h3>',
	    ));

	}

	/**
	 * Filters query on the public and attached to pre_get_posts filter
	 *
	 * @since 1.0.0
	 *
	 * @param $query
	 *
	 * @return mixed
	 */
	public function pre_get_posts_filter( $query ) {

		global $pakb;

		if ( ! is_admin() &&
			( $query->is_post_type_archive( 'knowledgebase' )
			  || $query->is_tax( 'knowledgebase_tags' ) || $query->is_tax( 'knowledgebase_category' )
			  || ( $query->is_search() &&
			       ( ( isset( $query->query_vars['post_type'] ) ) ? ( $query->query_vars['post_type'] == 'knowledgebase' ) : false )
			  )
			  || ( $pakb !== null && isset( $query->query_vars['page_id'] ) ? ( $query->query_vars['page_id'] == ( $pakb && $pakb->get( 'kb_page' ) ) ) : false )
			) && $query->is_main_query()
		) {
			$query->set( 'posts_per_page', - 1 );
			$query->set( 'posts_per_archive_page', - 1 );
		}

		return $query;
	}

	/**
	 * Filters template attached to template_include filter.
	 *
	 * @since 1.0.0
	 * @global WP_Query $wp_query
	 *
	 * @param           $template
	 *
	 * @return mixed
	 */
	public function template_include_filter( $template ) {

		global $wp_query, $pakb_query, $post, $pakb_cat, $pakb_tag, $pakb, $pakb_loop, $pakb_helper;

		$pakb_query = $wp_query;

		if ( get_option( 'kb_search_query' ) == "true" && ! is_search() ) {
			delete_option( 'kb_search_query' );
		}

		$is_search_query = ( get_option( 'kb_search_query' ) === "true" );

		if ( ( $wp_query->post_count >= 1 && //will check if there are any post to be displayed
			(
				is_post_type_archive( 'knowledgebase' ) || is_singular( 'knowledgebase' )
				|| is_tax( 'knowledgebase_category' ) || is_tax( 'knowledgebase_tags' )
				|| ( is_page( $pakb->get( 'kb_page' ) ) && ! is_null( $pakb->get( 'kb_page' ) ) )
				|| ( is_search() && ( isset( $_REQUEST['post_type'] ) ? ( $_REQUEST['post_type'] == 'knowledgebase' ) : false ) )
			) )
		     || $is_search_query // will override default theme search if it's a kb search
		) {


			$overridekb = false;
			$kb_page    = get_post( $pakb->get( 'kb_page' ) );

			if ( ! empty( $kb_page ) ) {
				if ( $pakb->get( 'kb_slug' ) == $kb_page->post_name ) {
					$overridekb = true;
				}
			}

			if ( ( is_post_type_archive( 'knowledgebase' ) && ! $overridekb ) || ( is_search() && $is_search_query )  ) {

				if ( is_search() && $is_search_query ) {
					$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
						'ID'           => isset( $wp_query->post ) ? $wp_query->post->ID : $kb_page->ID,
						'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'search' ) ),
						'post_title'   => sprintf( __( 'Search Result for "%s"', 'pressapps-knowledge-base' ), get_search_query() ),
					) ) );
					//pakb_override_is_var();
				} else {

					$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
						'ID'           => isset( $wp_query->post->ID ) ? $wp_query->post->ID : $kb_page->ID,
						'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'archive' ) ),
						'post_title'   => __( 'KB Archive', 'pressapps-knowledge-base' ),
					) ) );
				}

				/**
				 * @todo this is redundant Code we need to get this updated with 1 function call like pakb_override_is_var
				 */
				$wp_query->posts      = array( $post );
				$wp_query->post       = $post;
				$wp_query->post_count = 1;

			} elseif ( is_tax( 'knowledgebase_category' ) ) {

				$pakb_loop->process_cat();

				if ( $pakb_loop->has_sub_cat() ) {

					$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
						'ID'           => $pakb->get( 'kb_page' ),
						'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'knowledgebase' ) ),
						'post_title'   => $pakb_cat['main']->name,
					) ) );

				} else {
					$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
						'ID'           => $wp_query->post->ID,
						'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'archive' ) ),
						'post_title'   => $pakb_cat['main']->name,
					) ) );
				}

				$wp_query->posts      = array( $post );
				$wp_query->post       = $post;
				$wp_query->post_count = 1;


			} elseif ( is_tax( 'knowledgebase_tags' ) ) {

				$pakb_loop->process_tag();

				$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
					'ID'           => $wp_query->post->ID,
					'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'archive' ) ),
					'post_title'   => $pakb_tag['main']->name,
				) ) );

				$wp_query->posts      = array( $post );
				$wp_query->post       = $post;
				$wp_query->post_count = 1;


			} elseif (
				is_page( $pakb->get( 'kb_page' ) ) || ( is_post_type_archive( 'knowledgebase' ) && $overridekb ) ) {

				$pakb_loop->process_kbpage();

					$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
						'ID'           => $wp_query->post->ID,
						'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'knowledgebase' ) ),
						'post_title'   => $wp_query->post->post_title,
					) ) );

				$wp_query->posts      = array( $post );
				$wp_query->post       = $post;
				$wp_query->post_count = 1;

			} elseif ( is_singular( 'knowledgebase' ) ) {

				$post = new WP_Post( (object) $pakb_helper->get_dummy_post_data( array(
					'ID'           => $wp_query->post->ID,
					'post_content' => $pakb_helper->load_file( $pakb_helper->get_template_files( 'single' ) ),
					'post_title'   => $wp_query->post->post_title,
				) ) );

				$wp_query->posts      = array( $post );
				$wp_query->post       = $post;
				$wp_query->post_count = 1;
			}

			return $pakb_helper->page_template( $template );
		}


		return $template;
	}

	/**
	 * Attached to public pre_get_posts
	 *
	 * @param $query
	 */
	public function public_pre_get_posts( $query ) {
		global $pakb;
		if ( is_object( $pakb ) ) {

			switch ( $pakb->get( 'reorder' ) ) {
				case 'default':
					$orderby = 'date';
					break;
				case 'reorder':
					$orderby = 'menu_order';
					break;
				case 'alphabetically':
					$orderby = 'title';
					break;
				default:
					$orderby = 'date';
					break;
			}


			if ( ! is_admin() &&
			     ( $query->is_post_type_archive( 'knowledgebase' )
			       || $query->is_tax( 'knowledgebase_category' ) || $query->is_tax( 'knowledgebase_tags' )
			       || ( isset( $query->query_vars['page_id'] ) ? ( $query->query_vars['page_id'] == ( $pakb->get( 'kb_page' ) ) ) : false )
			       || ( $query->is_search() && ( isset( $_REQUEST['post_type'] ) ? ( $_REQUEST['post_type'] == 'knowledgebase' ) : false ) )
			     ) && $query->is_main_query()
			) {
				$query->set( 'orderby', $orderby );
				$query->set( 'order', 'ASC' );
			}
		}

	}

	/**
	 * Ajax live search function attached to wp_ajax_search_title & wp_ajax_nopriv_search_title.
	 *
	 * @since 1.0.0
	 * @global WPDB $wpdb
	 */
	public function live_search() {
		global $wpdb, $pakb, $pakb_helper;

		$search_input = $_REQUEST['query'];

		$live_search = $pakb->get( 'live_search' );

		if ( !$live_search ) {
			return;
		}

		if ( term_exists( $search_input, 'knowledgebase_tags' ) ) {
			$term_check = term_exists( $search_input, 'knowledgebase_tags' );
		} else {
			$term_check = false;
		}
		/*
		if ( $pakb->get( 'search_kb_tag' ) && $term_check ) {
			$search_qry = intval( $term_check['term_id'] );

			$qry = " SELECT pa.ID, pa.post_title, pa.post_type, pa.post_content as post_content, pa.post_name ";
			$qry .= " FROM {$wpdb->posts} AS pa INNER JOIN {$wpdb->term_relationships} AS pt ";
			$qry .= " ON pa.ID = pt.object_id AND pt.term_taxonomy_id = %s ";

			$sql_qry = $wpdb->prepare( $qry, $search_qry );
		} else {
		*/
			$search_qry   = "%" . $search_input . "%";

			$qry = ' SELECT ID, post_title, post_type, post_content as post_content, post_name ';
			$qry .= " FROM {$wpdb->posts} WHERE post_status = %s ";
			$qry .= " AND post_type = 'knowledgebase'  ";
			$qry .= " AND (post_title like %s or post_content like %s) ";

			$sql_qry = $wpdb->prepare( $qry, 'publish', $search_qry, $search_qry );
		/*
		}
		*/

		$search_json = array(
			"query"       => "Unit",
			"suggestions" => array(),
		);

		if ( $wpdb->get_results( $sql_qry ) ) {

			$results = $wpdb->get_results( $sql_qry );
			foreach ( $results as $result ) {

				if ( $pakb->get( 'search_show_cat' ) ) {
					$query_cats = wp_get_post_terms( $result->ID, 'knowledgebase_category' );
					$cat_output = array();

					//will display category
					foreach ( $query_cats as $query_cat ) {
						if ( $query_cat->parent ) {
							$cat_parent = get_term( $query_cat->parent, 'knowledgebase_category' );
							$cat_output[] = $cat_parent->name . ' <span>/</span> ' . $query_cat->name;
						} else {
							$cat_output[] = $query_cat->name;
						}
					}

					$search_json["suggestions"][] = array(
						"value" => $result->post_title,
						"data" => array( 'category' => implode( ' ', $cat_output ) ),
						"url"   => get_permalink( $result->ID ),
						"post_id"  => $result->ID
					);
				} else {
					$search_json["suggestions"][] = array(
						"value" => $result->post_title,
						"url"   => get_permalink( $result->ID ),
						"post_id"  => $result->ID
					);
				}

			}
		}



		echo json_encode( $search_json );
		die();
	}

	/**
	 * Voting function pass to init hook
	 *
	 * @since 1.0.0
	 */
	public function voting_init() {
		global $post, $pakb, $pakb_helper;

		if ( is_user_logged_in() ) {

			$vote_count = (array) get_user_meta( get_current_user_id(), 'vote_count', true );

			if ( isset( $_GET['pakb_vote_like'] ) && $_GET['pakb_vote_like'] > 0 ) :

				$post_id  = (int) $_GET['pakb_vote_like'];
				$the_post = get_post( $post_id );

				if ( $the_post && ! in_array( $post_id, $vote_count ) ) :
					$vote_count[] = $post_id;
					update_user_meta( get_current_user_id(), 'vote_count', $vote_count );
					$post_votes = (int) get_post_meta( $post_id, '_votes_likes', true );
					$post_votes ++;
					update_post_meta( $post_id, '_votes_likes', $post_votes );
					$post = get_post( $post_id );
					$pakb_helper->the_votes( true );
					die( '' );
				endif;

			elseif ( isset( $_GET['pakb_vote_dislike'] ) && $_GET['pakb_vote_dislike'] > 0 ) :

				$post_id  = (int) $_GET['pakb_vote_dislike'];
				$the_post = get_post( $post_id );

				if ( $the_post && ! in_array( $post_id, $vote_count ) ) :
					$vote_count[] = $post_id;
					update_user_meta( get_current_user_id(), 'vote_count', $vote_count );
					$post_votes = (int) get_post_meta( $post_id, '_votes_dislikes', true );
					$post_votes ++;
					update_post_meta( $post_id, '_votes_dislikes', $post_votes );
					$post = get_post( $post_id );
					$pakb_helper->the_votes( true );
					die( '' );

				endif;

			endif;

		} elseif ( ! is_user_logged_in() && is_object( $pakb ) && method_exists( $pakb, 'get' ) && $pakb->get( 'voting' ) == 1 ) {

			// ADD VOTING FOR NON LOGGED IN USERS USING COOKIE TO STOP REPEAT VOTING ON AN ARTICLE
			$vote_count = '';

			if ( isset( $_COOKIE['vote_count'] ) ) {
				$vote_count = @unserialize( base64_decode( $_COOKIE['vote_count'] ) );
			}

			if ( ! is_array( $vote_count ) && isset( $vote_count ) ) {
				$vote_count = array();
			}

			if ( isset( $_GET['pakb_vote_like'] ) && $_GET['pakb_vote_like'] > 0 ) :

				$post_id  = (int) $_GET['pakb_vote_like'];
				$the_post = get_post( $post_id );

				if ( $the_post && ! in_array( $post_id, $vote_count ) ) :
					$vote_count[]          = $post_id;
					$_COOKIE['vote_count'] = base64_encode( serialize( $vote_count ) );
					setcookie( 'vote_count', $_COOKIE['vote_count'], time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
					$post_votes = (int) get_post_meta( $post_id, '_votes_likes', true );
					$post_votes ++;
					update_post_meta( $post_id, '_votes_likes', $post_votes );
					$post = get_post( $post_id );
					$pakb_helper->the_votes( true );
					die( '' );
				endif;

			elseif ( isset( $_GET['pakb_vote_dislike'] ) && $_GET['pakb_vote_dislike'] > 0 ) :

				$post_id  = (int) $_GET['pakb_vote_dislike'];
				$the_post = get_post( $post_id );

				if ( $the_post && ! in_array( $post_id, $vote_count ) ) :
					$vote_count[]          = $post_id;
					$_COOKIE['vote_count'] = base64_encode( serialize( $vote_count ) );
					setcookie( 'vote_count', $_COOKIE['vote_count'], time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );
					$post_votes = (int) get_post_meta( $post_id, '_votes_dislikes', true );
					$post_votes ++;
					update_post_meta( $post_id, '_votes_dislikes', $post_votes );
					$post = get_post( $post_id );
					$pakb_helper->the_votes( true );
					die( '' );

				endif;

			endif;

		} elseif ( ! is_user_logged_in() && is_object( $pakb ) && method_exists( $pakb, 'get' ) && $pakb->get( 'voting' ) == 2 ) {

			return;

		}

	}

	/**
	 * Custom CSS option for the plugin.
	 *
	 * @since 1.0.0
	 * @return string css style
	 */
	public function custom_css() {
		global $pakb;

		$custom_css = '';
		// Custom CSS
		if ( $pakb->get( 'custom_css' ) ) {
			$custom_css .= $pakb->get( 'custom_css' );
		}

		// Accent color
		$accent_color = sanitize_text_field( $pakb->get( 'accent_color' ) );
		if ( $accent_color ) {
			$custom_css .= '.uk-card-body:hover, .uk-search-default .uk-search-input:focus { border-color: ' . $accent_color . " }\n";
			$custom_css .= '.pakb-toc { border-left-color: ' . $accent_color . " }\n";
			$custom_css .= '.pakb-accent-color, .pakb-accent-color a, .pakb-accent-color a:hover, .autocomplete-suggestion { color: ' . $accent_color . " }\n";
			$custom_css .= '.uk-button-primary, .uk-button-primary:focus, .styled-ol li:before { background-color: ' . $accent_color . " }\n";
		}

		// Primary color
		$primary_color = sanitize_text_field( $pakb->get( 'primary_color' ) );
		if ( $primary_color ) {
			$custom_css .= '.pakb-primary-color, .pakb-primary-color a, .pakb-primary-color a:hover { color: ' . $primary_color . " }\n";
		}

		// Secondary color
		$secondary_color = sanitize_text_field( $pakb->get( 'secondary_color' ) );
		if ( $secondary_color ) {
			$custom_css .= '.pakb-secondary-color, .pakb-secondary-color a, .pakb-secondary-color a:hover, .uk-breadcrumb :last-child * { color: ' . $secondary_color . " }\n";
		}

		if ( $pakb->get( 'cat_size' ) ) {
			$custom_css .= '.pakb-boxes .uk-card-title, .pakb-lists h2 { font-size: ' . sanitize_text_field( $pakb->get( 'cat_size' ) ) . "px;}\n";
		}

		if ( $pakb->get( 'box_icon_size' ) ) {
			$custom_css .= '.pakb-boxes i { font-size: ' . sanitize_text_field( $pakb->get( 'box_icon_size' ) ) . "px;}\n";
		}

		//sanitize on output
		return $custom_css;
	}

	/**
	 * Filters the title of the post based on condition.
	 *
	 * @since 1.0.0
	 *
	 * @param      $title
	 * @param null $id
	 *
	 * @return string Title
	 */
	public function the_title_filter( $title, $id = null ) {
		global $pakb;

		//filters the title for the knowledgebase archive page
		if ( is_post_type_archive( 'knowledgebase' ) && ! is_admin() && in_the_loop() ) {

			//get the value of the kb that was set on skelet
			$archive_id = $pakb->get( 'kb_page' );

			if ( $archive_id ) {
				//create a post object based on the ID
				$archive_obj = get_post( $archive_id );
				$title       = $archive_obj->post_title;
			}
		}

		return $title;

	}

	public function search_post_query(  ) {

		if ( isset( $_POST['post_type'] ) && isset( $_POST['s'] ) && $_POST['search_nonce'] ) {
			wp_verify_nonce( $_POST['post_type'].'-search' );
			update_option( 'kb_search_query', 'true' );
		}
	}
}
