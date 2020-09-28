<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

/**
 * Options Page settings
 * @var $settings
 */
$settings = array(
	'header_title' => __( 'Knowledge Base', 'pressapps-knowledge-base' ),
	'menu_title'   => __( 'Knowledge Base', 'pressapps-knowledge-base' ),
	'menu_type'    => 'add_submenu_page',
	'menu_slug'    => 'pressapps-knowledge-base',
	'ajax_save'    => false,
);

/**
 * Options sections & fields
 * @var $options
 */
$options = array();

/**
 * General Tab Section & options
 */
$options[] = array(
	'name'   => 'kb-general',
	'title'  => __( 'General', 'pressapps-knowledge-base' ),
	'icon'   => 'si-gear-pok',
	'fields' => array(
		array(
			'id'      => 'kb_template',
			'type'    => 'select',
			'title'   => __( 'Page Template', 'pressapps-knowledge-base' ),
			'options' => array_merge( array( 'page.php' => 'Default Template' ), wp_get_theme()->get_page_templates() ),
			'default' => 'page.php',
			'desc'    => 'Applies to caregory and single pages',
		),
		array(
			'id'      => 'kb_slug',
			'type'    => 'text',
			'title'   => __( 'Article Slug', 'pressapps-knowledge-base' ),
			'default' => 'knowledgebase',
			'desc'    => 'Must be unique, not used by any page, post or category!',
		),
		array(
			'id'      => 'kbcat_slug',
			'type'    => 'text',
			'title'   => __( 'Category Slug', 'pressapps-knowledge-base' ),
			'default' => 'kb',
			'desc'    => 'Must be unique, not used by any page, post or category!',
		),
		array(
			'id'    => 'breadcrumbs',
			'type'  => 'switcher',
			'title' => __( 'Breadcrumbs', 'pressapps-knowledge-base' ),
			'default'    => true,
		),
		array(
			'id'         => 'breadcrumb_text',
			'type'       => 'text',
			'title'      => __( 'Breadcrumb Text', 'pressapps-knowledge-base' ),
			'default'    => __( 'Knowledge Base', 'pressapps-knowledge-base' ),
			'dependency' => array( 'pakb_breadcrumbs', '==', 'true' )
		),
		array(
			'id'      => 'reorder',
			'type'    => 'radio',
			'title'   => __( 'Order Content', 'pressapps-knowledge-base' ),
			'options' => array(
				'default'        => __( 'Default Order', 'pressapps-knowledge-base' ),
				'reorder'        => __( 'Drag and Drop Reorder', 'pressapps-knowledge-base' ),
				'alphabetically' => __( 'Alphabetical Order', 'pressapps-knowledge-base' )
			),
			'default' => 'default'
		),
	)
);

/**
 * Main page options
 */
$options[] = array(
	'name'   => 'kb-main',
	'title'  => __( 'Main Page', 'pressapps-knowledge-base' ),
	'icon'   => 'si-home-address',
	'fields' => array(
		array(
			'id'             => 'kb_page',
			'type'           => 'select',
			'title'          => __( 'Main Knowledge Base Page', 'pressapps-knowledge-base' ),
			'options'        => 'pages',
			'default_option' => 'Select a page'
		),
		array(
			'id'             => 'kb_page_layout',
			'type'           => 'image_select',
			'title'          => __( 'Layout', 'pressapps-knowledge-base' ),
			'options' => array(
				1 => plugin_dir_url( dirname( __FILE__ ) ) . 'img/lists.png',
				2 => plugin_dir_url( dirname( __FILE__ ) ) . 'img/boxes.png',
			),
			'default' => 1,
		),

		array(
			'id'      => 'columns',
			'type'    => 'image_select',
			'title'   => __( 'Columns', 'pressapps-knowledge-base' ),
			'options' => array(
				'2' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/2col.png',
				'3' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/3col.png',
				'4' => plugin_dir_url( dirname( __FILE__ ) ) . 'img/4col.png',
			),
			'default' => '2col',
		),
		array(
			'id'             => 'layout_main',
			'type'           => 'sorter',
			'title'          => __( 'Page Layout', 'pressapps-knowledge-base' ),
			'default'        => array(
				'enabled'		=> array(
					'main'		=> __( 'Knowledge Base', 'pressapps-knowledge-base' ),
				),
				'disabled'     => array(
					'content'	=> __( 'Main Page Content', 'pressapps-knowledge-base' ),
					'sidebar'	=> __( 'Horizontal Sidebar', 'pressapps-knowledge-base' ),
				),
			),
			'enabled_title'  => __( 'Enabled', 'pressapps-knowledge-base' ),
			'disabled_title' => __( 'Disabled', 'pressapps-knowledge-base' ),
  		),
		array(
			'id'         => 'icon_cat',
			'type'       => 'switcher',
			'title'      => __( 'Category Icon', 'pressapps-knowledge-base' ),
			'default'    => true,
		),
		array(
			'id'    => 'category_count',
			'type'  => 'switcher',
			'title' => __( 'Category Count', 'pressapps-knowledge-base' ),
			'default'    => true,
			'dependency' => array( 'pakb_kb_page_layout_1', '==', 'true' )
		),
		array(
			'id'    => 'view_all',
			'type'  => 'switcher',
			'title' => __( 'View All Link', 'pressapps-knowledge-base' ),
			'default'    => true,
		),
		array(
			'id'    => 'view_all_count',
			'type'  => 'switcher',
			'title' => __( 'View All Count', 'pressapps-knowledge-base' ),
			'default'    => true,
			'dependency' => array( 'pakb_view_all', '==', 'true' )
		),
		array(
			'id'      => 'posts_per_cat',
			'type'    => 'number',
			'title'   => __( 'Articles Per Category', 'pressapps-knowledge-base' ),
			'default' => '5',
			'dependency' => array( 'pakb_kb_page_layout_1', '==', 'true' )
		),

	)
);

