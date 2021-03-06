<?php
/**
 * Manage subscriptions in the plugin.
 */
class CSS_Subscriptions
{
    /**
     * @var
     */
    private $core;
    /**
     * @var CSS_Opium
     */
    private $opium;

    /**
     * @var
     */
    private $plugin_admin_page;

    /**
     * @var
     */
    private $config;

    /**
     * CSS_Subscriptions constructor.
     */
    public function __construct( $core, $opium, $plugin_admin_page, $config )
    {
        $this->plugin_admin_page = $plugin_admin_page;
        $this->core = $core;
        $this->config = $config;
        $this->opium = $opium;

        // Register eletter custom post type
        add_action( 'init', array( $this, 'register_subscription_cpt' ), 0 );

        // Add meta boxes for e-letters
        add_action( 'add_meta_boxes', array( $this, 'metaboxes_subscription_add' ) );
        add_action( 'save_post', array( $this, 'metaboxes_subscription_save' ), 11, 3);
    }

    /**
     * Register subscription custom post type
     */
    public function register_subscription_cpt()
    {
        $labels = array(
            'name'                  => _x( 'Subscriptions', 'Post Type General Name', 'text_domain' ),
            'singular_name'         => _x( 'Subscriptions', 'Post Type Singular Name', 'text_domain' ),
            'menu_name'             => __( 'Subscriptions', 'text_domain' ),
            'name_admin_bar'        => __( 'Subscription', 'text_domain' ),
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
            'label'                 => __( 'Subscriptions', 'text_domain' ),
            'description'           => __( 'Post Type Description', 'text_domain' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail'),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 80,
            'menu_icon'             => 'dashicons-tickets',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
        );
        register_post_type( 'subscriptions', $args );
    }

    /**
     * Add metabox to subscription custom post type
     */
    public function metaboxes_subscription_add()
    {
        add_meta_box( 'tfs_meta_subscription_box', 'Subscription Details',
            array( $this, 'metaboxes_subscription_display' ), 'subscriptions', 'normal', 'high' );
        add_meta_box( 'tfs_meta_subscription_renewal_box', 'Subscription Renewals',
            array( $this, 'metaboxes_subscription_renewal_display' ), 'subscriptions', 'normal', 'high' );
        add_meta_box( 'tfs_meta_subscription_hide_unsubscribed', 'Hide Subscription',
            array( $this, 'metaboxes_subscription_hide_unsubscribed' ), 'subscriptions', 'normal', 'high' );
        if( class_exists('\csd_ext\tfs_customer_service_extension') ) {
            add_meta_box( 'tfs_meta_subscription_cancel_video_embed', 'Cancelation Video Embed',
                array( $this, 'metaboxes_subscription_cancel_video_embed' ), 'subscriptions', 'normal', 'high' );
            add_meta_box( 'tfs_meta_subscription_renewal_price', 'Renewal Price',
                array( $this, 'metaboxes_subscription_renewal_price' ), 'subscriptions', 'normal', 'high' );
            add_meta_box( 'tfs_meta_subscription_video_proceed_time', 'Video Proceed Time',
                array( $this, 'metaboxes_subscription_video_proceed_time' ), 'subscriptions', 'normal', 'high' );
            add_meta_box( 'tfs_meta_subscription_refund_policy', 'Subscription Refund Policy',
                array( $this, 'metaboxes_subscription_refund_policy' ), 'subscriptions', 'normal', 'high' );
        }


    }

    public function metaboxes_subscription_display()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        // Value for Eletter unique indentificator
        $tfs_subs_home_link = isset( $values['tfs_subs_home_link'] ) ? esc_attr( $values['tfs_subs_home_link'][0] ) : "";
        
		$tfs_subs_purchase_link = isset( $values['tfs_subs_purchase_link'] ) ? esc_attr( $values['tfs_subs_purchase_link'][0] ) : "";
		
		$tfs_subs_purchase_link_renew = isset( $values['tfs_subs_purchase_link_renew'] ) ? esc_attr( $values['tfs_subs_purchase_link_renew'][0] ) : "";

        $tfs_subs_cc_info_link = isset( $values['tfs_subs_cc_info_link'] ) ? esc_attr(
                                                                            $values['tfs_subs_cc_info_link'][0] ) : "";

        $tfs_subs_promo_code = isset( $values['tfs_subs_promo_code'] ) ? esc_attr(
                                                                            $values['tfs_subs_promo_code'][0] ) : "";
        $tfs_subs_spc_code = isset( $values['tfs_subs_spc_code'] ) ? esc_attr(
                                                                            $values['tfs_subs_spc_code'][0] ) : "";

        $tfs_subs_pubcode = isset( $values['tfs_subs_pubcode'] ) ? esc_attr( $values['tfs_subs_pubcode'][0] ) : "";

        echo $this->get_all_authcodes_select( 'tfs_subs_pubcode', $tfs_subs_pubcode );
        ?>
        <style>
            #agora-pubcode-picker{display:none;}
        </style>
        <p>
            Link this Subscription to a valid Authentication Code set up under Middleware 2 &#10141; Authentication Codes
        </p>

        <label for="tfs_subs_home_link"><b>Subscription info URL:</b> </label><br>
        <input name="tfs_subs_home_link" id="tfs_subs_home_link" placeholder="http://" type="url" size="75"
               value="<?php echo $tfs_subs_home_link; ?>">
        <p>
            This link will be used on the "My Paid Subscription" page in the Dashboard so you can direct users to a
            post, promo, microsite, etc. where they can learn more about the subscription
        </p>
        <br>

        <label for="tfs_subs_purchase_link"><b>Subscription buy URL:</b> </label><br>
        <input name="tfs_subs_purchase_link" id="tfs_subs_purchase_link" placeholder="http://" type="url" size="75"
               value="<?php echo $tfs_subs_purchase_link; ?>">
        <p>
            This link will be used on the "My Paid Subscription" page in the Dashboard and the renewal pop ups,
            to direct users to an order form
        </p>
        <br>
		
        <label for="tfs_subs_purchase_link_renew"><b>Subscription renew URL:</b> </label><br>
        <input name="tfs_subs_purchase_link_renew" id="tfs_subs_purchase_link_renew" placeholder="http://" type="url" size="75"
               value="<?php echo $tfs_subs_purchase_link_renew; ?>">
        <p>
            This link will be used on the "My Paid Subscription" page in the Dashboard so you can direct users to a renewal link where they can renew the subscription early
        </p>
		<?php

	    if( defined('CSD_CC_INFO')) {
		    ?>
			<label for="tfs_subs_cc_info_link">
				<b>OPIUM NICK:</b>
			</label>
			<br>
			<input name="tfs_subs_cc_info_link" id="tfs_subs_cc_info_link" placeholder="http://" type="text" size="75"
				   value="<?php echo $tfs_subs_cc_info_link; ?>">
			<p>
				This will be used for a prepop of OPIUM on the credit card update function
			</p>


            <label for="tfs_subs_cc_info_link">
                <b>OPIUM Specified Pub Code (SPC):</b>
            </label>
            <br>
            <input name="tfs_subs_spc_code" id="tfs_subs_spc_code" placeholder="SPC (Specified Pub Code)" type="text" size="75"
                   value="<?php echo $tfs_subs_spc_code; ?>">
            <p>
                This will be used for a prepop of OPIUM on the credit card update function
            </p>

            <input name="tfs_subs_promo_code" id="tfs_subs_promo_code" placeholder="XMISSING" type="text" size="75"
                   value="<?php echo $tfs_subs_promo_code; ?>">
            <p>
                OPIUM Promo Code
            </p>


		    <?php
	    }
    }

