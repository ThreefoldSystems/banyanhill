<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');
include_once('class_middleware.php');

/**
 * Class: class_change_email_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_change_email_controller
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
     * Constructor method
     *
     * @method __construct
     *
     */
    public function __construct ( ) {
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
        add_action( 'wp_ajax_csd_ext_change_email_address',
            array( $this, 'csd_ext_change_email_address' ) );
        add_action( 'wp_ajax_csd_ext_change_email_address_confirm',
            array( $this, 'csd_ext_change_email_address_confirm' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_email_address',
            array( $this, 'csd_ext_change_email_address' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_email_address_confirm',
            array( $this, 'csd_ext_change_email_address_confirm' ) );
    }
    /**
     * function to render first view for change email flow
     *
     * @method csd_ext_change_email_address
     *
     */
    public function csd_ext_change_email_address () {
        $content = array();
        $return = array();

        if( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if ( !empty( $data['sub_ref'] ) && !empty( $data['old_email']) ) {
                $sub_ref = sanitize_text_field($data['sub_ref']);
                $customer_number = $this->middleware->core->user->get_customer_number();
                $recent_changed_time = get_transient( $sub_ref . '_changed_' . $customer_number );
                if ( !empty( $recent_changed_time ) ) {
                    $content['message'] = $this->remaining_time( $recent_changed_time );
                    $return['html'] = $this->display_class->display_frontend('change_email/time_remaining',
                       $content);
                    die( json_encode($return) );
                }

                $content['sub_ref'] = $sub_ref;
                $content['old_email'] = $data['old_email'];
                $return['html'] = $this->display_class->display_frontend('change_email/change_email', $content);
                die(json_encode($return));
            }
        }
        $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
        $return['html'] = $this->display_class->display_frontend('error', $content);
        die( json_encode($return) );
    }

    /**
     * function to process change email address request
     *
     * @method csd_ext_change_email_address_confirm
     *
     */
    public function csd_ext_change_email_address_confirm () {
        $return = array();
        $content = array();

        if ( empty( $_POST['data'] ) ) {
            $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
            $return['html'] = $this->display_class->display_frontend('error', $content);
            die( json_encode($return) );
        }

        $data = $_POST['data'];
        if ( !empty( $data['new_email']) && !empty( $data['new_email_repeat'] ) && !empty( $data['sub_ref'] ) ) {
            $sub_ref = sanitize_text_field($data['sub_ref']);
            $new_email = sanitize_text_field($data['new_email']);
            $new_email_repeat = sanitize_text_field($data['new_email_repeat']);

            if( is_email( $new_email ) && strtolower( $new_email ) === strtolower( $new_email_repeat ) )   {
                $update_email = $this->middleware->core->mw->updateSubscriptionEmailAddress( $sub_ref, $new_email );

                if ( !is_wp_error($update_email) ) {
                    $customer_number = $this->middleware->core->user->get_customer_number();
                    set_transient( $sub_ref . '_changed_' . $customer_number, current_time('H:i:s'), 15 * 60 );
                    $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_email_success');
                    $return['html'] = $this->display_class->display_frontend('success', $content);
                    $return['email_address'] = $new_email;
                    $return['sub_ref'] = $sub_ref;
                    die( json_encode($return) );
                }

            }
        } else {
            $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
            $return['html'] = $this->display_class->display_frontend('error', $content);
            die( json_encode($return) );
        }
    }

    /**
     *  Get remaining time
     */
    public function remaining_time( $changed_time )
    {
        $remaining_time_in_minutes = 15 - round( abs( strtotime( current_time('H:i:s')) -
                    strtotime( $changed_time ) ) / 60 );
        $remaining_time_in_minutes = $remaining_time_in_minutes == 0 ? '1' : $remaining_time_in_minutes;
        $minute = $remaining_time_in_minutes == 1 ? 'minute' : 'minutes';
        $remaining_time_in_minutes = $remaining_time_in_minutes . ' ' . $minute;

        return $this->middleware->core->get_language_variable('txt_csd_ext_changed_recently',
            array( 'time' => $remaining_time_in_minutes ) );
    }
}
$change_email_controller = new class_change_email_controller;