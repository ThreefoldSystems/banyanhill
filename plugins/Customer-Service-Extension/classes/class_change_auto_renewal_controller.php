<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');

/**
 * Class: class_change_auto_renewal_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_change_auto_renewal_controller
{

    /**
     * @var class_display
     */
    protected $display_class;

    /**
     * @var class_middleware
     */
    protected $middleware;

    /**
     * @var wpdb
     */
    private $wpdb;

    /**
     *  Constructor Method.
     *
     * @method __construct
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->display_class = new class_display();
        $this->middleware = class_middleware::get_instance();

        $this->wp_actions();
    }

    /**
     *  Set up ajax callback functions as wordpress actions
     *
     * @method wp_actions
     */
    private function wp_actions() {
        add_action( 'wp_ajax_csd_ext_change_auto_renew',
            array( $this, 'csd_ext_change_auto_renew' ) );
        add_action( 'wp_ajax_csd_ext_change_auto_renew_confirm',
            array( $this, 'csd_ext_change_auto_renew_confirm' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_auto_renew',
            array( $this, 'csd_ext_change_auto_renew' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_auto_renew_confirm',
            array( $this, 'csd_ext_change_auto_renew_confirm' ) );
        add_action( 'wp_ajax_csd_ext_auto_renew_remind',
            array( $this, 'csd_ext_auto_renew_remind' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_auto_renew_remind',
            array( $this, 'csd_ext_auto_renew_remind' ) );
    }

    /**
     * function to render first view for auto renew flow
     *
     * @method csd_ext_change_auto_renew
     *
     */
    public function csd_ext_change_auto_renew () {
        $return = array();
        $content = array();
        if ( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if (!empty($data['auto_status']) && !empty($data['subref'])) {
                $auto_status = sanitize_text_field($data['auto_status']);
                $subref = sanitize_text_field($data['subref']);
                $content['auto_status'] = $auto_status;
                $content['subref'] = $subref;
                if (!empty($data['subname'])) {
                    $content['subname'] = sanitize_text_field($data['subname']);
                }
                if (!empty($data['expire'])) {
                    $content['expire'] = sanitize_text_field($data['expire']);
                }
                $return['html'] = $this->display_class->display_frontend('change_auto_renew/change_auto_renew', $content);
                die(json_encode($return));
            }
        }
        $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
        $return['html'] = $this->display_class->display_frontend('error', $content);
        die( json_encode($return) );
    }


    /**
     * function to process change auto renew request
     *
     * @method csd_ext_change_auto_renew_confirm
     *
     */
    public function csd_ext_change_auto_renew_confirm () {
        $return = array();
        $content = array();
        if ( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if ( !empty($data['auto_status']) && !empty($data['subref'] ) ) {
                $auto_status = sanitize_text_field( $data['auto_status'] );
                $subref = sanitize_text_field( $data['subref'] );
                if ( strtolower( $auto_status ) === 'on' ) {
                    $toggle_auto_renew = $this->middleware->core->mw->update_subscription_auto_renew_flag( $subref, 'D');
                    if ( !is_wp_error( $toggle_auto_renew ) ) {
                        $return['auto_renew_change'] = 'Off';
                        $return['sub_ref'] = $subref;
                        $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_toggle_auto_renew_success');
                        $return['html'] = $this->display_class->display_frontend('success', $content);
                        die( json_encode($return) );
                    }
                } elseif ( strtolower( $auto_status ) === 'off' ) {
                    $subref = sanitize_text_field( $data['subref'] );
                    $subname = sanitize_text_field( $data['subname'] );
                    $expire = sanitize_text_field( $data['expire'] );
                    $content = array();
                    $toggle_auto_renew = $this->middleware->core->mw->update_subscription_auto_renew_flag( $subref, 'C');
                    if ( !is_wp_error( $toggle_auto_renew ) ) {
                        $return['sub_ref'] = $subref;
                        $content['sub_ref'] = $subref;
                        $content['sub_name'] = $subname;
                        $content['expire'] = $expire;
                        $return['auto_renew_change'] = 'On';
                        $return['html'] = $this->display_class->display_frontend('change_auto_renew/reminder_optin', $content);
                        die( json_encode($return) );
                    }
                }
            }
        }

        $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
        $return['html'] = $this->display_class->display_frontend('error', $content);
        die( json_encode($return) );
    }

    /**
     * function to process enable auto renew request
     *
     * @method csd_ext_auto_renew_remind
     *
     */
    public function csd_ext_auto_renew_remind( ) {
        $content = array();
        $return = array();

        $customer_number = $this->middleware->core->user->get_customer_number();

        if ( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if( !empty( $data['expire'] ) ) {
                $expire = sanitize_text_field( $data['expire'] );
                $expire = strtotime( $expire );
            }
            if( !empty( $data['sub_name'] ) ) {
                $subname = sanitize_text_field( $data['sub_name'] );
            }
            if( !empty( $data['sub_ref'] ) ) {
                $subref = sanitize_text_field( $data['sub_ref'] );
            }

            $table_name = $this->wpdb->prefix . 'csd_ext_auto_renewals';
            $insert = $this->wpdb->insert(
                $table_name,
                array(
                    'customerNumber' =>  $customer_number,
                    'subName' => $subname,
                    'subRef' => $subref,
                    'expireDate' => $expire,
                    'reminderSent' => 'false'
                )
            );
            if ( !empty( $insert ) ) {
                $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_auto_renew_confirm_on');
                $return['html'] = $this->display_class->display_frontend('success', $content);
                die( json_encode( $return ) );
            }
        }


        $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
        $return['html'] = $this->display_class->display_frontend('error', $content);
        die( json_encode( $return ) );
    }
}
$change_auto_renewal_controller = new class_change_auto_renewal_controller;