    /**
     *  Adds a metabox with renewal settings in 'subscriptions' custom posts backend page
     */
    public function metaboxes_subscription_renewal_display()
    {
        global $post;

        // If subscription renewal is enabled
        if ( $this->config['subscription_renewals'] == 1 ) {
            // Post meta values
            $subscription_renewals_enable = get_post_meta( $post->ID, 'tfs_subscription_renewals_enable', true );
            $subscription_renewals_remaining = get_post_meta( $post->ID, 'tfs_subscription_remaining', true );
            $subscription_renewals_notice = get_post_meta( $post->ID, 'tfs_subscription_renewals_notice', true );

            $wp_editor_settings = array(
                'textarea_rows' => 10
            );
            ?>

            <label for="tfs_subscription_renewals_enable"><b>Subscription Renewals:</b> </label><br>
            <input type="radio" name="tfs_subscription_renewals_enable" value="0" <?php if ( $subscription_renewals_enable == 0 ) { echo 'checked'; } ?>>
            <?php _e('Disabled'); ?>

            <input type="radio" name="tfs_subscription_renewals_enable" value="1" <?php if ( $subscription_renewals_enable == 1 ) { echo 'checked'; } ?>>
            <?php _e('Enabled'); ?>

            <div class="tfs_subscription_renewals" style="display: none;">
                <hr />

                <label for="tfs_subscription_remaining"><b>Show renewal message when issues to go is less than or equal to:</b> </label>
                <input type="number" min="0" max="5000" name="tfs_subscription_remaining" id="tfs_subscription_remaining" value="<?php echo $subscription_renewals_remaining; ?>" width="20">
                <hr />

                <label for="tfs_subscription_renewals_notice"><b>Subscription Renewal Notice:</b> </label><br>

                <?php
                // Display wysiwyg editor for popup
                wp_editor( $subscription_renewals_notice, 'tfs_subscription_renewals_notice', $wp_editor_settings );
                ?>
            </div>
            <?php
        } else {
            $page           = $this->plugin_admin_page;
            $css_admin_url  = add_query_arg( compact( 'page' ), admin_url( 'admin.php' ) );

            echo 'Subscription renewals are disabled. Please enable them in <a href="' . $css_admin_url .'">Customer Self Service Settings</a>.';
        }
    }

