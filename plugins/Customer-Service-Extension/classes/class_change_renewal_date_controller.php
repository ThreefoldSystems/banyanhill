<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');

/**
 * Class: class_change_renewal_date_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_change_renewal_date_controller
{

    /**
     * @var class_display
     */
    protected $display_class;

    /**
     * Constructor method
     *
     * @method __construct
     *
     */
    public function __construct ( ) {
        $this->display_class = new class_display();

        $this->wp_actions();
    }

    /**
     *  Set up ajax callback functions as wordpress actions
     *
     * @method wp_actions
     */
    private function wp_actions() {
        add_action( 'wp_ajax_csd_ext_change_renewal_date',
            array( $this, 'csd_ext_change_renewal_date' ) );
        add_action( 'wp_ajax_csd_ext_change_renewal_date_confirm',
            array( $this, 'csd_ext_change_renewal_date_confirm' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_renewal_date',
            array( $this, 'csd_ext_change_renewal_date' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_renewal_date_confirm',
            array( $this, 'csd_ext_change_renewal_date_confirm' ) );
    }

    /**
     * function to render first view for change renewal date flow
     *
     * @method csd_ext_change_renewal_date
     *
     */
    public function csd_ext_change_renewal_date () {
        $return = array();
        $content = array();
        if( !empty( $_POST ) && !empty( $_POST['data']['url'] ) ) {
            $content['url'] = $_POST['data']['url'];
        }
		
        if( !empty( $_POST ) && !empty( $_POST['data']['savings'] ) ) {
            $content['savings'] = $_POST['data']['savings'];
        }		
		
        $return['html'] = $this->display_class->display_frontend('change_renewal_date/change_renewal_date', $content);
        die( json_encode($return) );
    }
}
$change_renewal_date_controller = new class_change_renewal_date_controller;