<?php
/*
Plugin Name: eForm easySubmission Add-on
Description: Provides a bunch of functionality to handle submissions effectively
Plugin URI: https://eform.live
Author: WPQuark
Author URI: https://wpquark.com
Version: 1.1.0
License: GPL3
Text Domain: eform-es
Domain Path: /translations
*/

/*

    Copyright (C) 2017 Swashata Ghosh ( WPQuark ) swashata@wpquark.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Autoloaders
class EForm_EasySub_AutoLoader {
	public static function load_classes( $name ) {
		$path = trailingslashit( dirname( __FILE__ ) ) . 'classes/';
		$filename = 'class-' . str_replace( '_', '-', strtolower( $name ) ) . '.php';
		if ( file_exists( $path . $filename ) ) {
			require_once $path . $filename;
		}
	}
}
spl_autoload_register( 'EForm_EasySub_AutoLoader::load_classes' );

global $eform_easysub_op;
$eform_easysub_op = get_option( 'eform_easysub_op' );

$eform_easysub = new EForm_EasySub_Loader( __FILE__, '1.1.0' );
$eform_easysub->load();
