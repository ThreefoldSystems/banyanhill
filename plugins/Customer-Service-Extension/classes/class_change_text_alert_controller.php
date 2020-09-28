<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');
include_once('class_middleware.php');

/**
 * Class: class_change_text_alert_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_change_text_alert_controller
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
        add_action( 'wp_ajax_csd_ext_change_text_alert',
            array( $this, 'csd_ext_change_text_alert' ) );
        add_action( 'wp_ajax_csd_ext_change_text_alert_confirm',
            array( $this, 'csd_ext_change_text_alert_confirm' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_text_alert',
            array( $this, 'csd_ext_change_text_alert' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_text_alert_confirm',
            array( $this, 'csd_ext_change_text_alert_confirm' ) );
    }
    /**
     * function to render first view for change phone number flow
     *
     * @method csd_ext_change_text_alert
     *
     */
    public function csd_ext_change_text_alert () {
        $return = array();
        $content = array ();

        if ( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if ( !empty( $data['phone'] ) ) {
                $content['phone'] = $data['phone'];
            }
            if ( !empty( $data['addr_code'] ) ) {
                $content['addr_code'] = $data['addr_code'];
            }
            if ( !empty( $data['sub_ref'] ) ) {
                $content['sub_ref'] = $data['sub_ref'];
            }

        }
        $return['html'] = $this->display_class->display_frontend('change_text_alert/change_text_alert', $content);
        die( json_encode($return) );
    }

    /**
     * function to process change phone number request
     *
     * @method csd_ext_change_text_alert_confirm
     *
     */
    public function csd_ext_change_text_alert_confirm () {
        $return = array();
        $content = array();

        if ( empty( $_POST['data'] ) ) {
            $return['status'] = 'Missing post data';
            $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
            $return['html'] = $this->display_class->display_frontend('error', $content);
            die( json_encode($return) );
        }

        $data = $_POST['data'];
        if ( !empty($data['new_phone']) &&  !empty($data['new_phone_repeat'])
            && $data['new_phone'] === $data['new_phone_repeat'] ) {

            if ( empty($data['addr_code']) ) {
                $data['addr_code'] = 'ADDR-01';
            }

            $customer_number = $this->middleware->core->user->get_customer_number();
            $payload = array( 'customerNumber' => $customer_number,
                'phoneNumber' => $data['new_phone'],
                'addressCode' => $data['addr_code']
                );

            $update_user = $this->middleware->core->mw->put_update_postal_address( $payload );

            if ( !is_wp_error( $update_user ) ) {
                //update local user object
                $this->update_user( $data['new_phone'] );
                $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_text_alert_success');
                $return['html'] = $this->display_class->display_frontend('success', $content);
                $return['phone'] = $data['new_phone'];
                die( json_encode($return) );
            } else {
                // If error came back from Middleware, display it
                if ( !empty( $update_user->error_data['post_request_failed']['body'] ) ) {
                    if ( $update_user->error_data['post_request_failed']['response']['code'] !== 500 ) {
                        $return['message'] = $update_user->error_data['post_request_failed']['body'];
                        $return['status'] = 'Error in Middleware';
                    } else {
                        $return['status'] = 'Advantage error';
                        $return['error'] = $update_user->error_data;
                    }
                } else {
                    $return['error'] = json_encode($update_user);
                }
            }
        }
        if ( empty( $content['message'] ) ) {
            $content['message'] = $this->middleware->core->get_language_variable('txt_csd_ext_general_error');
        }
        if ( empty( $return['status'] ) ) {
            $return['status'] = 'General Error';
        }
        $return['html'] = $this->display_class->display_frontend('error', $content);
        die( json_encode($return) );
    }

    /**
     * Updates the local middleware user object in the WordPress installation
     *
     * @param $phone_number
     */
    public function update_user( $phone_number ) {
        $this->middleware->core->user->wp_user->data->middleware_data->postalAddresses[0]->phoneNumber = $phone_number;

        //Save middleware data (local)
        $this->middleware->core->user->save_middleware_data( get_current_user_id() );
    }

}
$change_text_alert_controller = new class_change_text_alert_controller;