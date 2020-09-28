<?php
/**
 * Class to handle direct submission editing and related functionality
 *
 * This is a singleton class
 */
class EForm_EasySub_Direct_Submission {

	private static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'ipt_fsqm_sutb', array( $this, 'shortcode_cb' ) );
	}

	public function shortcode_cb( $atts ) {
		$atts = shortcode_atts( array(
			'id' => '',
			'show_login' => '1',
			'block_for_non_logged' => '0',
			'msg' => __( 'Please login to edit your previous submission', 'ipt_fsqm_de' ),
			'referer' => '0',
		), $atts, 'ipt_fsqm_sutb' );
		ob_start();

		// Just show the form if the logged in user isn't present
		if ( ! is_user_logged_in() ) {
			// 1. Show the form
			$this->get_main_form( $atts['id'], $atts['msg'], $atts['show_login'], $atts['block_for_non_logged'] );
		} else {
			// 1. Get the submission id
			// 2. Show the edit form
			$this->get_form_for_logged_in( $atts['id'], $atts['referer'] );
		}

		return ob_get_clean();
	}

	public function get_form_for_logged_in( $id, $referer = '0' ) {
		global $ipt_fsqm_info, $ipt_fsqm_settings, $wpdb;
		$user_id = get_current_user_id();
		if ( '1' == $referer ) {
			$current_page = ( isset( $_SERVER['HTTPS'] ) ? "https" : "http" ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$data_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$ipt_fsqm_info['data_table']} WHERE user_id = %d AND form_id = %d AND referer = %s ORDER BY id DESC LIMIT 0,1", $user_id, $id, $current_page ) ); // WPCS: unprepared SQL ok
		} else {
			$data_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$ipt_fsqm_info['data_table']} WHERE user_id = %d AND form_id = %d ORDER BY id DESC LIMIT 0,1", $user_id, $id ) ); // WPCS: unprepared SQL ok
		}
		if ( null == $data_id ) {
			$this->get_main_form( $id );
			return;
		}
		IPT_FSQM_Form_Elements_Static::ipt_fsqm_form_edit( $data_id );
	}

	public function get_main_form( $id, $msg = '', $show_login = '0', $block_for_non_logged = '0' ) {
		if ( $show_login == '1' && ! is_user_logged_in() ) {
			$form = new IPT_FSQM_Form_Elements_Front( null, $id );
			$form->container( array( array( $form->ui, 'msg_error' ), array( $form->print_login_message(), true, $msg, false ) ), true );
			if ( $block_for_non_logged == '1' ) {
				return;
			}
		}
		echo IPT_FSQM_Form_Elements_Static::ipt_fsqm_form_cb( array(
			'id' => $id,
		) );
	}
}