    /**
     *  Adds a metabox with a checkbox to hide subscriptions from non-subscribed users
     */
    public function metaboxes_subscription_hide_unsubscribed()
    {
        global $post;

        // Post meta values
        $subscription_hide_unsubscribed = get_post_meta( $post->ID, 'tfs_subscription_hide', true );
        ?>

        <label for="tfs_subscription_hide_unsubscribed"><b>Hide this subscription if the user is not subscribed:</b> </label><br>
        <input type="radio" name="tfs_subscription_hide_unsubscribed" value="0"
            <?php if ( !isset($subscription_hide_unsubscribed) || $subscription_hide_unsubscribed == 0 ) { echo 'checked'; } ?>>
        <?php _e('Show'); ?>
        <input type="radio" name="tfs_subscription_hide_unsubscribed" value="1"
            <?php if ( isset($subscription_hide_unsubscribed) && $subscription_hide_unsubscribed == 1 ) { echo 'checked'; } ?>>
        <?php _e('Hide');
    }

    /**
     *  Adds a metabox to take in embed video code for the csd extension plugin
     */
    public function metaboxes_subscription_cancel_video_embed()
    {
        global $post;

        // Post meta values
        $subscription_cancel_video_embed = get_post_meta( $post->ID, 'tfs_subscription_cancel_video_embed', true );
        ?>

        <label for="tfs_subscription_cancel_video_embed"><b>Enter the embed code for the cancel process video here:</b> </label><br>
        <textarea name="tfs_subscription_cancel_video_embed" cols="100"><?php echo $subscription_cancel_video_embed; ?></textarea>
      <?php

    }

    /**
     *  Adds a metabox to take the length of time before a video proceed button appears
     */
    public function metaboxes_subscription_video_proceed_time()
    {
        global $post;

        // Post meta values
        $subscription_video_proceed_time = get_post_meta( $post->ID, 'tfs_subscription_video_proceed_time', true );
        ?>

        <label for="tfs_subscription_video_proceed_time">
            <b>Set the length of time in seconds you want to elapse before the video 'Proceed' button appears:</b>
        </label><br>
        <input type="number" name="tfs_subscription_video_proceed_time" value="<?php echo $subscription_video_proceed_time; ?>"
               width="20">
        <?php

    }


