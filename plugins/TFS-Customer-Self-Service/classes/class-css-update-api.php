<?php

require_once 'class-css-user-response-object.php';

/**
 * Handles update actions in the plugins.
 */
class CSS_Update_Api
{
    /**
     * @var agora_core_framework
     */
    private $core;

    /**
     * @var CSS_Subscriptions
     */
    private $subscriptions;

    /**
     * @var CSS_Eletters
     */
    private $eletters;

    /**
     * @var CSS_Template_Manager
     */
    private $template_manager;

    /**
     * @var
     */
    private $config;

    /**
     * Construct
     *
     * @param agora_core_framework $core
     * @param CSS_Subscriptions $subscriptions
     * @param CSS_Eletters $eletters
     * @param CSS_Template_Manager $template_manager
     * @param $config
     */
    public function __construct( agora_core_framework $core, CSS_Subscriptions $subscriptions, CSS_Eletters $eletters, CSS_Template_Manager $template_manager, $config )
    {
        $this->core = $core;
        $this->subscriptions = $subscriptions;
        $this->eletters = $eletters;
        $this->template_manager = $template_manager;

        $this->config = $config;

        /** Listener to recieve the password request token from the email */
        add_action( 'init', array( $this, 'css_change_email_listener' ) );

        // Get subscription renewal notices
        add_action( 'wp_head', array( $this, 'subscription_renewals_notices' ) );

        /**	Ajax magic hooks */
        add_action( 'wp_ajax_css_open_url', array( $this, 'css_open_url' ) );
        add_action( 'wp_ajax_css_change_address', array( $this, 'css_change_address' ) );
        add_action( 'wp_ajax_css_change_password', array( $this, 'css_change_password' ) );
        add_action( 'wp_ajax_css_change_username', array( $this, 'css_change_username' ) );
        add_action( 'wp_ajax_css_add_remove_customer_list', array( $this, 'css_add_remove_customer_list' ) );
        add_action( 'wp_ajax_css_request_email_change', array( $this, 'css_request_email_change' ) );
        add_action( 'wp_ajax_css_get_state', array( $this, 'css_get_state' ) );
        add_action( 'wp_ajax_css_change_subs_email', array( $this, 'css_change_subs_email' ) );
        add_action( 'wp_ajax_css_cancel_auto_renew', array( $this, 'css_disable_auto_renew' ) );
        add_action( 'wp_ajax_css_change_single_listing_email', array( $this, 'css_change_single_listing_email' ) );
        add_action( 'wp_ajax_css_request_change_updates', array( $this, 'css_request_change_updates' ) );

    }

