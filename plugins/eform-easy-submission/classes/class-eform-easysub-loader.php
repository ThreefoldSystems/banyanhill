<?php

class EForm_EasySub_Loader {
	/**
	 *
	 *
	 * @staticvar string
	 * Holds the absolute path of the main plugin file directory
	 */
	static $abs_path;

	/**
	 *
	 *
	 * @staticvar string
	 * Holds the absolute path of the main plugin file
	 */
	static $abs_file;

	/**
	 *
	 *
	 * @staticvar string
	 * The current version of the plugin
	 */
	static $version;

	public function __construct( $file_loc, $version = '1.0.0' ) {
		self::$abs_path = dirname( $file_loc );
		self::$abs_file = $file_loc;
		self::$version = $version;
		global $eform_easysub_op;
		$eform_easysub_op = get_option( 'eform_easysub_op' );
	}

	public function load() {
		// activation hook
		register_activation_hook( self::$abs_file, array( $this, 'plugin_install' ) );
		// deactivation hook
		register_deactivation_hook( self::$abs_file, array( $this, 'plugin_deactivate' ) );
		// Load Text Domain For Translations //
		add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );

		// Add our shortcode generators
		add_action( 'ipt_fsqm_tmce_extendor_script', array( $this, 'tmce_shortcode_extender' ) );

		// Init the shortcode classes
		EForm_EasySub_Direct_Submission::get_instance();
		// This is incomplete
		// EForm_EasySUb_List_Subs::setup();

		// Init Format String
		EForm_EasySub_Format_Strings::get_instance();
	}

	public function tmce_shortcode_extender() {
		wp_enqueue_script( 'easy-sub-shortcode', plugins_url( '/static/admin/js/eform-easysub-tinymce-plugin.min.js', self::$abs_file ), array( 'jquery' ), self::$version );
		wp_localize_script( 'easy-sub-shortcode', 'eFormEasySubShortcode', array(
			'l10n' => array(
				'dst' => __( 'Direct Submission Edit', 'eform-es' ),
				'slf' => __( 'Show Login Form', 'eform-es' ),
				'dsnf' => __( 'Do not show the new form for non logged in users', 'eform-es' ),
				'dsnftt' => __( 'Requires the Show Login Form to be checked', 'eform-es' ),
				'dsrf' => __( 'Edit Form for Current URL', 'eform-es' ),
				'dsrftt' => __( 'If you want to show the edit form based on current URL, then enable this. eForm stores referal data during submission. So you can publish same form on multiple URLs and for each URL the corresponding submission data will be shown.', 'eform-es' ),
				'lgmsg' => __( 'Login Message', 'eform-es' ),
				'lgmsgdf' => __( 'Please Login to edit your submission', 'eform-es' ),
			),
		) );
	}

	public function plugin_install() {
		// Check if eForm is present
		if ( ! class_exists( 'IPT_FSQM_Loader' ) ) {
			deactivate_plugins( plugin_basename( self::$abs_file ) );
			wp_die( __( 'The add-on requires eForm - WordPress Form Builder plugin to work with.', 'eform-es' ), __( 'Error while activating add-on', 'eform-es' ), array(
				'back_link' => true,
			) );
			return;
		}
		if ( version_compare( IPT_FSQM_Loader::$version, '3.7.1', '<' ) ) {
			deactivate_plugins( plugin_basename( self::$abs_file ) );
			wp_die( __( 'This add-on requires eForm - WordPress Form Builder version 3.7.1 or greater to work.', 'eform-es' ), __( 'Error while activating add-on', 'eform-es' ), array(
				'back_link' => true,
			) );
			return;
		}
		// Add the latest version information
		$default_info = array(
			'version' => self::$version,
		);
		// Get the rest
		$existing_info = get_option( 'eform_easysub_op', array() );
		// Merge
		if ( ! empty( $existing_info ) ) {
			$existing_info['version'] = $default_info['version'];
		} else {
			$existing_info = $default_info;
		}
		update_option( 'eform_easysub_op', $existing_info );
		global $eform_easysub_op;
		$eform_easysub_op = get_option( 'eform_easysub_op' );
	}

	public function plugin_deactivate() {
		// Nothing to do here too
	}

	public function plugin_textdomain() {
		load_plugin_textdomain( 'eform-es', false, basename( dirname( self::$abs_file ) ) . '/translations' );
	}
}