    /**
     *  Adds a metabox to take renewal price for use in csd extension plugin
     */
    public function metaboxes_subscription_renewal_price()
    {
        global $post;

        // Post meta values
        $subscription_renewal_price = get_post_meta( $post->ID, 'tfs_subscription_renewal_price', true );
		
		$subscription_renewal_savings = get_post_meta( $post->ID, 'tfs_subscription_renewal_savings', true );
        ?>

        <label for="tfs_subscription_renewal_price"><b>Enter the renewal price for the subscription here:</b> </label><br>
        <span class="tfs_subscription_renewal_price_span">$</span>
        <input type="number" name="tfs_subscription_renewal_price" id="tfs_subscription_renewal_price"
                             value="<?php echo $subscription_renewal_price; ?>" width="20">
		<br>
		<br>
        <label for="tfs_subscription_renewal_savings"><b>Enter the amount saved for renewing the subscription early here:</b> </label><br>
        <span class="tfs_subscription_renewal_price_span">$</span>
        <input type="number" name="tfs_subscription_renewal_savings" id="tfs_subscription_renewal_savings"
                             value="<?php echo $subscription_renewal_savings; ?>" width="20">
        <?php

    }

    /**
     *  Adds a metabox to set data relevant to refund policy
     */
    public function metaboxes_subscription_refund_policy()
    {
        global $post;
        // Post meta values
        $tfs_subscription_backend = get_post_meta( $post->ID, 'tfs_subscription_backend', true );
        ?>
        <label for="tfs_subscription_backend"><b>Is this a <strong>backend</strong> publication?</b> </label><br>
        <input type="radio" name="tfs_subscription_backend" value="0" <?php if ( empty($tfs_subscription_backend) ) { echo 'checked'; } ?>>
        <?php _e('No'); ?>

        <input type="radio" name="tfs_subscription_backend" value="1" <?php if ( !empty($tfs_subscription_backend) ) { echo 'checked'; } ?>>
        <?php _e('Yes');
    }