    /**
     *  Receives all the user lists and subscriptions and changes the email address of each of them
     *  to the actual email address in the user object.
     *
     *  @return string/bool
     */
    public function css_change_bulk_email( $lists = null, $new_email = null, $old_email = null )
    {
        if ( empty ( $lists ) ) {
            // Security Checks
            if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_bulk_email' ) ) {
                die( $this->core->get_language_variable('txt_css_nonce_error') );
            }

            $new_email = $this->core->user->_get_email();
            $old_email = sanitize_text_field( $_POST['old_email'] );
            $lists = sanitize_text_field( $_POST['css_data'] );
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        if ( ! is_email( $new_email ) || ! is_email( $old_email ) ) {
            die();
        }

        $listcodes = explode( ',', $lists );

        foreach ( $listcodes as $list ) {
            if ( ! empty( $list ) ) {
                $type = explode( "@", $list );
                $type = $type[0];

                $value = explode( "@", $list );
                $value = $value[1];

                if ( $type == 'listCode' ) {
                    if ( ! $this->core->mw->updateCustomerSignup( $old_email, $value, array( 'newEmailAddress' => $new_email, 'advStatus' => 'A' ) ) ) {
                        return false;
                    }
                }

                if ( $type == 'subref' ) {
                    if ( ! $this->core->mw->updateSubscriptionEmailAddress( $value, $new_email ) ) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     *   Creates an html list with all the subscriptions using the old customers email address, to allow the customer
     *   to submit a bulk change email request.
     *
     * @param $old_email
     * @return string | false
     */
    public function check_subscriptions_email_change( CSS_User_Response_Object $user_response_object )
    {
        $old_email = $user_response_object->get_old_email();

        // Check if email supplied is a valid email address
        if ( ! is_email( $old_email ) ) {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }

        // Get the users active subscritions
        $mw_lists = $this->subscriptions->get_filtered_active_user_subscription();

        if ( $mw_lists ) {
            foreach ( $mw_lists as $list_item ) {
                // Limit the results only to active subscriptions
                if ( in_array( strtolower( $list_item->status ), array( "r", "p", "q", "w" ) ) ) {

                    // As Middleware doesn't return the subscription email address we have to do an extra call for each
                    $subs_email = strtolower( end( agora()->mw->findSubscriptionEmailAddressBySubRef( $list_item->subref ) )->emailAddress );

                    if ( $subs_email == strtolower( $old_email ) ) {
                        $counter = true;
                        break;
                    }
                }
            }
        }

        if ( isset( $counter ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *   Creates an html list with all the eletters using the old customers email address, to allow the customer
     *   to submit a bulk change email request.
     *
     * @param $old_email
     * @return bool|string
     */
    public function check_eletters_email_change( CSS_User_Response_Object $user_response_object )
    {
        $old_email = $user_response_object->get_old_email();

        // Check if email supplied is a valid email address
        if ( ! is_email( $old_email ) ) {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }

        $mw_lists = $this->core->mw->get_customer_list_signups_by_email( $old_email );

        if ( $mw_lists ) {
            foreach ( $mw_lists as $list_item ) {

                // Limit the results only to active listings
                if ( $list_item->status == "A" ) {
                    $counter = true;
                    break;
                }
            }
        }

        if ( isset( $counter ) ) {
            return true;
        }

        return false;
    }

    /**
     * Generates a change username form on email change is the old email matches the username
     *
     * @param $old_email
     * @return bool|string
     */
    public function check_username_email_change( CSS_User_Response_Object $user_response_object )
    {
        $old_email = $user_response_object->get_old_email();

        // Check if email supplied is a valid email address
        if ( ! is_email( $old_email ) ) {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }

        $username = $this->core->user->get_username();

        if ( strtoupper( $username ) == strtoupper( $old_email ) ) {
            return true;
        }

        return false;
    }

    /**
     *  Sends an email address to warn the user that his address has been updated, using the customers email address.
     *
     * @return true | false
     */
    public function send_email_addr_update( $email_addr )
    {
        // Start formating the email
        $subject = $this->core->get_language_variable('txt_css_email_chg_addr_subject');
        $message = $this->core->get_language_variable('txt_css_email_chg_addr_body',
            array( 'site' => get_site_url() ) );
        $header = "";

        // Send as an html email
        add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );

        // Check if is a valid email
        if ( ! is_email( $email_addr ) ) {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }

        // Send email
        if ( wp_mail( $email_addr, $subject, $message, $header ) ) {
            return 1;
        } else {
            new WP_ERROR( 'send_email_addr_update', $this->core->get_language_variable('txt_css_email_error_send_email') );
            return 0;
        }
    }

    /**
     *  Sends an email address to warn the user that his password has been updated, using the customers email address.
     *
     * @return true | false
     */
    public function send_email_pwd_update()
    {
        // Start formating the email
        $email_addr = $this->core->user->_get_email();
        $subject = $this->core->get_language_variable('txt_css_email_chg_pwd_subject');
        $message = $this->core->get_language_variable('txt_css_email_chg_pwd_body',
            array( 'site' => $_SERVER['HTTP_HOST'] ) );

        $header = "";

        // Check if is a valid email
        if ( ! is_email( $email_addr ) ) {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }

        //Send as an html email
        add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );

        // Send email
        if ( wp_mail( $email_addr, $subject, $message, $header ) ) {
            return 1;
        } else {
            new WP_ERROR( 'send_email_pwd_update', $this->core->get_language_variable('txt_css_pwd_email_error') );
            return 0;
        }
    }

    /**
     *  Listens for email change requests
     **/
    public function css_change_email_listener()
    {
        // Will only work if the token and new_email vars are present
        if ( isset( $_GET['css_token'] ) ) {
            if ( get_transient( $_GET['css_token'] ) ) {
                // Clean the $_GET variables decode them and sanitize them
                $user_response_object = get_transient( $_GET['css_token'] );

                set_transient( 'el' . $user_response_object->get_old_email(), $user_response_object, 3600 );
                delete_transient( $_GET['css_token'] );
            }
        }
    }

    /**
     *  Method to sends a link to validated email address
     *  Sends a 20 bytes token and saves as a transient with the encoded email address
     */
    public function css_request_email_change()
    {
        $customer_number = $this->core->user->get_customer_number();

        if ( get_transient( 'email_changed_' . $customer_number ) ) {
            $this->remaining_time( 'email', $customer_number );
            die();
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        // Verify security Nonce
        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_email' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        // Get and parse variables from the urlencoded post data
        $local_var = wp_parse_args( sanitize_text_field( urldecode( $_POST['css_data'] ) ) );

        $new_email = urldecode( $local_var['new_email'] );
        $new_email_repeat = urldecode( $local_var['new_email_repeat'] );
        $actual_url = urldecode( $local_var['actual_url'] );

        $user = wp_get_current_user();
        if ( !is_wp_error($user) && wp_check_password( $local_var['userPassword'], $user->data->user_pass, $user->ID)
            || empty( $this->config['request_pwd_on_email_update'] ) ) {

            // Check if it's a valid email (REGEX) and sanitize
            if ( ! is_email( $new_email ) ) {
                die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
            }

            // Check if emails match
            if ( strtolower( $new_email ) <> strtolower( $new_email_repeat ) ) {
                die("Emails don't match");
            }

            $old_email = $this->core->user->_get_email();

            $user_response_object = new CSS_User_Response_Object();

            $user_response_object->set_new_email( $new_email );
            $user_response_object->set_old_email( $old_email );
            $user_response_object->set_actual_url( $actual_url );

            $eletter = $this->check_eletters_email_change( $user_response_object );
            $subs = $this->check_subscriptions_email_change( $user_response_object );
            $username = $this->check_username_email_change( $user_response_object );

            if ( $eletter || $subs || $username ) {
                set_transient( 'uro_' . $old_email, $user_response_object );

                // Load template file
                $this->template_manager->process_template(
                    'css-email-change-other-updates',
                    array(
                        'eletter' => $eletter,
                        'subs' => $subs,
                        'old_email' => $old_email,
                        'username' => $username
                    )
                );

                die();
            } else {
                $this->send_email_change_email( $user_response_object );
            }
        } else {
            die( $this->core->get_language_variable('txt_css_gen_wrong_act_pwd') );
        }
    }

    /**
     *  Request to change email
     */
    public function css_request_change_updates()
    {
        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_request_change_updates' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        $old_email = sanitize_text_field( $_POST['old_email'] );
        $css_data = sanitize_text_field( $_POST['css_data'] );

        $items = explode( ',', $css_data );

        $user_response_object = get_transient( 'uro_' . $old_email );

        foreach ( $items as $item ) {
            if ( $item == 'username' ) {
                $user_response_object->set_username( true );
            } else if ( $item == 'free' ) {
                $user_response_object->set_eletters( true );
            } else if ( $item == 'paid' ) {
                $user_response_object->set_subscriptions( true );
            }
        }

        $this->send_email_change_email( $user_response_object );
    }

    /**
     *  Send 'change email'
     */
    public function send_email_change_email( CSS_User_Response_Object $user_response_object )
    {
        $actual_url = $user_response_object->get_actual_url();

        // Generate unique random 20 bytes token and save it as a transient for 1 hour (3600 seconds)
        $key = bin2hex( random_bytes( 20 ) );
        set_transient( $key, $user_response_object, 3600 );

        // Reorganize the URL depending if the url contains an anchor or not
        $split_link = explode( "#", $actual_url );

        // Check if the anchor is present
        if ( strpos( $actual_url, '#' ) ) {
            // Generate a reformated link to force the anchor at the end
            $generated_link = strtok( $split_link[0], '?' ) . "?css_token=" . $key;
        } else {
            //Email parameters
            $generated_link = strtok( $actual_url, '?' ) . "?css_token=" . $key;
        }

        // Start formatting the email
        $subject = $this->core->get_language_variable('txt_email_chg_mail_subject');
        $message = $this->core->get_language_variable('txt_email_chg_mail_body',
            array( 'site' => get_site_url(), 'link' => $generated_link . '#css-change-email' ));

        $header = "";

        // Send as html email
        add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );

        //Send email
        if ( wp_mail( $user_response_object->get_new_email(), $subject, $message, $header ) ) {
            $customer_number = $this->core->user->get_customer_number();
            set_transient( 'email_changed_' . $customer_number, current_time('H:i:s'), 15 * 60 );

            // We have to return true as a string as jQuery will decide what to do depending the response
            echo 'true';
            die();
        } else {
            die( $this->core->get_language_variable('txt_css_email_error_send_email') );
        }
    }

    /**
     * Changes the username in Middleware, WordPress and the Middleware user object
     *
     * returns
     * true | mixed
     */
    public function css_change_username( $new_username = null )
    {
        $customer_number = $this->core->user->get_customer_number();

        if ( empty( $new_username ) ) {
            if ( get_transient( 'username_changed_' . $customer_number ) ) {
                $this->remaining_time( 'username', $customer_number );
                die();
            }

            // Verify security Nonce
            if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_username' ) ) {
                die( $this->core->get_language_variable('txt_css_nonce_error') );
            }

            $vars = wp_parse_args( $_POST['css_data'] );
            $new_username = sanitize_text_field( $vars['new_username'] );
        } else {
            $email_changed = true;
            $password_bypass = 'yes';
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        $user = wp_get_current_user();
        if ( !is_wp_error($user) && wp_check_password( $vars['userPassword'], $user->data->user_pass, $user->ID)
            || empty( $this->config['request_pwd_on_username_update'] ) || isset( $password_bypass ) )
        {
            $old_username = $this->core->user->get_username();

            // Check that the user isn't changing his password to a reserved one
            $forbidden_usernames = array(
                'admin', 'tfs', 'webmaster', 'info', 'administrator', 'security', 'police',
                'billing', 'sysadmin', 'hacker','wordpress'
            );

            if ( in_array( trim( strtolower( $new_username ) ), $forbidden_usernames ) ) {
                die($this->core->get_language_variable('txt_css_username_ilegal'));
            }

            // Security check before speaking to middleware
            if ( empty( $old_username ) && empty( $new_username ) ) {
                die( $this->core->get_language_variable('txt_css_values_empty') );
            }

            // Middleware call with the updated username
            $update_username = $this->core->mw->updateUsername( $old_username, $new_username );

            if ( ! is_wp_error( $update_username ) ) {
                if ( get_current_user_id() ) {
                    // Update WordPress database username
                    global $wpdb;
                    $wpdb->update( $wpdb->users, array( 'user_login' => $new_username ), array( 'ID' => get_current_user_id() ) );

                    // Save middleware local user object
                    $this->core->user->middleware_data->accounts[0]->id->userName = $new_username;
                    $this->core->user->save_middleware_data( get_current_user_id() );

                    set_transient( 'username_changed_' . $customer_number, current_time('H:i:s'), 15 * 60 );

                    if ( isset( $email_changed ) ) {
                        return;
                    }

                    if ( is_email( $new_username ) ) {
                        $url =  get_site_url() . "/customer-self-service/#css-change-email";

                        set_transient( get_current_user_id() . '_username_email', true);

                        die( true );
                    } else {
                        die( true );
                    }
                }
            } else {
                // If error came back from Middleware, display it
                if ( $mw_error = $update_username->error_data['post_request_failed']['body'] ) {
                    $error_message = $mw_error;
                } else {
                    $error_message = $this->core->get_language_variable('txt_css_general_error');
                }

                die( $error_message );
            }
        } else {
            die( $this->core->get_language_variable('txt_css_gen_wrong_act_pwd') );
        }
    }

    /**
     * 1.4 Changes the email address of the current user in Middleware, the users local object cache and
     * the WordPress database
     *
     *  returns true or false
     *
     * @param $new_email_address
     */
    public function css_change_email_addr( $user_response_object )
    {
        $new_email = $user_response_object->get_new_email();
        $old_email = $user_response_object->get_old_email();

        // Change WordPress User Email
        if ( is_email( $new_email ) ) {
            // Get current customer number
            $customer_number = $this->core->user->get_customer_number();

            $update_email_address = $this->core->mw->put_update_email_address( $customer_number,  $new_email );

            // Send request to Middleware and continue if no errors
            if ( ! is_wp_error( $update_email_address ) ) {

                // Update email address
                if ( ! is_wp_error( wp_update_user( array( 'ID' => get_current_user_id(), 'user_email' =>  $new_email ) ) ) ) {
                    $local_email = $this->core->user->middleware_data->emailAddresses;

                    if ( is_array( $local_email ) ) {
                        end( $local_email )->emailAddress =  $new_email;

                        $this->core->user->save_middleware_data( get_current_user_id() );

                        //Send email to the user if the backend option is activated
                        if ( $this->config['send_email_on_addr_update'] ) {
                            $this->send_email_addr_update( $new_email );
                        }
                    }
                }

                if ( $user_response_object->get_eletters() ) {
                    $mw_lists = $this->core->mw->get_customer_list_signups_by_email( $old_email );

                    $eletters = '';

                    foreach ( $mw_lists as $list_item ) {
                        // Limit the results only to active listings
                        if ( $list_item->status == "A" ) {
                            $eletters .= ',listCode@' . $list_item->listCode;
                        }
                    }

                    if ( ! $this->css_change_bulk_email( $eletters, $new_email, $old_email ) ) {
                        $update_error[] = 'Eletters';
                    }
                }

                if ( $user_response_object->get_subscriptions() ) {
                    $mw_lists = $this->subscriptions->get_filtered_active_user_subscription();
                    $subs = '';

                    foreach ( $mw_lists as $list_item ) {
                        // Limit the results only to active subscriptions
                        if ( in_array( strtolower( $list_item->status ), array( "r", "p", "q", "w" ) ) ) {
                            // As Middleware doesn't return the subscription email address we have to do an extra call for each
                            $subs_email = strtolower( end( $this->core->mw->findSubscriptionEmailAddressBySubRef( $list_item->subref ) )->emailAddress );

                            if ( $subs_email == strtolower( $old_email ) ) {
                                $subs .= ',subref@' . $list_item->subref;
                            }
                        }
                    }

                    if ( ! $this->css_change_bulk_email( $subs, $new_email, $old_email ) ) {
                        $update_error[] = 'Subscriptions';
                    }
                }

                if ( $user_response_object->get_username() ) {
                    $email_change = true;

                    if( ! $this->css_change_username( $new_email,$email_change ) ) {
                        $update_error[] = 'Username';
                    }
                }

                if ( sizeof( $update_error ) > 0 ) {
                    return $this->core->get_language_variable('txt_css_additional_update_error');
                }

                // Updated Correctly
                return true;
            } else {
                // If error came back from Middleware, display it
                if ( $mw_error = $update_email_address->error_data['post_request_failed']['body'] ) {
                    $error_message = $mw_error;
                } else {
                    $error_message = $this->core->get_language_variable('txt_css_email_update_error');
                }

                return $error_message;
            }
        } else {
            return $this->core->get_language_variable('txt_css_email_update_error');
        }
    }

    /**
     *  Updates an email address of an individual Middleware product subscription
     *
     *  returns "true" | "false"
     */
    public function css_change_subs_email()
    {
        // Verify security Nonce
        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_subs_email' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        $new_email_address = sanitize_text_field( $_POST['new_mail'] );
        $subref = sanitize_text_field( $_POST['subref'] );

        $customer_number = $this->core->user->get_customer_number();

        if ( get_transient( $subref . '_changed_' . $customer_number ) ) {
            $this->remaining_time( $subref, $customer_number );
            die();
        }

        // Change WordPress User Email
        if ( is_email( $new_email_address ) ) {
            // Send request to Middleware and continue if no errors
            if ( ! is_wp_error( $this->core->mw->updateSubscriptionEmailAddress( $subref, $new_email_address ) ) ) {
                set_transient( $subref . '_changed_' . $customer_number, current_time('H:i:s'), 15 * 60 );
                die("true");
            } else {
                die("error");
            }
        } else {
            die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );
        }
    }

    /**
     *  Updates an email address of an individual Middleware product subscription
     *
     *  returns "true" | "false"
     */
    public function css_disable_auto_renew()
    {
        // Verify security Nonce
        if ( !wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_request_disable_auto_renew' ) ) {
            $response = array('message' => $this->core->get_language_variable('txt_css_nonce_error'), 'status' => 403);
            wp_die(json_encode($response));
        }

        // Check if the user is a Middleware user
        if ( !$this->core->user->is_middleware_user() ) {
            $response = array('message' => $this->core->get_language_variable('txt_css_not_mw_user'), 'status' => 403);
            wp_die(json_encode($response));
        }

        $subref = sanitize_text_field( $_POST['subref'] );

        // Change WordPress User Email
        $update = $this->core->mw->update_subscription_auto_renew_flag( $subref, 'D' );

        if( empty($update) ) {
            $response = array('message' => $this->core->get_language_variable('txt_css_general_error'), 'status' => 500);
            wp_die(json_encode($response));
        }

        $user_id = get_current_user_id();
        set_transient( $user_id . 'ar' . $subref, current_time('H:i:s'), 30 * 60);
        $response = array('message' => $this->core->get_language_variable('txt_auto_renew_disabled'), 'status' => 200);
        wp_die(json_encode($response));
    }

    /**
     *  Updates the email address associated to a listing (eletter).
     *
     *  @return string
     */
    public function css_change_single_listing_email()
    {
        // Verify security Nonce
        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_listings_email' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        $old_mail  = sanitize_text_field( $_POST['old_mail'] );
        $new_mail  = sanitize_text_field( $_POST['new_mail'] );
        $list_code = sanitize_text_field( $_POST['listcode'] );

        // Change WordPress User Email
        if ( is_email( $new_mail ) ) {
            $customer_number = $this->core->user->get_customer_number();

            if ( get_transient( $list_code . '_changed_' . $customer_number ) ) {
                $this->remaining_time( $list_code, $customer_number );
                die();
            }

            //Make sure the new email isn't already on the list;
            $new_email_lists = $this->core->mw->get_customer_list_signups_by_email( $new_mail );

            $new_email_in_use = false;
            if( !is_wp_error($new_email_lists) && !empty($new_email_lists) ) {
                foreach($new_email_lists as $list) {
                    if ($list->listCode == $list_code) {
                        $new_email_in_use = true;
                        break;
                    }
                }
            }

            if( empty($new_email_in_use) ) {
                // Swap email addresses if the new one isn't already in use
                $agora_update = $this->core->mw->updateCustomerSignup($old_mail, $list_code, array('newEmailAddress' => $new_mail, 'advStatus' => 'A'));
            } else {
                // If the new address is already attached to the list code activate it
                $agora_update = $this->core->mw->updateCustomerSignup($new_mail, $list_code, array('advStatus' => 'A'));
                if (!is_wp_error($agora_update)) {
                    // Turn off the old email
                    $agora_update = $this->core->mw->put_unsub_customer_signup($list_code, $old_mail);
                }
            }

            if (!is_wp_error($agora_update)) {
                set_transient($list_code . '_changed_' . $customer_number, current_time('H:i:s'), 15 * 60);
                die("true");
            } else {
                die($this->css_translate_errors($agora_update->error_data['post_request_failed']['body']));
            }
        } else {
            die($this->core->get_language_variable('txt_css_email_error_invalid_email'));
        }
    }

    /**
     * Translates errors
     *
     * @return string
     */
    public function css_translate_errors( $error_msg )
    {
        $array = $this->eletters->get_local_listing_dimensional();

        foreach( $array as $item ) {
            $error_msg = str_replace( $item['code'], $item['title'], $error_msg );
        }

        return str_replace( '"','',$error_msg );
    }

    /**
     *   Subscribes or unsubscribes a customer from a list (eletter)
     *
     *   returns - string
     */
    public function css_add_remove_customer_list()
    {
        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        // get variables
        $list_xcode = '';
        $list_code = '';
        $list_email = '';
        $list_action = '';
	$list_subname = '';
	$list_smsnbr = '';
        $security = '';

        extract( $_POST, EXTR_OVERWRITE) ;
        // Verify security Nonce
        if ( empty($security) || !wp_verify_nonce( sanitize_key( $security ), 'css_add_remove_customer_list' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }



        if( !empty( $list_code ) ) {
            $list_code = sanitize_text_field( $list_code );
        }
        if( !empty( $list_xcode ) ) {
            $list_xcode = sanitize_text_field( $list_xcode );
        }
        if( !empty( $list_action ) ) {
            $list_action= sanitize_text_field( $list_action );
        }
        if( !empty( $list_email ) ) {
            $list_email = sanitize_text_field( $list_email );
        }
        if( !empty( $list_subname ) ) {
            $list_subname = sanitize_text_field( $list_subname );
        }
        if( !empty( $list_smsnbr ) ) {
            $list_smsnbr = sanitize_text_field( $list_smsnbr );
        }		

        if ( !is_email( $list_email ) ) {
		if ( !false === $smsList = strrpos($list_code, '_SMS', -4) ) {
			die( $this->core->get_language_variable('txt_css_email_error_invalid_email') );	
		}
        }

        // Define the action (add or remove list)
        switch ( $list_action ) {
            case "add":
                $signup = $this->core->mw->put_customer_signup_by_email( $list_email, $list_code, $list_xcode, null );
                if ( !empty( $signup ) && !is_wp_error( $signup ) ) {
                    echo "true";
                } else {
                    die( $this->css_translate_errors( $this->core->get_language_variable( 'txt_css_list_subscribed_error' ) . $list_code ) );
                }
                break;
				
            case "add_sms":
                $signup = $this->core->mw->put_customer_signup_by_email_sms( $list_email, $list_code, $list_xcode, null, $list_smsnbr );
                if ( !empty( $signup ) && !is_wp_error( $signup ) ) {
                    echo "true";
                } else {
                    die( $this->css_translate_errors( $this->core->get_language_variable( 'txt_css_list_subscribed_error' ) . $list_code ) );
                }
                break;

            case "remove":
                $remove = $this->core->mw->put_unsub_customer_signup( $list_code, $list_email, $reference = null );
                if ( !empty( $remove ) && !is_wp_error( $remove ) ) {
                    echo "true";
                } else {
			if ($remove->error_data['post_request_failed']['response']['code'] === 422) {
				die( $this->css_translate_errors( 'Our records show you are not subscribed to <em>' . $list_subname . '</em> offers.' ) );
			} else {
				die( $this->css_translate_errors( $this->core->get_language_variable( 'txt_css_list_unsubscribed_error' ) . $list_email . ' from ' . $list_code ) );
			}
                }
                break;

	    case "remove_sms":
	    	//TODO: Address SMS (emailId)
		// https://wiki.14west.us/display/MWSUPPORT/Unsubscribe+Customer+Signup
                $remove = $this->core->mw->put_unsub_customer_signup_sms( $list_code, $list_email, $reference = null, $list_smsnbr );				
				
                if ( !empty( $remove ) && !is_wp_error( $remove ) ) {
                    echo "true";
                } else {
			if ($remove->error_data['post_request_failed']['response']['code'] === 422) {
				die( $this->css_translate_errors( 'Our records show you are not subscribed to <em>' . $list_subname . '</em> offers.' ) );
			} else {
				die( $this->css_translate_errors( $this->core->get_language_variable( 'txt_css_list_unsubscribed_error' ) . $list_email . ' from ' . $list_code ) );
			}
                }
                break;
				
            default:
                die( $this->css_translate_errors( $this->core->get_language_variable( 'txt_css_general_error' ) ) );
        }

        die();
    }

    /**
     * Changes the customer password in Middleware and WordPress
     *
     * string | WP_ERROR
     */
    public function css_change_password()
    {
        $customer_number = $this->core->user->get_customer_number();

        if ( get_transient( 'pwd_changed_' . $customer_number ) ) {
            $this->remaining_time( 'pwd', $customer_number );
            die();
        }

        // Verify security Nonce
        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_password' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        //  Get the post payload with the data
        $payload = sanitize_text_field( $_POST['css_data'] );

        // Verify that the payload in not empty
        if ( empty( $payload ) ) {
            new WP_ERROR( 'css_change_password', 'Empty Payload' );
            die();
        }

        $customer_number = $this->core->user->get_customer_number();

        // Check the the customer number exists
        if ( is_null( $customer_number ) ) {
            die( $this->core->get_language_variable('txt_css_pwd_customer_id_error') );
        } else {
            $defaults = array(
                'customerNumber' => $customer_number
            );

            $payload = wp_parse_args( $payload, $defaults );

            // Check if the current password is correct
            $user = wp_get_current_user();
            if ( is_wp_error($user) || !wp_check_password( $payload['existingPassword'], $user->data->user_pass, $user->ID) ) {
                die( 'wrongPass' );
            }

            // Check that passwords are correct
            if ( $payload['newPassword'] !== $payload['newPassword_repeat'] ) {
                die( 'mismatch' );
            }

            // Check the length of the password and return personalized error message
            if ( strlen( $payload['newPassword'] ) < $this->config['min_length_pwd'] ) {
                die( 'short' . str_replace( '{0}', $this->config['min_length_pwd'], $this->core->get_language_variable('txt_css_pwd_min_length') ) );
            }

            // Update the password if there is not an error
            $chg_pwd = $this->core->mw->put_update_password(
                $customer_number,
                $GLOBALS['user_login'],
                $payload['existingPassword'],
                $payload['newPassword']
            );
            
            if ( is_wp_error( $chg_pwd ) ) {
                die( $this->core->get_language_variable( 'txt_css_general_error' ) );
            } else {
                //Update the user cache to avoid automatic logout
                $userdata['ID'] = get_current_user_id(); //admin user ID
                $userdata['user_pass'] = $payload['newPassword'];

                //Update the User DATA
                wp_update_user( $userdata );

                // Save the middleware user object
                $this->core->user->middleware_data->accounts[0]->password = $payload['newPassword'];
                $this->core->user->save_middleware_data( get_current_user_id() );

                // Send email to the user if the backend option is activated
                if ( $this->config['send_email_on_pwd_update'] ) {
                    $this->send_email_pwd_update();
                }

                set_transient( 'pwd_changed_' . $customer_number, current_time('H:i:s'), 15 * 60 );

                die("true");
            }
        }
    }

    /**
     * Updates the local middleware user object in the WordPress installation
     *
     * @param $payload
     */
    public function css_update_user( $payload )
    {
        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        // Detect that the payload is not empty
        if ( empty( $payload ) ) {
            die('Empty Payload');
        }

        // Define the fields to update in an array
        $fields = array(
            'countryCode', 'postalCode', 'street', 'street2', 'street3', 'city', 'state', 'county',
            'firstName', 'middleInitial', 'lastName', 'companyName', 'departmentName', 'phoneNumber',
            'phoneNumber2', 'phoneNumber3', 'faxNumber', 'birthDate'
        );

        foreach ( $fields as $index => $field ) {
            $this->core->user->wp_user->data->middleware_data->postalAddresses[0]->{$field} = sanitize_text_field( $payload[$field] );
        }

        //Save middleware data (local)
        $this->core->user->save_middleware_data( get_current_user_id() );
    }
	
    /**
     * Updates the user information (name, address and contact details ) into middleware and the WP user local object,
     * sends an email in every address update if checked and requests users password.
     */
    function css_change_address()
    {
        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        if ( ! wp_verify_nonce( sanitize_key( $_POST['security'] ), 'css_change_address' ) ) {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        $customer_number = $this->core->user->get_customer_number();

        $payload = $_POST['css_data'];
        $defaults = array( 'customerNumber' => $customer_number );
        $payload = wp_parse_args( $payload, $defaults );


        $user = wp_get_current_user();
        if ( !is_wp_error($user) && wp_check_password( $payload['userPassword'], $user->data->user_pass, $user->ID)
            || empty( $this->config['request_pwd_on_addr_update'] ) ) {

            if ( empty( $customer_number ) ) {
                die( $this->core->get_language_variable('txt_css_pwd_customer_id_error') );
            } else {
                $update_user = $this->core->mw->put_update_postal_address( $payload );

                if ( ! is_wp_error( $update_user ) ) {
                    //update local user object
                    $this->css_update_user( $payload );

                    //Send email to the user
                    if ( $this->config['send_email_on_addr_update'] ) {
                        $this->send_email_addr_update( $this->core->user->_get_email());
                    }

                    die('true');
                } else {
                    // If error came back from Middleware, display it
                    if ( !empty( $update_user->error_data['post_request_failed']['body'] )
                        && $update_user->error_data['post_request_failed']['response']['code'] !== 500 ) {
                        $error_message = $update_user->error_data['post_request_failed']['body'];
                    } else {
                        $error_message = $this->core->get_language_variable('txt_css_general_error');
                    }

                    die('<div class="css_response error">' . $error_message . '</div>');
                }
            }
        } else {
            die( $this->core->get_language_variable('txt_css_addr_pwd_error') );
        }
    }

    /**
     *  Generates an html dropdown with a country list,
     *  It automatically selects the one passed  in the $code var
     *
     *  returns mixed
     */
    public function css_country_selector( $code = null )
    {
        $code = sanitize_text_field( $code );

        //Last Position of the arrays indicates if the given country has state or county
        // 0 show both
        // 1 show state
        // 2 show county

        $countries = array(
            "AF" => array("AFGHANISTAN", "AF", "AFG", "004",0),
            "AL" => array("ALBANIA", "AL", "ALB", "008",0),
            "DZ" => array("ALGERIA", "DZ", "DZA", "012",0),
            "AS" => array("AMERICAN SAMOA", "AS", "ASM", "016",0),
            "AD" => array("ANDORRA", "AD", "AND", "020",0),
            "AO" => array("ANGOLA", "AO", "AGO", "024",0),
            "AI" => array("ANGUILLA", "AI", "AIA", "660",0),
            "AQ" => array("ANTARCTICA", "AQ", "ATA", "010",0),
            "AG" => array("ANTIGUA AND BARBUDA", "AG", "ATG", "028",0),
            "AR" => array("ARGENTINA", "AR", "ARG", "032",0),
            "AM" => array("ARMENIA", "AM", "ARM", "051",0),
            "AW" => array("ARUBA", "AW", "ABW", "533",0),
            "AU" => array("AUSTRALIA", "AU", "AUS", "036",0),
            "AT" => array("AUSTRIA", "AT", "AUT", "040",0),
            "AZ" => array("AZERBAIJAN", "AZ", "AZE", "031",0),
            "BS" => array("BAHAMAS", "BS", "BHS", "044",0),
            "BH" => array("BAHRAIN", "BH", "BHR", "048",0),
            "BD" => array("BANGLADESH", "BD", "BGD", "050",0),
            "BB" => array("BARBADOS", "BB", "BRB", "052",0),
            "BY" => array("BELARUS", "BY", "BLR", "112",0),
            "BE" => array("BELGIUM", "BE", "BEL", "056",0),
            "BZ" => array("BELIZE", "BZ", "BLZ", "084",0),
            "BJ" => array("BENIN", "BJ", "BEN", "204",0),
            "BM" => array("BERMUDA", "BM", "BMU", "060",0),
            "BT" => array("BHUTAN", "BT", "BTN", "064",0),
            "BO" => array("BOLIVIA", "BO", "BOL", "068",0),
            "BA" => array("BOSNIA AND HERZEGOVINA", "BA", "BIH", "070",0),
            "BW" => array("BOTSWANA", "BW", "BWA", "072",0),
            "BV" => array("BOUVET ISLAND", "BV", "BVT", "074",0),
            "BR" => array("BRAZIL", "BR", "BRA", "076",0),
            "IO" => array("BRITISH INDIAN OCEAN TERRITORY", "IO", "IOT", "086",0),
            "BN" => array("BRUNEI DARUSSALAM", "BN", "BRN", "096",0),
            "BG" => array("BULGARIA", "BG", "BGR", "100",0),
            "BF" => array("BURKINA FASO", "BF", "BFA", "854",0),
            "BI" => array("BURUNDI", "BI", "BDI", "108",0),
            "KH" => array("CAMBODIA", "KH", "KHM", "116",0),
            "CM" => array("CAMEROON", "CM", "CMR", "120",0),
            "CA" => array("CANADA", "CA", "CAN", "124",1),
            "CV" => array("CAPE VERDE", "CV", "CPV", "132",0),
            "KY" => array("CAYMAN ISLANDS", "KY", "CYM", "136",0),
            "CF" => array("CENTRAL AFRICAN REPUBLIC", "CF", "CAF", "140",0),
            "TD" => array("CHAD", "TD", "TCD", "148",0),
            "CL" => array("CHILE", "CL", "CHL", "152",0),
            "CN" => array("CHINA", "CN", "CHN", "156",0),
            "CX" => array("CHRISTMAS ISLAND", "CX", "CXR", "162",0),
            "CC" => array("COCOS (KEELING) ISLANDS", "CC", "CCK", "166",0),
            "CO" => array("COLOMBIA", "CO", "COL", "170",0),
            "KM" => array("COMOROS", "KM", "COM", "174",0),
            "CG" => array("CONGO", "CG", "COG", "178",0),
            "CK" => array("COOK ISLANDS", "CK", "COK", "184",0),
            "CR" => array("COSTA RICA", "CR", "CRI", "188",0),
            "CI" => array("COTE D'IVOIRE", "CI", "CIV", "384",0),
            "HR" => array("CROATIA (local name: Hrvatska)", "HR", "HRV", "191",0),
            "CU" => array("CUBA", "CU", "CUB", "192",0),
            "CY" => array("CYPRUS", "CY", "CYP", "196",0),
            "CZ" => array("CZECH REPUBLIC", "CZ", "CZE", "203",0),
            "DK" => array("DENMARK", "DK", "DNK", "208",0),
            "DJ" => array("DJIBOUTI", "DJ", "DJI", "262",0),
            "DM" => array("DOMINICA", "DM", "DMA", "212",0),
            "DO" => array("DOMINICAN REPUBLIC", "DO", "DOM", "214",0),
            "TL" => array("EAST TIMOR", "TL", "TLS", "626",0),
            "EC" => array("ECUADOR", "EC", "ECU", "218",0),
            "EG" => array("EGYPT", "EG", "EGY", "818",0),
            "SV" => array("EL SALVADOR", "SV", "SLV", "222",0),
            "GQ" => array("EQUATORIAL GUINEA", "GQ", "GNQ", "226",0),
            "ER" => array("ERITREA", "ER", "ERI", "232",0),
            "EE" => array("ESTONIA", "EE", "EST", "233",0),
            "ET" => array("ETHIOPIA", "ET", "ETH", "210",0),
            "FK" => array("FALKLAND ISLANDS (MALVINAS)", "FK", "FLK", "238",0),
            "FO" => array("FAROE ISLANDS", "FO", "FRO", "234",0),
            "FJ" => array("FIJI", "FJ", "FJI", "242",0),
            "FI" => array("FINLAND", "FI", "FIN", "246",0),
            "FR" => array("FRANCE", "FR", "FRA", "250",0),
            "FX" => array("FRANCE, METROPOLITAN", "FX", "FXX", "249",0),
            "GF" => array("FRENCH GUIANA", "GF", "GUF", "254",0),
            "PF" => array("FRENCH POLYNESIA", "PF", "PYF", "258",0),
            "TF" => array("FRENCH SOUTHERN TERRITORIES", "TF", "ATF", "260",0),
            "GA" => array("GABON", "GA", "GAB", "266",0),
            "GM" => array("GAMBIA", "GM", "GMB", "270",0),
            "GE" => array("GEORGIA", "GE", "GEO", "268",0),
            "DE" => array("GERMANY", "DE", "DEU", "276",0),
            "GH" => array("GHANA", "GH", "GHA", "288",0),
            "GI" => array("GIBRALTAR", "GI", "GIB", "292",0),
            "GR" => array("GREECE", "GR", "GRC", "300",0),
            "GL" => array("GREENLAND", "GL", "GRL", "304",0),
            "GD" => array("GRENADA", "GD", "GRD", "308",0),
            "GP" => array("GUADELOUPE", "GP", "GLP", "312",0),
            "GU" => array("GUAM", "GU", "GUM", "316",0),
            "GT" => array("GUATEMALA", "GT", "GTM", "320",0),
            "GN" => array("GUINEA", "GN", "GIN", "324",0),
            "GW" => array("GUINEA-BISSAU", "GW", "GNB", "624",0),
            "GY" => array("GUYANA", "GY", "GUY", "328",0),
            "HT" => array("HAITI", "HT", "HTI", "332",0),
            "HM" => array("HEARD ISLAND & MCDONALD ISLANDS", "HM", "HMD", "334",0),
            "HN" => array("HONDURAS", "HN", "HND", "340",0),
            "HK" => array("HONG KONG", "HK", "HKG", "344",0),
            "HU" => array("HUNGARY", "HU", "HUN", "348",0),
            "IS" => array("ICELAND", "IS", "ISL", "352",0),
            "IN" => array("INDIA", "IN", "IND", "356",0),
            "ID" => array("INDONESIA", "ID", "IDN", "360",0),
            "IR" => array("IRAN, ISLAMIC REPUBLIC OF", "IR", "IRN", "364",0),
            "IQ" => array("IRAQ", "IQ", "IRQ", "368",0),
            "IE" => array("IRELAND", "IE", "IRL", "372",2),
            "IL" => array("ISRAEL", "IL", "ISR", "376",0),
            "IT" => array("ITALY", "IT", "ITA", "380",0),
            "JM" => array("JAMAICA", "JM", "JAM", "388",0),
            "JP" => array("JAPAN", "JP", "JPN", "392",0),
            "JO" => array("JORDAN", "JO", "JOR", "400",0),
            "KZ" => array("KAZAKHSTAN", "KZ", "KAZ", "398",0),
            "KE" => array("KENYA", "KE", "KEN", "404",0),
            "KI" => array("KIRIBATI", "KI", "KIR", "296",0),
            "KP" => array("KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF", "KP", "PRK", "408",0),
            "KR" => array("KOREA, REPUBLIC OF", "KR", "KOR", "410",0),
            "KW" => array("KUWAIT", "KW", "KWT", "414",0),
            "KG" => array("KYRGYZSTAN", "KG", "KGZ", "417",0),
            "LA" => array("LAO PEOPLE'S DEMOCRATIC REPUBLIC", "LA", "LAO", "418",0),
            "LV" => array("LATVIA", "LV", "LVA", "428",0),
            "LB" => array("LEBANON", "LB", "LBN", "422",0),
            "LS" => array("LESOTHO", "LS", "LSO", "426",0),
            "LR" => array("LIBERIA", "LR", "LBR", "430",0),
            "LY" => array("LIBYAN ARAB JAMAHIRIYA", "LY", "LBY", "434",0),
            "LI" => array("LIECHTENSTEIN", "LI", "LIE", "438",0),
            "LT" => array("LITHUANIA", "LT", "LTU", "440",0),
            "LU" => array("LUXEMBOURG", "LU", "LUX", "442",0),
            "MO" => array("MACAU", "MO", "MAC", "446",0),
            "MK" => array("MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF", "MK", "MKD", "807",0),
            "MG" => array("MADAGASCAR", "MG", "MDG", "450",0),
            "MW" => array("MALAWI", "MW", "MWI", "454",0),
            "MY" => array("MALAYSIA", "MY", "MYS", "458",0),
            "MV" => array("MALDIVES", "MV", "MDV", "462",0),
            "ML" => array("MALI", "ML", "MLI", "466",0),
            "MT" => array("MALTA", "MT", "MLT", "470",0),
            "MH" => array("MARSHALL ISLANDS", "MH", "MHL", "584",0),
            "MQ" => array("MARTINIQUE", "MQ", "MTQ", "474",0),
            "MR" => array("MAURITANIA", "MR", "MRT", "478",0),
            "MU" => array("MAURITIUS", "MU", "MUS", "480",0),
            "YT" => array("MAYOTTE", "YT", "MYT", "175",0),
            "MX" => array("MEXICO", "MX", "MEX", "484",0),
            "FM" => array("MICRONESIA, FEDERATED STATES OF", "FM", "FSM", "583",0),
            "MD" => array("MOLDOVA, REPUBLIC OF", "MD", "MDA", "498",0),
            "MC" => array("MONACO", "MC", "MCO", "492",0),
            "MN" => array("MONGOLIA", "MN", "MNG", "496",0),
            "MS" => array("MONTSERRAT", "MS", "MSR", "500",0),
            "MA" => array("MOROCCO", "MA", "MAR", "504",0),
            "MZ" => array("MOZAMBIQUE", "MZ", "MOZ", "508",0),
            "MM" => array("MYANMAR", "MM", "MMR", "104",0),
            "NA" => array("NAMIBIA", "NA", "NAM", "516",0),
            "NR" => array("NAURU", "NR", "NRU", "520",0),
            "NP" => array("NEPAL", "NP", "NPL", "524",0),
            "NL" => array("NETHERLANDS", "NL", "NLD", "528",0),
            "AN" => array("NETHERLANDS ANTILLES", "AN", "ANT", "530",0),
            "NC" => array("NEW CALEDONIA", "NC", "NCL", "540",0),
            "NZ" => array("NEW ZEALAND", "NZ", "NZL", "554",0),
            "NI" => array("NICARAGUA", "NI", "NIC", "558",0),
            "NE" => array("NIGER", "NE", "NER", "562",0),
            "NG" => array("NIGERIA", "NG", "NGA", "566",0),
            "NU" => array("NIUE", "NU", "NIU", "570",0),
            "NF" => array("NORFOLK ISLAND", "NF", "NFK", "574",0),
            "MP" => array("NORTHERN MARIANA ISLANDS", "MP", "MNP", "580",0),
            "NO" => array("NORWAY", "NO", "NOR", "578",0),
            "OM" => array("OMAN", "OM", "OMN", "512",0),
            "PK" => array("PAKISTAN", "PK", "PAK", "586",0),
            "PW" => array("PALAU", "PW", "PLW", "585",0),
            "PA" => array("PANAMA", "PA", "PAN", "591",0),
            "PG" => array("PAPUA NEW GUINEA", "PG", "PNG", "598",0),
            "PY" => array("PARAGUAY", "PY", "PRY", "600",0),
            "PE" => array("PERU", "PE", "PER", "604",0),
            "PH" => array("PHILIPPINES", "PH", "PHL", "608",0),
            "PN" => array("PITCAIRN", "PN", "PCN", "612",0),
            "PL" => array("POLAND", "PL", "POL", "616",0),
            "PT" => array("PORTUGAL", "PT", "PRT", "620",0),
            "PR" => array("PUERTO RICO", "PR", "PRI", "630",0),
            "QA" => array("QATAR", "QA", "QAT", "634",0),
            "RE" => array("REUNION", "RE", "REU", "638",0),
            "RO" => array("ROMANIA", "RO", "ROU", "642",0),
            "RU" => array("RUSSIAN FEDERATION", "RU", "RUS", "643",0),
            "RW" => array("RWANDA", "RW", "RWA", "646",0),
            "KN" => array("SAINT KITTS AND NEVIS", "KN", "KNA", "659",0),
            "LC" => array("SAINT LUCIA", "LC", "LCA", "662",0),
            "VC" => array("SAINT VINCENT AND THE GRENADINES", "VC", "VCT", "670",0),
            "WS" => array("SAMOA", "WS", "WSM", "882",0),
            "SM" => array("SAN MARINO", "SM", "SMR", "674",0),
            "ST" => array("SAO TOME AND PRINCIPE", "ST", "STP", "678",0),
            "SA" => array("SAUDI ARABIA", "SA", "SAU", "682",0),
            "SN" => array("SENEGAL", "SN", "SEN", "686",0),
            "RS" => array("SERBIA", "RS", "SRB", "688",0),
            "SC" => array("SEYCHELLES", "SC", "SYC", "690",0),
            "SL" => array("SIERRA LEONE", "SL", "SLE", "694",0),
            "SG" => array("SINGAPORE", "SG", "SGP", "702",0),
            "SK" => array("SLOVAKIA (Slovak Republic)", "SK", "SVK", "703",0),
            "SI" => array("SLOVENIA", "SI", "SVN", "705",0),
            "SB" => array("SOLOMON ISLANDS", "SB", "SLB", "90",0),
            "SO" => array("SOMALIA", "SO", "SOM", "706",0),
            "ZA" => array("SOUTH AFRICA", "ZA", "ZAF","710",0),
            "ES" => array("SPAIN", "ES", "ESP", "724",0),
            "LK" => array("SRI LANKA", "LK", "LKA", "144",0),
            "SH" => array("SAINT HELENA", "SH", "SHN", "654",0),
            "PM" => array("SAINT PIERRE AND MIQUELON", "PM", "SPM", "666",0),
            "SD" => array("SUDAN", "SD", "SDN", "736",0),
            "SR" => array("SURINAME", "SR", "SUR", "740",0),
            "SJ" => array("SVALBARD AND JAN MAYEN ISLANDS", "SJ", "SJM", "744",0),
            "SZ" => array("SWAZILAND", "SZ", "SWZ", "748",0),
            "SE" => array("SWEDEN", "SE", "SWE", "752",0),
            "CH" => array("SWITZERLAND", "CH", "CHE", "756",0),
            "SY" => array("SYRIAN ARAB REPUBLIC", "SY", "SYR", "760",0),
            "TW" => array("TAIWAN, PROVINCE OF CHINA", "TW", "TWN", "158",0),
            "TJ" => array("TAJIKISTAN", "TJ", "TJK", "762",0),
            "TZ" => array("TANZANIA, UNITED REPUBLIC OF", "TZ", "TZA", "834",0),
            "TH" => array("THAILAND", "TH", "THA", "764",0),
            "TG" => array("TOGO", "TG", "TGO", "768",0),
            "TK" => array("TOKELAU", "TK", "TKL", "772",0),
            "TO" => array("TONGA", "TO", "TON", "776",0),
            "TT" => array("TRINIDAD AND TOBAGO", "TT", "TTO", "780",0),
            "TN" => array("TUNISIA", "TN", "TUN", "788",0),
            "TR" => array("TURKEY", "TR", "TUR", "792",0),
            "TM" => array("TURKMENISTAN", "TM", "TKM", "795",0),
            "TC" => array("TURKS AND CAICOS ISLANDS", "TC", "TCA", "796",0),
            "TV" => array("TUVALU", "TV", "TUV", "798",0),
            "UG" => array("UGANDA", "UG", "UGA", "800",0),
            "UA" => array("UKRAINE", "UA", "UKR", "804",0),
            "AE" => array("UNITED ARAB EMIRATES", "AE", "ARE", "784",0),
            "GB" => array("UNITED KINGDOM", "GB", "GBR", "826",2),

            //USA 3 DIGIT CODE IS EMPTY TO MAKE IT WORK WITH MIDDLEWARE
            "US" => array("UNITED STATES", "US", "", "840",1),

            "UM" => array("UNITED STATES MINOR OUTLYING ISLANDS", "UM", "UMI", "581",0),
            "UY" => array("URUGUAY", "UY", "URY", "858",0),
            "UZ" => array("UZBEKISTAN", "UZ", "UZB", "860",0),
            "VU" => array("VANUATU", "VU", "VUT", "548",0),
            "VA" => array("VATICAN CITY STATE (HOLY SEE)", "VA", "VAT", "336",0),
            "VE" => array("VENEZUELA", "VE", "VEN", "862",0),
            "VN" => array("VIET NAM", "VN", "VNM", "704",0),
            "VG" => array("VIRGIN ISLANDS (BRITISH)", "VG", "VGB", "92",0),
            "VI" => array("VIRGIN ISLANDS (U.S.)", "VI", "VIR", "850",0),
            "WF" => array("WALLIS AND FUTUNA ISLANDS", "WF", "WLF", "876",0),
            "EH" => array("WESTERN SAHARA", "EH", "ESH", "732",0),
            "YE" => array("YEMEN", "YE", "YEM", "887",0),
            "YU" => array("YUGOSLAVIA", "YU", "YUG", "891",0),
            "ZR" => array("ZAIRE", "ZR", "ZAR", "180",0),
            "ZM" => array("ZAMBIA", "ZM", "ZMB", "894",0),
            "ZW" => array("ZIMBABWE", "ZW", "ZWE", "716",0),
        );

        echo '<br /><select id="tfs_css_countryCode" name="countryCode">';
        foreach ( $countries as $country ) {
            echo '<option  county="' . $country[4] . '"  value="' . $country[2] . '"';
            if ( strtoupper( $code ) == $country[2] ) {
                echo " selected='selected'";
            }
            echo ' >' . ucwords( strtolower( $country[0] ) ) . '</option>';
        }

        echo '</select>';
    }

    /**
     *  Depending if the country is in the list or not will generate a textfield or a dropdown with the list of all the
     *  states, selecting by default the state passed.
     *
     * @param $country - optional
     * @param $state - optional
     *
     * returns mixed
     */
    public function css_get_state( $country, $state = '' )
    {
        // Check if the user is a Middleware user
        if ( ! $this->core->user->is_middleware_user() ) {
            die( $this->core->get_language_variable('txt_css_not_mw_user') );
        }

        //Check if we are doing an ajax call
        if ( isset( $_POST['countryCode'] ) ) {
            $country = sanitize_text_field($_POST['countryCode']);
        }

        //Middleware hack
        if ( empty( $country ) ) {
            $country = "USA";
        }

        //Countries where we are going to display a drop down
        $state_contries = array( 'USA', 'CAN' );

        if ( ! in_array( $country, $state_contries ) ) {
            if(!empty($state)) {
                echo '<input type="text" value="'.$state.'" name="state" id="tfs_css_state">';
            } else {
                echo '<input type="text" value="" name="state" id="tfs_css_state">';
            }

        } else {
            switch ( $country ) {
                case "CAN":
                    $state_list = array(
                        'AB' => 'Alberta',
                        'BC' => 'British Columbia',
                        'MB' => 'Manitoba',
                        'NB' => 'New Brunswick',
                        'NL' => 'Newfoundland and Labrador',
                        'NT' => 'Northwest Territories',
                        'NS' => 'Nova Scotia',
                        'NU' => 'Nunavut',
                        'ON' => 'Ontario',
                        'PE' => 'Prince Edward Island',
                        'QC' => 'Quebec',
                        'SK' => 'Saskatchewan',
                        'YT' => 'Yukon');
                    break;

                case "USA":
                    $state_list = array(
                        'AL' => 'Alabama',
                        'AK' => 'Alaska',
                        'AZ' => 'Arizona',
                        'AR' => 'Arkansas',
                        'CA' => 'California',
                        'CO' => 'Colorado',
                        'CT' => 'Connecticut',
                        'DE' => 'Delaware',
                        'DC' => 'District of Columbia',
                        'FL' => 'Florida',
                        'GA' => 'Georgia',
                        'HI' => 'Hawaii',
                        'ID' => 'Idaho',
                        'IL' => 'Illinois',
                        'IN' => 'Indiana',
                        'IA' => 'Iowa',
                        'KS' => 'Kansas',
                        'KY' => 'Kentucky',
                        'LA' => 'Louisiana',
                        'ME' => 'Maine',
                        'MD' => 'Maryland',
                        'MA' => 'Massachusetts',
                        'MI' => 'Michigan',
                        'MN' => 'Minnesota',
                        'MS' => 'Mississippi',
                        'MO' => 'Missouri',
                        'MT' => 'Montana',
                        'NE' => 'Nebraska',
                        'NV' => 'Nevada',
                        'NH' => 'New Hampshire',
                        'NJ' => 'New Jersey',
                        'NM' => 'New Mexico',
                        'NY' => 'New York',
                        'NC' => 'North Carolina',
                        'ND' => 'North Dakota',
                        'OH' => 'Ohio',
                        'OK' => 'Oklahoma',
                        'OR' => 'Oregon',
                        'PA' => 'Pennsylvania',
                        'RI' => 'Rhode Island',
                        'SC' => 'South Carolina',
                        'SD' => 'South Dakota',
                        'TN' => 'Tennessee',
                        'TX' => 'Texas',
                        'UT' => 'Utah',
                        'VT' => 'Vermont',
                        'VA' => 'Virginia',
                        'WA' => 'Washington',
                        'WV' => 'West Virginia',
                        'WI' => 'Wisconsin',
                        'WY' => 'Wyoming',
                    );
                    break;
            }

            $html = "<select class='tfs_css_state_dropdown' id='tfs_css_state'>";

            foreach ( $state_list as $key => $value ) {
                $html .= " <option value=\"" . $key . "\"";

                if ( $key == $state ) {
                    $html .= "selected='selected'";
                }

                $html .= ">" . $value . "</option>";
            }

            $html .= "</select>";

            echo $html;
        }

        // Dirty hack is to avoid displaying the 0 in the ajax calls
        if ( isset( $_POST['countryCode'] ) ) {
            die();
        }
    }

    /**
     *  Get remaining time
     */
    public function remaining_time( $type, $customer_number )
    {
        $remaining_time_in_minutes = 15 - round( abs( strtotime( current_time('H:i:s')) - strtotime( get_transient( $type.'_changed_' . $customer_number ) ) ) / 60 );

        $remaining_time_in_minutes = $remaining_time_in_minutes == 0 ? '1' : $remaining_time_in_minutes;

        $minute = $remaining_time_in_minutes == 1 ? 'minute' : 'minutes';

        $remaining_time_in_minutes = $remaining_time_in_minutes . ' ' . $minute;

        if ( $this->core->get_language_variable('txt_css_' . $type . '_changed_recently', array( 'time' => $remaining_time_in_minutes ) ) ) {
            echo $this->core->get_language_variable('txt_css_' . $type.'_changed_recently', array( 'time' => $remaining_time_in_minutes ) );
        } else {
            echo $this->core->get_language_variable('txt_css_default_changed_recently', array( 'time' => $remaining_time_in_minutes ) );
        }
    }

    /**
     *  Returns the circ_status code in a non technical word
     *
     *  @param $code - the circ_status code (c,e,r,p,q,w)
     *
     *  @return string
     */
    public function resolve_circ_status( $code )
    {
        switch ( strtolower( $code ) ) {
            case "c":
                return __("Cancelled");
                break;
            case "e":
                return __("Expired");
                break;
            case "r" or "p" or "q" or "w";
                return __("Active");
                break;
            default:
                return $code;
        }
    }

    /**
     * Returns the compiled content of a frontend view file (normally php file) situated in '/view/frontend/default'
     * of the plugin.
     */
    public function css_open_url()
    {
        if ( check_ajax_referer( 'css_open_url', 'security' ) ) {
            // Check if the user is a Middleware user
            if ( ! $this->core->user->is_middleware_user() ) {
                die( $this->core->get_language_variable('txt_css_not_mw_user') );
            }

            // Clean Post data
            $file = sanitize_file_name( $_POST['template'] );
            $file = str_replace( ".", '', $file );

            $old_email = $this->core->user->_get_email();

            // Changes the email address if present
            if ( get_transient( 'el' . $old_email ) ) {
                $user_response = get_transient( 'el' . $old_email );

                $response = $this->css_change_email_addr( $user_response );

                // Load template file
                $this->template_manager->process_template(
                    'css-change-bulk-email',
                    array(
                        'response' => $response
                    )
                );

                delete_transient( 'el' . $old_email );
                die();
            }

            // Check transient for email change
            $customer_number = $this->core->user->get_customer_number();
            $email_transient = get_transient( 'email_changed_' . $customer_number );
            $user_transient = get_transient( get_current_user_id() . '_username_email' );

            if ( !empty($user_transient) && empty($email_transient) ) {
                $this->template_manager->process_template( 'css-account-landing' );
                delete_transient( get_current_user_id() . '_username_email' );
                die();
            } else {
                // Load template file
                $this->template_manager->process_template( $file );
            }
        } else {
            die( $this->core->get_language_variable('txt_css_nonce_error') );
        }

        die();
    }

    /**
     *  Subscription renewals notice
     *
     *  @return string
     */
    public function subscription_renewals_notices()
    {
        // Check if there are subscription renewal notices
        if ( $subscription_renewal_notices = $this->subscriptions->process_subscription_renewals_notices() ) {
            // Load template file
            $this->template_manager->process_template(
                'css-subscription-renewals',
                array(
                    'subscription_renewals' => $subscription_renewal_notices
                )
            );
        }
    }
}