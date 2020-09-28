<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

include_once('class_display.php');
include_once('class_middleware.php');

/**
 * Class: class_lifetime_controller
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_lifetime_controller
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
        add_action( 'wp_ajax_csd_ext_lifetime', array( $this, 'csd_ext_lifetime' ) );
        add_action( 'wp_ajax_nopriv_csd_ext_lifetime', array( $this, 'csd_ext_lifetime' ) );
    }

    /**
     * function to render lifetime modal view
     *
     * @method csd_ext_lifetime
     *
     */
    public function csd_ext_lifetime () {
        $return = array();

        $return['html'] = $this->display_class->display_frontend('lifetime/lifetime');
        die(json_encode($return));
    }
}
$lifetime_controller = new class_lifetime_controller;