/**
 * Single Tab Section & options
 */
$options[] = array(
	'name'   => 'kb-single',
	'title'  => __( 'Single Page', 'pressapps-knowledge-base' ),
	'icon'   => 'si-document',
	'fields' => array(
		array(
			'id'      => 'meta_display',
			'type'    => 'switcher',
			'title'   => __( 'Display Meta', 'pressapps-knowledge-base' ),
			'default' => false,
		),
		array(
			'id'       => 'meta',
			'type'     => 'checkbox',
			'title'    => 'Meta Info',
			'options'  => array(
				'updated'	=> 'Updated',
				'category'	=> 'Category',
				'tags'		=> 'Tags',
			),
			'default'  => array( 'category', 'updated', 'tags' ),
			'dependency' => array( 'pakb_meta_display', '==', 'true' )
		),
		array(
			'id'    => 'toc',
			'type'  => 'switcher',
			'title' => __( 'Table of Contents', 'pressapps-knowledge-base' ),
			'default' => true
		),
		array(
			'id'         => 'toc_title',
			'type'       => 'text',
			'title'      => __( 'Title', 'pressapps-knowledge-base' ),
			'default'    => 'Article sections',
			'dependency' => array( 'pakb_toc', '==', 'true' ),
			'desc'  => __( 'Title for table of contents', 'pressapps-knowledge-base' ),
		),
		array(
			'id'      => 'toc_scroll_offset',
			'type'    => 'number',
			'title'   => __( 'Scroll Offset', 'pressapps-knowledge-base' ),
			'default' => '100',
			'after'	  => 'px',
			'dependency' => array( 'pakb_toc', '==', 'true' ),
			'desc'  => __( 'Scroll offset from top for table of contents', 'pressapps-knowledge-base' ),
		),
		array(
			'id'         => 'toc_selectors',
			'type'       => 'text',
			'title'      => __( 'Heading Selectors', 'pressapps-knowledge-base' ),
			'default'    => 'h2,h3,h4',
			'dependency' => array( 'pakb_toc', '==', 'true' ),
			'desc'  => __( 'Comma separated selectors for table of contents', 'pressapps-knowledge-base' ),
		),
		array(
			'id'    => 'comments',
			'type'  => 'switcher',
			'title' => __( 'Comments', 'pressapps-knowledge-base' ),
			'desc'  => __( 'Requires theme support', 'pressapps-knowledge-base' ),
		),
		array(
			'id'    => 'related_articles',
			'type'  => 'switcher',
			'title' => __( 'Related Articles', 'pressapps-knowledge-base' ),
			'default' => true
		)
	)
);

/**
 * Search Tab Section & options
 */
$options[] = array(
	'name'   => 'kb-search',
	'title'  => __( 'Search', 'pressapps-knowledge-base' ),
	'icon'   => 'si-find',
	'fields' => array(
		array(
			'id'       => 'search_display',
			'type'     => 'checkbox',
			'title'    => 'Display Search',
			'options'  => array(
				'main'		=> 'Main Page',
				'category'	=> 'Category Page',
				'single'	=> 'Single Page',
			),
			'default'  => array( 'main', 'category', 'single' ),
		),
		array(
			'id'         => 'searchbox_placeholder',
			'type'       => 'text',
			'title'      => __( 'Placeholder', 'pressapps-knowledge-base' ),
			'default'    => 'Search for answers',
		),
		array(
			'id'         => 'live_search',
			'type'       => 'switcher',
			'title'      => __( 'Live Search', 'pressapps-knowledge-base' ),
			'default'    => true,
		),
		array(
			'id'         => 'search_show_cat',
			'type'       => 'switcher',
			'title'      => __( 'Display Categories in Results', 'pressapps-knowledge-base' ),
			'default'    => true,
			'dependency' => array( 'pakb_live_search', '==', 'true' )
		),

	)
);

/**
 * Voting Tab Section & options
 */