    /**
     *  Saves the data sent as associated meta_data to the post
     *
     *  @param $post_id
     */
    public function metaboxes_subscription_save( $post_id )
    {


        // Bail if we're doing an auto save
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // If our current user can't edit this post, bail
        if ( ! current_user_can( 'edit_post' ) ) {
            return;
        }

        // Save input content
        $tfs_subs_pubcode = '';
        $tfs_subs_purchase_link = '';
		$tfs_subs_purchase_link_renew = '';
	    $tfs_subs_cc_info_link = '';
        $tfs_subs_promo_code = '';
        $tfs_subs_spc_code = '';
        $tfs_subs_pubcode = '';
        $tfs_subs_home_link = '';
        $tfs_subscription_renewals_enable = '';
        $tfs_subscription_remaining = '';
        $tfs_subscription_renewals_notice = '';
        $tfs_subscription_hide_unsubscribed = '';
        $tfs_subscription_cancel_video_embed = '';
        $tfs_subscription_video_proceed_time = '';
        $tfs_subscription_renewal_price = '';
        $tfs_subscription_renewal_savings = '';
        $tfs_subscription_backend = '';

        extract($_POST, EXTR_IF_EXISTS);

        if (!empty($tfs_subs_pubcode)) {
            $tfs_subs_pubcode = sanitize_text_field( $tfs_subs_pubcode );
            update_post_meta( $post_id, 'tfs_subs_pubcode', $tfs_subs_pubcode );
        }
        if (!empty($tfs_subs_promo_code)) {
            $tfs_subs_promo_code = sanitize_text_field( $tfs_subs_promo_code );
            update_post_meta( $post_id, 'tfs_subs_promo_code', $tfs_subs_promo_code );
        }
        if (!empty($tfs_subs_spc_code)) {
            $tfs_subs_spc_code = sanitize_text_field( $tfs_subs_spc_code );
            update_post_meta( $post_id, 'tfs_subs_spc_code', $tfs_subs_spc_code );
        }

        if (!empty($tfs_subs_purchase_link)) {
            $tfs_subs_purchase_link = sanitize_text_field( $tfs_subs_purchase_link);
            update_post_meta($post_id, 'tfs_subs_purchase_link', $tfs_subs_purchase_link );
        }
		
        if (!empty($tfs_subs_purchase_link_renew)) {
            $tfs_subs_purchase_link_renew = sanitize_text_field( $tfs_subs_purchase_link_renew);
            update_post_meta($post_id, 'tfs_subs_purchase_link_renew', $tfs_subs_purchase_link_renew );
        }		
		
        if (!empty($tfs_subs_cc_info_link)) {
	        $tfs_subs_cc_info_link = sanitize_text_field( $tfs_subs_cc_info_link);
            update_post_meta($post_id, 'tfs_subs_cc_info_link', $tfs_subs_cc_info_link );
        }

        if (!empty($tfs_subs_home_link)) {
            $tfs_subs_home_link = sanitize_text_field( $tfs_subs_home_link );
            update_post_meta( $post_id, 'tfs_subs_home_link', $tfs_subs_home_link );
        }

        if (!empty($tfs_subscription_renewals_enable)) {
            $tfs_subscription_renewals_enable = sanitize_text_field( $tfs_subscription_renewals_enable );
            update_post_meta( $post_id, 'tfs_subscription_renewals_enable', $tfs_subscription_renewals_enable );
        }

        if (!empty($tfs_subscription_remaining)) {
            $tfs_subscription_remaining = sanitize_text_field( $tfs_subscription_remaining );
            update_post_meta( $post_id, 'tfs_subscription_remaining', $tfs_subscription_remaining );
        }

        if (!empty($tfs_subscription_renewals_notice)) {
            $tfs_subscription_renewals_notice = stripslashes( $tfs_subscription_renewals_notice );
            update_post_meta( $post_id, 'tfs_subscription_renewals_notice', $tfs_subscription_renewals_notice );
        }

        if (isset($tfs_subscription_hide_unsubscribed)) {
            $tfs_subscription_hide_unsubscribed = sanitize_text_field( $tfs_subscription_hide_unsubscribed );
            update_post_meta( $post_id, 'tfs_subscription_hide', $tfs_subscription_hide_unsubscribed );
        }

        if (!empty($tfs_subscription_cancel_video_embed)) {
            $tfs_subscription_cancel_video_embed = wp_kses( $tfs_subscription_cancel_video_embed, $allowedtags );
            update_post_meta( $post_id, 'tfs_subscription_cancel_video_embed', $tfs_subscription_cancel_video_embed );
        }

        if (!empty($tfs_subscription_video_proceed_time)) {
            $tfs_subscription_video_proceed_time = sanitize_text_field( $tfs_subscription_video_proceed_time );
            update_post_meta( $post_id, 'tfs_subscription_video_proceed_time', $tfs_subscription_video_proceed_time );
        }

        if (!empty($tfs_subscription_renewal_price)) {
            $tfs_subscription_renewal_price = sanitize_text_field( $tfs_subscription_renewal_price );
            update_post_meta( $post_id, 'tfs_subscription_renewal_price', $tfs_subscription_renewal_price );
        }
		
        if (!empty($tfs_subscription_renewal_savings)) {
            $tfs_subscription_renewal_savings = sanitize_text_field( $tfs_subscription_renewal_savings );
            update_post_meta( $post_id, 'tfs_subscription_renewal_savings', $tfs_subscription_renewal_savings );
        }		

        if (isset($tfs_subscription_backend)) {
            $tfs_subscription_backend = sanitize_text_field( $tfs_subscription_backend );
            update_post_meta( $post_id, 'tfs_subscription_backend', $tfs_subscription_backend);
        }
    }

