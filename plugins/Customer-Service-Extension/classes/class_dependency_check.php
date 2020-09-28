<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

/**
 * Class: class_dependency_check
 * Description:
 * Version:
 * @author: Threefold Systems
*/
class class_dependency_check
{

    /**
     * @array $dependencies
     */
    private $dependencies = array();

    /**
     * Constructor method
     *
     * @method __construct
     *
     * @param array $dependencies
     */
    public function __construct($dependencies = array()) {

        $this->dependencies = $dependencies;

    }

    /**
     * Check for the existence of required classes
     *
     * @method check_dependencies
     *
     * @return boolean
     */
    public function check_dependencies() {

        if ( !empty( $this->dependencies ) ) {
            foreach ( $this->dependencies as $dependency ) {
                if ( !class_exists( $dependency ) ) {
                    include_once( ABSPATH . '/wp-admin/includes/plugin.php' );

                    add_action('all_admin_notices', array( $this, 'dependency_failed_error' ) );

                    return false;
                }
            }

        }

        return true;

    }

    /**
     * Action callback for all_admin_notices
     *
     * @method dependency_failed_error
     *
     * @return string
     */
    public function dependency_failed_error () {

        echo '<div id="message" class="error"><p>';
        echo __('This plugin has failed the dependency check');
        echo '</p></div>';

    }

}