$options[] = array(
	'name'   => 'kb-voting',
	'title'  => __( 'Voting', 'pressapps-knowledge-base' ),
	'icon'   => 'si-heart',
	'fields' => array(
		array(
			'id'      => 'voting',
			'type'    => 'radio',
			'title'   => __( 'Voting', 'pressapps-knowledge-base' ),
			'options' => array(
				'0' => __( 'Disabled', 'pressapps-knowledge-base' ),
				'1' => __( 'Public Voting', 'pressapps-knowledge-base' ),
				'2' => __( 'Logged In Users Only', 'pressapps-knowledge-base' ),
			),
			'default' => '0',
		),
		array(
			'id'         => 'vote_title',
			'type'       => 'text',
			'title'      => __( 'Title', 'pressapps-knowledge-base' ),
			'default'    => 'Did this article answer your question?',
			'dependency' => array( 'pakb_voting_0', '!=', 'true' )
		),
		array(
			'id'         => 'vote_thanks',
			'type'       => 'text',
			'title'      => __( 'Thanks', 'pressapps-knowledge-base' ),
			'default'    => 'Thanks for your feedback!',
			'dependency' => array( 'pakb_voting_0', '!=', 'true' )
		),
		array(
			'id'         => 'vote_up_icon',
			'type'       => 'icon',
			'title'      => __( 'Vote Up Icon', 'pressapps-knowledge-base' ),
			'default'    => 'si-heart',
			'dependency' => array( 'pakb_voting_0', '!=', 'true' )
		),
		array(
			'id'         => 'vote_down_icon',
			'type'       => 'icon',
			'title'      => __( 'Vote Down Icon', 'pressapps-knowledge-base' ),
			'default'    => 'si-dislike',
			'dependency' => array( 'pakb_voting_0', '!=', 'true' )
		),
		array(
			'id'           => 'vote_reset_all',
			'type'         => 'button',
			'title'        => __( 'Reset All Votes', 'pressapps-knowledge-base' ),
			'button_title' => __( 'Reset All Votes', 'pressapps-knowledge-base' ),
			'dependency'   => array( 'pakb_voting_0', '!=', 'true' )
		),
	)
);

/**
 * Style Tab & Options fields
 */
$options[] = array(
	'name'   => 'kb-style',
	'title'  => __( 'Styling', 'pressapps-knowledge-base' ),
	'icon'   => 'si-brush',
	'fields' => array(
		array(
			'id'      => 'accent_color',
			'type'    => 'color_picker',
			'title'   => __( 'Accent Color', 'pressapps-knowledge-base' ),
			'default' => '#03A9F4',
		),
		array(
			'id'      => 'primary_color',
			'type'    => 'color_picker',
			'title'   => __( 'Primary Color', 'pressapps-knowledge-base' ),
			'default' => '#000000',
		),
		array(
			'id'      => 'secondary_color',
			'type'    => 'color_picker',
			'title'   => __( 'Secondary Color', 'pressapps-knowledge-base' ),
			'default' => '#A9AAAB',
			'desc'  => __( 'Breadcrumbs, article meta, view all links.', 'pressapps-knowledge-base' ),
		),
		array(
			'id'      => 'box_icon_size',
			'type'    => 'number',
			'title'   => __( 'Category Icon Size', 'pressapps-knowledge-base' ),
			'default' => '54',
			'after'	  => 'px',
			'desc'  => __( 'Applies to boxed layout only.', 'pressapps-knowledge-base' ),
			'dependency' => array( 'pakb_kb_page_layout_2|pakb_icon_cat', '==|==', 'true|true' )
		),
		array(
			'id'      => 'cat_size',
			'type'    => 'number',
			'title'   => __( 'Category Font Size', 'pressapps-knowledge-base' ),
			'default' => '26',
			'after'	  => 'px',
		),
		array(
			'id'    => 'custom_css',
			'type'  => 'textarea',
			'title' => __( 'Custom CSS', 'pressapps-knowledge-base' ),
			'desc'  => __( 'You can add and override CSS styles here.', 'pressapps-knowledge-base' ),
		)
	)
);

/**
 * Style Tab & Options fields
 */
$options[] = array(
	'name'   => 'kb-extend',
	'title'  => __( 'Extensions', 'pressapps-knowledge-base' ),
	'icon'   => 'si-rocket',
	'fields' => array(
		array(
		  'type'    => 'subheading',
		  'content' => 'Helpdesk Theme',
		  'info'  => __( 'WordPress theme build specificaly for knowledge base plugin - <a href="https://www.dropbox.com/s/8ch40685w6q1nje/helpdesk.zip?dl=1" target="_blank">Download</a>', 'pressapps-knowledge-base' ),
		),
		array(
		  'type'    => 'subheading',
		  'content' => 'Contextual Sidebar Addon',
		  'info'  => __( 'Allow customers to access all knowledge base articles anywhere on the site without reloading the page. - <a href="https://codecanyon.net/item/pressapps-knowledge-base-contextual-sidebar-addon/21091013" target="_blank">Purchase</a>', 'pressapps-knowledge-base' ),
		),
	)
);

// Register Framework page settings and options fields
SkeletFramework::instance( $settings, $options );