    /**
     *  Process subscription renewals notice
     *
     *  @return string
     */
    public function process_subscription_renewals_notices()
    {
        // Check if subscription renewals is enabled
        if ( $this->config['subscription_renewals'] == 1 ) {
            // Check if user is logged in
            if ( is_user_logged_in() ) {
                // Get customer information
                $current_user = wp_get_current_user();
                $current_user_mw = $current_user->middleware_data;

                // Check if user has middleware data
                if ( $current_user_mw ) {
                    // Get user's subscriptions
                    $current_user_mw_subscriptions = $current_user->middleware_data->subscriptionsAndOrders->subscriptions;

                    // Check if user has subscriptions
                    if ( is_array( $current_user_mw_subscriptions ) ) {
                        $subscriptions_renewal_notices = array();

                        foreach ( $current_user_mw_subscriptions as $user_subscription ) {
                            // Check if subscription is active
                            $active_circ_status = array('P', 'Q', 'R', 'W');

                            if (in_array($user_subscription->circStatus, $active_circ_status)) {
                                // Get number of issues remaining
                                $issues_remaining = $user_subscription->issuesRemaining;

                                // Get item number
                                $item_number = $user_subscription->id->item->itemNumber;

                                $query_args_subscriptions = array(
                                    'post_type' => 'subscriptions',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                    'meta_query' => array(
                                        array(
                                            'key' => 'tfs_subs_pubcode',
                                            'value' => $item_number
                                        )
                                    )
                                );

                                $query_subscriptions = new WP_Query( $query_args_subscriptions );

                                if ( $query_subscriptions->have_posts() ) {
                                    // For each subscription
                                    while ($query_subscriptions->have_posts()) {
                                        $query_subscriptions->the_post();

                                        $subscription_renewals_enable = get_post_meta($query_subscriptions->post->ID, 'tfs_subscription_renewals_enable', true);
                                        $subscription_renewals_remaining = get_post_meta($query_subscriptions->post->ID, 'tfs_subscription_remaining', true);
                                        $subscription_renewals_notice = get_post_meta($query_subscriptions->post->ID, 'tfs_subscription_renewals_notice', true);

                                        // Check if renewal is enabled for this subscription
                                        if ( $subscription_renewals_enable == '1' ) {
                                            // Check amount of issues
                                            if ( $issues_remaining <= $subscription_renewals_remaining) {
                                                // Check if there is content for subscription's renewal notice
                                                if ( ! empty( $subscription_renewals_notice ) ) {
                                                    array_push( $subscriptions_renewal_notices, $subscription_renewals_notice );
                                                }
                                            }
                                        }
                                    }

                                    wp_reset_postdata();
                                }
                            }
                        }

                        // Return subscriptions renewal notices if any exists
                        if ( ! empty( $subscriptions_renewal_notices ) ) {
                            return $subscriptions_renewal_notices;
                        }
                    }
                }
            }
        }

        // Return false if no subscriptions/notices etc.
        return false;
    }

    /**
     *  Returns a select dropdown with all the registered subscriptions authcodes
     *  it has optional arguments, the html select name and the default selected value
     *
     *  @param string $select_name (optional)
     *  @param string $default_value (optional)
     *  @return string
     */
    public function get_all_authcodes_select( $select_name = "authcodes_list", $default_value = "" )
    {
        $all_authcodes = $this->core->authentication->get_all_authcodes();

        $html = '<label><b>Please Select a valid subscription:</b></label><br>
                <select name="'.$select_name.'" >
				<option selected disabled>Select Subscription</option>';

        foreach( $all_authcodes as $code ) {
            if ( $code->type == 'subscriptions' ) {
                $html .= '<option value="'.$code->advantage_code.'"';

                if ( $default_value == $code->advantage_code ) {
                    $html .= "selected";
                }

                $html .= '>'. $code->description.'</option>';
            }
        }

        $html .= "</select>";

        return $html;
    }

