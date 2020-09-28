<?php
/**
 * Extends eForm functionality to include elements in format strings
 *
 * This is a singleton class
 */
class EForm_EasySub_Format_Strings {
	private static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		// Add to the settings variables
		add_filter( 'ipt_fsqm_filter_default_settings', array( $this, 'modify_form_settings' ), 10, 2 );

		// Add to the admin settings window
		add_action( 'ipt_fsqm_admin_format_options', array( $this, 'admin_settings' ), 10, 2 );

		// Add to the format string filter
		add_filter( 'ipt_fsqm_format_strings', array( $this, 'modify_format_string' ), 10, 4 );
	}

	public function modify_format_string( $format_string_components, $that, $form_id, $data_id ) {
		// Check the settings
		if ( ! isset( $that->settings['format']['extended'] ) || false == $that->settings['format']['extended'] ) {
			return $format_string_components;
		}
		// We have some, so let's do it
		$value_api = new IPT_eForm_Form_Elements_Values( $data_id );
		$value_api->set_option_delimiter( $that->settings['format']['odelimiter'] );
		$value_api->set_row_delimiter( $that->settings['format']['rdelimiter'] );
		$value_api->set_range_delimiter( $that->settings['format']['radelimiter'] );
		$value_api->set_entry_delimiter( $that->settings['format']['edelimiter'] );

		$variable_parts = array();
		$added_elements = array(
			'mcq' => array(),
			'freetype' => array(),
			'pinfo' => array(),
		);

		if ( preg_match_all( '/([M|F|O])([0-9]+)/', $that->settings['format']['elements'], $variable_parts ) ) {
			foreach ( $variable_parts[0] as $key => $part ) {
				$m_type = 'mcq';
				if ( 'F' == $variable_parts[1][ $key ] ) {
					$m_type = 'freetype';
				} elseif ( 'O' == $variable_parts[1][ $key ] ) {
					$m_type = 'pinfo';
				}
				$ekey = (int) $variable_parts[2][ $key ];

				// Check for duplicates
				if ( in_array( $ekey, $added_elements[ $m_type ] ) ) {
					continue;
				}

				// Get element settings
				$element = $that->get_element_from_layout( array(
					'm_type' => $m_type,
					'key' => $ekey,
				) );

				$added_elements[ $m_type ][] = $ekey;

				$data_type = 'label';

				if ( 'upload' == $element['type'] ) {
					$data_type = 'html';
				}

				// Add to the item data
				if ( ! empty( $element ) ) {
					$format_string_components[ '%' . $variable_parts[1][ $key ] . $ekey . '%' ] = $value_api->get_value( $m_type, $ekey, 'string', $data_type );
				}
			}
		} elseif ( 'all' == $that->settings['format']['elements'] ) {
			// Loop through all mcq
			foreach ( array( 'mcq' => 'M', 'freetype' => 'F', 'pinfo' => 'O' ) as $m_type => $formatter ) {
				foreach ( array_keys( $that->{$m_type} ) as $e_key ) {
					$data_type = 'label';
					if ( 'upload' == $that->{$m_type}[ $e_key ]['type'] ) {
						$data_type = 'html';
					}
					$format_string_components[ '%' . $formatter . $e_key . '%' ] = $value_api->get_value( $m_type, $e_key, 'string', $data_type );
				}
			}
		}

		return $format_string_components;
	}

	public function modify_form_settings( $settings, $form_id ) {
		$settings['format']['extended'] = false;
		$settings['format']['elements'] = 'all';
		$settings['format']['odelimiter'] = ', ';
		$settings['format']['rdelimiter'] = '<br />';
		$settings['format']['radelimiter'] = '/';
		$settings['format']['edelimiter'] = ':';
		return $settings;
	}

	public function admin_settings( $that, $op ) {
		?>
		<tr>
			<th><?php $that->ui->generate_label( 'settings[format][extended]', __( 'Add Format Strings Based on Form Elements', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->toggle( 'settings[format][extended]', __( 'Yes', 'eform-es' ), __( 'No', 'eform-es' ), $op['extended'], '1', false, true, array(
					'condid' => 'ipt_fsqm_format_elements_wrap,ipt_fsqm_format_elements_odelimiter_wrap,ipt_fsqm_format_elements_rdelimiter_wrap,ipt_fsqm_format_elements_radelimiter_wrap,ipt_fsqm_format_elements_edelimiter_wrap'
				) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'If enabled, then it will convert your mentioned form elements into format strings. When you mention elements <code>M0,F4,O2</code> the available format strings would be <code>%M0%, %F4%, %O2%</code>.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<tr id="ipt_fsqm_format_elements_wrap">
			<th><?php $that->ui->generate_label( 'settings[format][elements]', __( 'Form Elements to Convert into Format Strings', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->text( 'settings[format][elements]', $op['elements'], __( 'M0,F1,F2,O1,M13', 'eform-es' ) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'Mention form elements to convert into format strings. When you mention elements <code>M0,F4,O2</code> the available format strings would be <code>%M0%, %F4%, %O2%</code>. If you mention <code>all</code> then all elements will be converted into format strings.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<tr id="ipt_fsqm_format_elements_odelimiter_wrap">
			<th><?php $that->ui->generate_label( 'settings[format][odelimiter]', __( 'Option Delimiter', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->text( 'settings[format][odelimiter]', $op['odelimiter'], __( ', ', 'eform-es' ) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'The delimiter with which multiple options will be concatenated.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<tr id="ipt_fsqm_format_elements_rdelimiter_wrap">
			<th><?php $that->ui->generate_label( 'settings[format][rdelimiter]', __( 'Row Delimiter', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->text( 'settings[format][rdelimiter]', $op['rdelimiter'], __( '<br />', 'eform-es' ) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'The delimiter with which multiple rows will be concatenated.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<tr id="ipt_fsqm_format_elements_radelimiter_wrap">
			<th><?php $that->ui->generate_label( 'settings[format][radelimiter]', __( 'Range Delimiter', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->text( 'settings[format][radelimiter]', $op['radelimiter'], __( '/', 'eform-es' ) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'The delimiter with which range values will be concatenated.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<tr id="ipt_fsqm_format_elements_edelimiter_wrap">
			<th><?php $that->ui->generate_label( 'settings[format][edelimiter]', __( 'Entry Delimiter', 'eform-es' ) ); ?></th>
			<td>
				<?php $that->ui->text( 'settings[format][edelimiter]', $op['edelimiter'], __( ':', 'eform-es' ) ); ?>
			</td>
			<td>
				<?php $that->ui->help( __( 'The delimiter with which multiple entries will be concatenated.', 'eform-es' ) ); ?>
			</td>
		</tr>
		<?php
	}
}
