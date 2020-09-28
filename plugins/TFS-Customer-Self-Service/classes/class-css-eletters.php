<?php
/**
 * Manage eletters/listing in the plugin.
 */
class CSS_Eletters
{
    /**
     * CSS_Eletters constructor.
     */
    public function __construct()
    {
        // Register eletter custom post type
        add_action( 'init', array( $this, 'register_eletter_cpt' ), 0 );

        // Add meta boxes for e-letters
        add_action( 'add_meta_boxes', array( $this, 'metaboxes_eletter_add' ) );
        add_action( 'save_post', array( $this, 'metaboxes_eletter_save' ), 11, 3);
    }

    /**
     * Register eletter custom post type
     */
    public function register_eletter_cpt()
    {
        $labels = array(
            'name'                  => _x( 'E-letters', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'E-letters', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'E-letters', 'text_domain' ),
            'name_admin_bar'        => __( 'E-letter', 'text_domain' ),
            'archives'              => __( 'Item Archives', 'text_domain' ),
            'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
            'all_items'             => __( 'All Items', 'text_domain' ),
            'add_new_item'          => __( 'Add New Item', 'text_domain' ),
            'add_new'               => __( 'Add New', 'text_domain' ),
            'new_item'              => __( 'New Item', 'text_domain' ),
            'edit_item'             => __( 'Edit Item', 'text_domain' ),
            'update_item'           => __( 'Update Item', 'text_domain' ),
            'view_item'             => __( 'View Item', 'text_domain' ),
            'search_items'          => __( 'Search Item', 'text_domain' ),
            'not_found'             => __( 'Not found', 'text_domain' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
            'featured_image'        => __( 'Featured Image', 'text_domain' ),
            'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
            'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
            'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
            'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
            'items_list'            => __( 'Items list', 'text_domain' ),
            'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
            'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
        );

        $args = array(
            'label'                 => __( 'E-letters', 'text_domain' ),
            'description'           => __( 'Post Type Description', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail'),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 80,
            'menu_icon'             => 'dashicons-email',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        );

        register_post_type( 'e-letters', $args );
    }

    /**
     * Add metabox to eletter custom post type
     */
    public function metaboxes_eletter_add()
    {
        add_meta_box( 'tfs_meta_eletter_box', 'E-Letter Unique Identifier', array( $this, 'metaboxes_eletter_display' ), 'e-letters', 'normal', 'high' );
    }

    /**
     *  Add a metabox for eletter page edit screen
     **/
    public function metaboxes_eletter_display()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        wp_nonce_field( 'tfs_css_eletter_nonce', 'tfs_css_eletter_nonce' );

        // Value for Eletter unique indentificator
        $eletter_code = isset( $values['tfs_eletter_code'] ) ? esc_attr( $values['tfs_eletter_code'][0] ) : "";
        $eletter_xcode = isset( $values['tfs_eletter_xcode'] ) ? esc_attr( $values['tfs_eletter_xcode'][0] ) : "";
        ?>

        <p>Please enter the Listcode for this eLetter</p>
        <label for="tfs_eletter_code"><b>Listcode:</b> </label><br />
        <input name="tfs_eletter_code" id="tfs_eletter_code" value="<?php echo $eletter_code; ?>">

        <p>Please enter the Xcode to use with this eLetter</p>
        <label for="tfs_eletter_xcode"><b>Xcode:</b> </label><br />
        <input name="tfs_eletter_xcode" id="tfs_eletter_xcode" value="<?php echo $eletter_xcode; ?>">
        <style>
            #agora-pubcode-picker{ display:none; }
        </style>
        <?php
    }

    /**
     *  Save metabox content for eletter page edit screen
     **/
    public function metaboxes_eletter_save( $post_id )
    {
        // Bail if we're doing an auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // If our current user can't edit this post, bail
        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }

        // Save eletter code
        $eletter_code = sanitize_text_field( $_POST['tfs_eletter_code']);

        if ( isset( $eletter_code ) ) {
            update_post_meta( $post_id, 'tfs_eletter_code', $eletter_code);
        }

        // Save eletter code
        $eletter_xcode = sanitize_text_field( $_POST['tfs_eletter_xcode']);

        if ( isset( $eletter_xcode ) ) {
            update_post_meta( $post_id, 'tfs_eletter_xcode', $eletter_xcode);
        }
    }

    /**
     *  Puts all the local listings data (from the eletters custom posts) into an array
     *
     *  @return array
     */
    public function get_local_listing_array()
    {
        $query = new WP_Query(
            array(
                'post_type' => 'e-letters',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            )
        );

        $all_listings = array();

        while ( $query->have_posts() ) {
            $query->the_post();
            $value = get_post_custom( $query->post->ID );

            array_push( $all_listings, $value['tfs_eletter_code'][0] );
        }

        wp_reset_query();

        return $all_listings;
    }

    /**
     *  Puts all the local listings data and metadata into a dimensional array (from the eletters custom posts)
     *
     *  @return array
     */
    public function get_local_listing_dimensional()
    {
        $query = new WP_Query(
            array(
                'post_type' => 'e-letters',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            )
        );

        $all_listings = array();

        while ( $query->have_posts() ) {
            $query->the_post();
            $value = get_post_custom( $query->POST->ID );

            $val = $value['tfs_eletter_code'][0];
            if( !empty($value['tfs_eletter_xcode']) ) {
                $xcode = $value['tfs_eletter_xcode'][0];
            } else {
                $xcode = 'XMISSING';
            }

            $args = array(
                'content' => get_the_content(),
                'title' => get_the_title(),
                'code' => $value['tfs_eletter_code'][0],
                'postID' => get_the_ID(),
                'postUrl' => get_the_permalink(),
                'featured_image' => Get_the_post_thumbnail(),
                'xcode' => $xcode
            );

            $all_listings[$val] = $args;
        }

        wp_reset_query();

        return $all_listings;
    }
}