    /**
     *  Puts all the local subscription data (from the subscriptions custom posts) into an dimensional array
     *
     *  @return array
     */
    public function get_local_subscriptions_dimensional()
    {
        $query = new WP_Query(
            array(
                'post_type' => 'subscriptions',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            )
        );

        $all_listings = array();

        while ( $query->have_posts() ) {
            $query->the_post();
            $value = get_post_custom( $query->post->ID );

            $val = $value['tfs_subs_pubcode'][0];
            $tfs_subs_promo_code = $value['tfs_subs_promo_code'][0];
            $tfs_subs_spc_code = $value['tfs_subs_spc_code'][0];



            $tfs_subs_cc_info_link =
                $this->opium->create_prepop_link($value['tfs_subs_cc_info_link'][0],
                    $tfs_subs_promo_code, $this->opium->config['opium_url'],array('SPC' => $tfs_subs_spc_code));





            $cc_number =
                            $this->core->mw->find_credit_cards_by_customer_number_affiliate_code(
                                    $this->core->user->get_customer_number()
                              );

            if ( is_wp_error($cc_number ) ){
                $cc_number = "xxx xxx xxx xxx";
            } else {
                //get first credit card in array
				if ( is_array( $cc_number ) ) {
                	$cc_number = end($cc_number);
				}
                $cc_number =  $cc_number->cardNumber;

            }


            $args = array(
                'content' => get_the_content(),
                'title' => get_the_title(),
                'code' => $value['tfs_subs_pubcode'][0],
                'buy_url' => $value['tfs_subs_purchase_link'][0],
                'renew_url' => $value['tfs_subs_purchase_link_renew'][0],
				'renew_savings' => $value['tfs_subscription_renewal_savings'][0],
                'info_url' => $value['tfs_subs_home_link'][0],
                'renewal_link' => $tfs_subs_cc_info_link,
                'cc_info' => $cc_number,
                'post_id' => get_the_ID(),
                'post_url' => get_the_permalink(),
                'featured_image' => Get_the_post_thumbnail(),
				'is_backend' => $value['tfs_subscription_backend'][0]
            );

            $all_listings[$val] = $args;
        }

        wp_reset_query();

        return $all_listings;
    }

    /**
     * Return an filtered array with the user subscription plus at the end of the array
     * using the key [displayed_pubcodes], it has an array with the displayed pubcodes
     *
     * @return array
     */
    public function get_filtered_active_user_subscription()
    {
        $tfs_subscriptions = $this->core->authentication->get_user_subscriptions();

        // Middleware user subscriptions
        $current_user = wp_get_current_user();
        $user_subscription_meta = $current_user->middleware_data->subscriptionsAndOrders->subscriptions;

        // Allowed subscriptions backend options
        $allowed_subscriptions = false;
        if( !empty($this->config['allowed_subscriptions_checkbox']) && !empty($this->config['allowed_subscriptions'])){
            $allowed_subscriptions = explode( ',', str_replace( ' ', '', $this->config['allowed_subscriptions'] ) );
            $tfs_subscriptions['allowed_subscriptions'] = $allowed_subscriptions;
        }

        // Empty array for the displayed results;
        $displayed_results = array();

        if ( ! is_wp_error( $tfs_subscriptions ) ) {
            $results = 0;

            foreach ( $tfs_subscriptions as $itemKey => $item ) {
                if( $item->temp == true ) {
                    unset( $tfs_subscriptions[$itemKey] );
                } else {
                    foreach ( $user_subscription_meta as $sub ) {
                        if ( $sub->id->item->itemNumber == $item->pubcode ) {
                            $item->issuesRemaining = $sub->issuesRemaining;
                            $item->renewMethod = $sub->renewMethod;
                            $item->is_lifetime = false;
                            $item->rate = $sub->rate;
                            if( $sub->circStatus === "P" || strtoupper( $sub->subType ) === 'LIFE' ) {
                                $item->is_lifetime = true;
                            }
                        }
                    }
                    if ( !empty($this->config['allowed_subscriptions_checkbox']) && !empty( $allowed_subscriptions ) ) {

                        if ( !in_array( $item->pubcode, $allowed_subscriptions ) ) {
                            unset( $tfs_subscriptions[$results] );
                            $results++;
                            continue;
                        }
                    }

                    $ar_trans = get_transient($current_user->ID . 'ar' . $item->subref);
                    if( !empty($ar_trans) ) {
                        $item->no_auto = true;
                    }

                    $results++;
                    // Add in an array the displayed results
                    array_push( $displayed_results, $item->pubcode );
                }
            }
        }

        // Save the displayed pubcodes in an array
        $tfs_subscriptions['displayed_pubcodes'] = $displayed_results;

        return $tfs_subscriptions;
    }
}