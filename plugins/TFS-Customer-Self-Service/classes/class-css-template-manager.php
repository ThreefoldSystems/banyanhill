<?php
/**
 * Template manager.
 */
class CSS_Template_Manager
{
    /**
     * @var agora_core_framework
     */
    public $core;

    /**
     * @var CSS_Subscriptions
     */
    private $subscriptions;

    /**
     * @var CSS_Eletters
     */
    private $eletters;

    /**
     * @var CSS_Helpers
     */
    private $helpers;

    /**
     * @var
     */
    private $config;

    /**
     * CSS_Template_Manager constructor.
     *
     * @param agora_core_framework $core
     * @param CSS_Subscriptions $subscriptions
     * @param CSS_Eletters $eletters
     * @param CSS_Helpers $helpers
     * @param $config
     */
    public function __construct( agora_core_framework $core, CSS_Subscriptions $subscriptions, CSS_Eletters $eletters, CSS_Helpers $helpers, $config )
    {
        $this->core = $core;
        $this->config = $config;
        $this->subscriptions = $subscriptions;
        $this->eletters = $eletters;
        $this->helpers = $helpers;
    }

    /**
     *  Process template
     *
     * Processes each template based on name, specifies what data to pass to each view/template.
     *
     * @param $template_name string Name of template file
     *
     *  @return array/bool
     */
    public function process_template( $template_name, $content = null )
    {
        switch ( $template_name ) {
            case 'css-change-address':
                $pass_content = $this->process_change_address();
                break;

            case 'css-change-bulk-email':
                $pass_content = $this->process_change_bulk_email( $content );
                break;

            case 'css-change-email':
                $pass_content = $this->process_change_email();
                break;

            case 'css-change-password':
                $pass_content = $this->process_change_password();
                break;

            case 'css-change-username':
                $pass_content = $this->process_change_username();
                break;

            case 'css-contact-support':
                $pass_content = $this->process_contact_support();
                break;

            case 'css-customer-self-service':
                $pass_content = $this->process_customer_self_service( $content );
                break;

            case 'css-email-change-other-updates':
                $pass_content = $this->process_email_change_other_updates( $content );
                break;

            case 'css-listings':
                $pass_content = $this->process_listings();
                break;

            case 'css-menu':
                $pass_content = $this->process_menu();
                break;

            case 'css-subscription-renewals':
                $pass_content = $this->process_subscriptions_renewals( $content );
                break;

            case 'css-account-landing':
                $pass_content = $this->process_account_landing();
                break;

            case 'css-subscriptions':
            case 'css-payment':
                $pass_content = $this->process_subscriptions();
                break;

            case 'css-username-as-email':
                $pass_content = $this->process_username_as_email();
                break;

            default:
                $pass_content = $content;
        }

        $this->load_template( $template_name, $pass_content );
    }

    /**
     *  Load template
     *
     * Loads template/view with data provided.
     *
     * @param $template_name string Name of template file
     * @param $content array Content to pass to the view
     *
     * @return void
     */
    public function load_template( $template_name, $content )
    {
        $load_template_file = $this->core->view->load( $template_name, $content, true );

        if ( !empty($load_template_file) ) {
            echo $load_template_file;
        } else {
            echo 'Error, ' . $template_name . ' view file does not exist.';
        }
    }

    /**
     * Process css-change-email template
     *
     * @return array/bool
     */
    private function process_change_address()
    {
        $return_content = array();

        // Get customer address
        $customer_address = $this->core->user->get_address();
        if ( !empty($customer_address) ) {
            $return_content['customer_address'] = $customer_address;
        }

        // Check if it's allowed to change country
        if ( !empty($this->config['allow_change_country']) ) {
            $return_content['allow_change_country'] = $this->config['allow_change_country'];
        }

        // Check if request password on address change
        if ( !empty($this->config['request_pwd_on_addr_update']) ) {
            $return_content['request_pwd_on_addr_update'] = !empty($this->config['request_pwd_on_addr_update']);
        }

        return $return_content;
    }

