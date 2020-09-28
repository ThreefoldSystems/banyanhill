<?php
/**
 * Copyright (C) Threefold systems - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 */
namespace csd_ext;

/**
 * Class: class_display
 * Description:
 * Version:
 * @author: Threefold Systems
 */
class class_display
{
    /**
     * Constructor method
     *
     * @method constructor
     *
     */
    public function __construct ( ) {

    }

    /**
     * Function to render templates
     *
     * @method display_frontend
     *
     * @param  string $template The name of the template file you want to load. Without the .php extension
     * @param  array $content Content to be passed to the rendered template
     * @param  boolean $return wheteher we need to echo the template directly, or just return its contents
     * @return string
     */
    public function display_frontend ( $template = null, $content = null, $return = null ) {

        if ( is_array($content) ) {
            extract($content);
        }

        if ( empty( $template ) ) {
            $template = 'primary_view';
        }

        try {
            $path = $this->_get_template($template);

            // Check to see if we need to echo the template directly, or just return its contents
            if($return === false){
                include($path);
            }else{
                ob_start();
                include($path);
                return ob_get_clean();
            }
        } catch (Exception $e) {
            echo __('Unable to Load Template ') . $e->getMessage() .'\n';
        }
    }

    /**
     * Helper function to return the template path.
     *
     * @method _get_template
     *
     * @param  string $template_name The name of the template file you want to load. Without the .php extension
     * @return string The full path to the template file. Returns false if no matching file was found.
     */
    public function _get_template($template_name){
        if( file_exists( dirname( __FILE__ ) . '/../views/' . $template_name .'.php' ) ) {
            return dirname( __FILE__ ) . '/../views/' . $template_name .'.php';
        }
        return false;
    }

}