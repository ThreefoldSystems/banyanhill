<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');

/**
 * Class: class_change_renewal_price_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_change_renewal_price_controller
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
        add_action( 'wp_ajax_csd_ext_change_renewal_price',
            array( $this, 'csd_ext_change_renewal_price' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_change_renewal_price',
            array( $this, 'csd_ext_change_renewal_price' ) );
    }

    /**
     * function to render first view for renewal price flow
     *
     * @method csd_ext_change_renewal_price
     *
     */
    public function csd_ext_change_renewal_price () {
        $return = array();
        $content = array();
        if( !empty( $_POST['data'] ) ) {
            $data = $_POST['data'];
            if( !empty($data['url']) ) {
                $content['url'] = $data['url'];
            }
            if( !empty($data['rate']) ) {
                $content['rate'] = $data['rate'];
            }
            if( !empty($data['price']) ) {
                $content['price'] = $data['price'];
            }

        }
        $return['html'] = $this->display_class->display_frontend('change_renewal_price/change_renewal_price', $content);
        die( json_encode($return) );
    }

}
$change_renewal_price_controller = new class_change_renewal_price_controller;