    /**
     * Process css-change-bulk-email template
     *
     * @return array/bool
     */
    private function process_change_bulk_email( $content )
    {
        return $content;
    }

    /**
     * Process css-change-email template
     *
     * @return array/bool
     */
    private function process_change_email()
    {
        $return_content = array();

        // Get customer email
        $customer_email = $this->core->user->_get_email();
        if ( !empty($customer_email) ) {
            $return_content['customer_email'] = $customer_email;
        }

        // Check if request password on email change
        if ( !empty($this->config['request_pwd_on_email_update']) ) {
            $return_content['request_pwd_on_email_update'] = $this->config['request_pwd_on_email_update'];
        }

        // Check transient for email change
        $customer_number = $this->core->user->get_customer_number();
        $time_remaining = $this->helpers->transient_time_remaining( 'email_changed_' . $customer_number );

        if ( !empty($time_remaining) ) {
            $return_content['email_change_time_remaining'] = $time_remaining;
        }

        return $return_content;
    }

    /**
     * Process css-change-password template
     *
     * @return array/bool
     */
    private function process_change_password()
    {
        $return_content = array();

        // Check transient for password change
        $customer_number = $this->core->user->get_customer_number();
        $time_remaining = $this->helpers->transient_time_remaining( 'pwd_changed_' . $customer_number );

        if ( !empty($time_remaining) ) {
            $return_content['password_change_time_remaining'] = $time_remaining;
        }

        return $return_content;
    }

    /**
     * Process css-change-username template
     *
     * @return array/bool
     */
    private function process_change_username()
    {
        $return_content = array();

        // Get customer username
        $username = $this->core->user->get_username();
        if ( !empty($username) ) {
            $return_content['customer_username'] = $username;
        }

        // Check if request password on username change
        if ( !empty($this->config['request_pwd_on_username_update']) ) {
            $return_content['request_pwd_on_username_update'] = $this->config['request_pwd_on_username_update'];
        }

        // Check transient for username change
        $customer_number = $this->core->user->get_customer_number();
        $time_remaining = $this->helpers->transient_time_remaining( 'username_changed_' . $customer_number );

        if ( !empty($time_remaining) ) {
            $return_content['username_change_time_remaining'] = $time_remaining;
        }

        return $return_content;
    }

    /**
     * Process css-account-landing template
     *
     * Return the account landing config value in array
     *
     * @return array
     */
    private function process_account_landing()
    {
        $return_content = array();

        // Check if landing content is set in the config
        if ( !empty($this->config['css_account_landing']) ) {
            $return_content['account-landing'] = $this->config['css_account_landing'];
        }

        return $return_content;
    }

    /**
     * Process css-contact-support template
     *
     * @return array/bool
     */
    private function process_contact_support()
    {
        $return_content = array();

        // Get value of contact mode
        $css_contact_mode = "";
        if ( !empty($this->config['css_contact_mode']) ) {
            $css_contact_mode = strtolower( str_replace( ' ', '', $this->config['css_contact_mode'] ) );
        }
        $return_content['css_contact_mode'] = $css_contact_mode;

        // Get value of phone title
        if ( !empty($this->config['css_phone_data']) ) {
            $return_content['css_phone_data'] = $this->config['css_phone_data'];
        }

        // Get value of contact shortcode
        if ( !empty($this->config['css_contact_shortcode']) ) {
            $return_content['css_contact_shortcode'] = $this->config['css_contact_shortcode'];
        }

        return $return_content;
    }

    /**
     * Process css-customer-self-service template
     *
     * @return array/bool
     */
    private function process_customer_self_service( $content )
    {
        return $content;
    }

    /**
     * Process css-email-change-other-updates template
     *
     * @return array/bool
     */
    private function process_email_change_other_updates( $content )
    {
        $return_content = array();

        // Add data passed to the template processor
        if ( isset($content['eletter']) ) {
            $return_content['eletter'] = $content['eletter'];
        }

        if ( isset($content['subs']) ) {
            $return_content['subs'] = $content['subs'];
        }

        if ( isset($content['old_email']) ) {
            $return_content['old_email'] = $content['old_email'];
        }

        if ( isset($content['username']) ) {
            $return_content['username'] = $content['username'];
        }

        return $return_content;
    }

    /**
     * Process css-process-listings template
     *
     * @return array
     */
    private function process_listings()
    {
        $return_content = array();

        // Get customer lists
        $mw_lists = $this->core->mw->get_customer_list_signups_by_id( $this->core->user->get_customer_number());
        if ( !empty($mw_lists) ) {
            $return_content['mw_lists'] = $mw_lists;
        } else {
            // repeat call if the first one fails to reduce occurrences of front end errors
            $mw_lists = $this->core->mw->get_customer_list_signups_by_id( $this->core->user->get_customer_number() );
            if( !empty($mw_lists) ) {
                $return_content['mw_lists'] = $mw_lists;
            }
        }

        // Get the placeholder image URL
        if ( !empty($this->config['placeholder_img']) ) {
            $return_content['placeholder_img'] = $this->config['placeholder_img'];
        }

        // Get eletters
        $local_eletters = $this->eletters->get_local_listing_array();
        $return_content['all_lists_codes'] = $local_eletters;

        // Check time remaining for each listing email change
        if ( !empty($mw_lists) && !empty($local_eletters) ) {
            // For each middleware list
            foreach ( $mw_lists as $item ) {
                // Check if this middleware list has been added in eletters cpt
                if ( in_array( $item->listCode, $local_eletters ) ) {
                    // Check transient for email change
                    $customer_number = $this->core->user->get_customer_number();
                    $time_remaining = $this->helpers->transient_time_remaining( $item->listCode . '_changed_' . $customer_number );

                    if ( $time_remaining ) {
                        $item->email_changed = $time_remaining;
                    }
                }
            }
        }

        // Get customer email
        $customer_email = $this->core->user->_get_email();
        if ( !empty($customer_email) ) {
            $return_content['customer_email'] = $customer_email;
        }

        $return_content['eletter_multidimensional'] = $this->eletters->get_local_listing_dimensional();

        // Get allowed listings
        $allowed_listings = $this->config['allowed_listings'];
        if ( !empty($allowed_listings) ) {
            $return_content['allowed_list'] = explode( ',', str_replace( ' ', '', $allowed_listings ) );
        }

        // Check allowed listings checkbox
        $allowed_listings_checkbox = $this->config['allowed_listings_checkbox'];
        if ( !empty($allowed_listings_checkbox) ) {
            $return_content['allowed_listings_checkbox'] = $allowed_listings_checkbox;
        }

        // Get display listings recomendations
        $display_listings_recomendations = $this->config['display_listings_recomendations'];
        if ( !empty($display_listings_recomendations) ) {
            $return_content['display_listings_recomendations'] = $display_listings_recomendations;
        }

        return $return_content;
    }

    /**
     * Process css-menu template
     *
     * @return array/bool
     */
    private function process_menu()
    {
        $return_content = array();

        $return_content['css_user'] = $this->core->user;

        if( !empty($this->config['alt_img']) ) {
            $return_content['alt_icon'] = $this->config['alt_img'];
        }

        if( !empty($this->config['alt_template_profile']) ) {
            $return_content['alt_template_profile'] = $this->config['alt_template_profile'];
        }

        // Get display account
        if ( !empty($this->config['display_account']) ) {
            $return_content['display_account'] = $this->config['display_account'];
        }

        // Get display subscriptions
        if ( !empty($this->config['display_subscriptions']) ) {
            $return_content['display_subscriptions'] = $this->config['display_subscriptions'];
        }

        // Get display listings
        if ( !empty($this->config['display_listings']) ) {
            $return_content['display_listings'] = $this->config['display_listings'];
        }

        // Get display contact
        if ( !empty($this->config['display_contact']) ) {
            $return_content['display_contact'] = $this->config['display_contact'];

            $css_contact_mode = "";

            if(!empty($this->config['css_contact_mode'])){
                // Get value of contact mode
                $css_contact_mode = strtolower( str_replace( ' ', '',  $this->config['css_contact_mode'] ) );
            }

            if ( !empty($css_contact_mode) && $css_contact_mode == "Display text" && empty ( $this->config['css_phone_data'] ) ) {
                $return_content['display_contact'] = false;
            } elseif ( empty( $this->config['css_contact_shortcode'] ) ) {
                $return_content['display_contact'] = false;
            }
        }

        return $return_content;
    }

    /**
     * Process css-subscriptions-renewals template
     *
     * @return array/bool
     */
    private function process_subscriptions_renewals( $content )
    {
        $return_content = array();

        // Get subscription renewal notices
        if ( isset($content['subscription_renewals']) ) {
            $return_content['subscription_renewals'] = $content['subscription_renewals'];
        }

        return $return_content;
    }

    /**
     * Process css-subscriptions template
     *
     * @return array/bool
     */
    private function process_subscriptions()
    {
        $return_content = array();
        // Get the filtered user subscriptions
        $tfs_subscriptions = $this->subscriptions->get_filtered_active_user_subscription();
        if ( !empty($tfs_subscriptions) ) {
            $return_content['tfs_subscriptions'] = $tfs_subscriptions;
        }

        // Get the local subscriptions info
        $subscription_info = $this->subscriptions->get_local_subscriptions_dimensional();
        if ( !empty($subscription_info) ) {
            $return_content['subscriptions_info'] = $subscription_info;
        }

        // Get the placeholder image URL
        if (  !empty($this->config['placeholder_img']) ) {
            $return_content['placeholder_img'] = $this->config['placeholder_img'];
        }

        if( !empty($this->config['hide_nonsubscribed']) ) {
            $return_content['hide_nonsubscribed'] = $this->config['hide_nonsubscribed'];
        }

        if (  !empty($this->config['allow_toggle_auto_renew']) ) {
            $return_content['allow_toggle_auto_renew'] = $this->config['allow_toggle_auto_renew'];
        }

        if( $hide_nonsubscribed = $this->config['hide_nonsubscribed'] ) {
            $return_content['hide_nonsubscribed'] = $hide_nonsubscribed;
        }

        // Check time remaining for each subscription email change
        if ( isset($tfs_subscriptions) && isset($subscription_info) ) {
            // For each user's subscription
            foreach ( $tfs_subscriptions as $key => $item ) {
                // Check if subscription exists in subscription returned from the get_local_subscriptions_dimensional
                if (  $subscription_info[$item->pubcode] ) {
                    // Check transient for email change
                    $customer_number = $this->core->user->get_customer_number();
                    $time_remaining = $this->helpers->transient_time_remaining( $item->subref . '_changed_' . $customer_number );

                    if ( $time_remaining ) {
                        $item->email_changed = $time_remaining;
                    }
                }
            }
        }

        if ( $this->config['show_remaining_issues']) {
            $return_content['remaining_issues'] = true;
        }

        if ( $this->config['issues_to_renew']) {
            $return_content['issues_to_renew'] = $this->config['issues_to_renew'];
        }

        return $return_content;
    }

    /**
     * Process css-subscriptions template
     *
     * @return array/bool
     */
    private function process_username_as_email()
    {
        $return_content = array();

        // Get customer username
        $username = $this->core->user->get_username();
        if ( !empty($username) ) {
            $return_content['customer_username'] = $username;
        }

        // Check if request password on username change
        if ( !empty($this->config['request_pwd_on_email_update']) ) {
            $return_content['request_pwd_on_email_update'] = $this->config['request_pwd_on_email_update'];
        }

        return $return_content;
    }
}