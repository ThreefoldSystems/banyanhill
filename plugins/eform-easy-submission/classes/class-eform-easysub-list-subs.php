<?php
/**
 * Lists Submissions of a form
 *
 * In a safe way
 */
class EForm_EasySUb_List_Subs {
	private $form_id = null;
	private $limit = 100;
	private $orderby = 'date';
	private $order = 'desc';
	private $value = null;
	private $columns = array();

	public static function setup() {
		// Add the TinyMCE generator

		// TinyMCE ajax helper

		// Add shortcode
		add_shortcode( 'ipt_eform_subs', array( 'EForm_EasySub_List_Subs', 'shortcode_cb' ) );
	}

	public static function shortcode_cb( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'form_id' => '0',
			'mcq' => 'all',
			'freetype' => 'all',
			'pinfo' => '',
			'limit' => '100',
			'orderby' => 'date',
			'order' => 'desc',
			'image' => '1',
			'heading' => '1',
		), $atts, 'ipt_eform_subs' );
		$columns = array(
			'mcq' => array(),
			'freetype' => array(),
			'pinfo' => array(),
		);
		$form = new IPT_FSQM_Form_Elements_Base( $atts['form_id'] );
		foreach ( array( 'mcq', 'freetype', 'pinfo' ) as $m_type ) {
			if ( 'all' == $atts[ $m_type ] ) {
				$columns[ $m_type ] = array_keys( $form->{$m_type} );
			} else {
				$columns[ $m_type ] = wp_parse_id_list( $atts[ $m_type ] );
			}
		}
		$appearance = array(
			'image' => ( '1' == $atts['image'] ? true : false ),
			'heading' => ( '1' == $atts['heading'] ? true : false ),
		);
		$subs = new self( $atts['form_id'], $columns, $atts['limit'], $atts['orderby'], $atts['order'] );
		ob_start();
		$subs->show_list( $appearance, $content );
		return ob_get_clean();
	}

	public function __construct( $form_id, $columns = array(), $limit = 100, $orderby = 'date', $order = 'desc' ) {
		$this->form_id = $form_id;
		$this->limit = absint( $limit );
		$orderby = strtolower( $orderby );
		if ( ! in_array( $orderby, array( 'f_name', 'l_name', 'score', 'date', 'time' ) ) ) {
			$orderby = 'date';
		}
		$this->orderby = $orderby;
		if ( 'desc' != strtolower( $order ) ) {
			$this->order = 'asc';
		}
		$this->value = new IPT_eForm_Form_Elements_Values( null, $this->form_id );
		$this->value->set_option_delimiter( '<br />' );
		$this->value->set_row_delimiter( '<br /><br />' );
		$columns = wp_parse_args( $columns, array(
			'mcq' => array(),
			'freetype' => array(),
			'pinfo' => array(),
		) );
		$this->columns = $columns;
	}

	/**
	 * Enqueues scripts and styles.
	 *
	 * Either hook it up in wp_enqueue_scripts or call directly but not before
	 * the mentioned hook.
	 */
	public function enqueue( $theme ) {
		// Get UI
		if ( isset( $theme['ui-class'] ) && class_exists( $theme['ui-class'] ) ) {
			$ui = $theme['ui-class']::instance( 'eform-es' );
		} else {
			$ui = IPT_Plugin_UIF_Front::instance( 'eform-es' );
		}
		// Also enqueue the UI stuff
		$ui->enqueue( plugins_url( '/lib/', IPT_FSQM_Loader::$abs_file ), IPT_FSQM_Loader::$version );
		// Start buffering to get the loader HTML
		ob_start();
		$ui->ajax_loader( false, '', array(), true, __( 'Please wait', 'eform-es' ) );
		$ajax_loader = ob_get_clean();

		// Enqueue the datatable
		wp_enqueue_style( 'ipt-fsqm-up-yadcf-css', plugins_url( '/lib/css/jquery.dataTables.yadcf.css', IPT_FSQM_Loader::$abs_file ), array(), IPT_FSQM_Loader::$version, 'all' );
		wp_enqueue_script( 'ipt-fsqm-up-datatable', plugins_url( '/lib/js/jquery.dataTables.min.js', IPT_FSQM_Loader::$abs_file ), array( 'jquery' ), IPT_FSQM_Loader::$version );
		wp_enqueue_script( 'ipt-fsqm-up-datatable-yadcf', plugins_url( '/lib/js/jquery.dataTables.yadcf.js', IPT_FSQM_Loader::$abs_file ), array( 'ipt-fsqm-up-datatable' ), IPT_FSQM_Loader::$version );

		// Main CSS
		if ( isset( $theme['leaderboard-css'] ) && ! empty( $theme['leaderboard-css'] ) ) {
			wp_enqueue_style( 'ipt-eform-lb-css', $theme['leaderboard-css'], array(), IPT_FSQM_Loader::$version );
		} else {
			wp_enqueue_style( 'ipt-eform-lb-css', plugins_url( '/static/front/css/ipt-eform-lb.css', IPT_FSQM_Loader::$abs_file ), array(), IPT_FSQM_Loader::$version );
		}

		// Main JS
		wp_enqueue_script( 'ipt-eform-lb-js', plugins_url( '/static/front/js/jquery.ipt-eform-lb.min.js', IPT_FSQM_Loader::$abs_file ), array( 'jquery', 'ipt-plugin-uif-front-js' ), IPT_FSQM_Loader::$version );
		wp_localize_script( 'ipt-eform-lb-js', 'ipteFormLB', array(
			'css' => 'ipt-eform-lb-css-css',
			'cssl' => plugins_url( '/static/front/css/ipt-eform-lb.css', IPT_FSQM_Loader::$abs_file ),
			'l10n' => array(
				'sEmptyTable' => __( 'No submissions yet!', 'eform-es' ),
				'sInfo' => __( 'Showing _START_ to _END_ of _TOTAL_ entries', 'eform-es' ),
				'sInfoEmpty' => __( 'Showing 0 to 0 of 0 entries', 'eform-es' ),
				'sInfoFiltered' => __( '(filtered from _MAX_ total entries)', 'eform-es' ),
				/* translators: %s will be replaced by an empty string */
				'sInfoPostFix' => sprintf( _x( '%s', 'sInfoPostFix', 'eform-es' ), '' ),
				/* translators: For thousands separator inside datatables */
				'sInfoThousands' => _x( ',', 'sInfoThousands', 'eform-es' ),
				'sLengthMenu' => __( 'Show _MENU_ entries', 'eform-es' ),
				'sLoadingRecords' => $ajax_loader,
				'sProcessing' => $ajax_loader,
				'sSearch' => '',
				'sSearchPlaceholder' => __( 'Search submissions', 'eform-es' ),
				'sZeroRecords' => __( 'No matching records found', 'eform-es' ),
				'oPaginate' => array(
					'sFirst' => __( '<i title="First" class="ipticm ipt-icomoon-first"></i>', 'eform-es' ),
					'sLast' => __( '<i title="Last" class="ipticm ipt-icomoon-last"></i>', 'eform-es' ),
					'sNext' => __( '<i title="Next" class="ipticm ipt-icomoon-forward4"></i>', 'eform-es' ),
					'sPrevious' => __( '<i title="Previous" class="ipticm ipt-icomoon-backward3"></i>', 'eform-es' ),
				),
				'oAria' => array(
					'sSortAscending' => __( ': activate to sort column ascending', 'eform-es' ),
					'sSortDescending' => __( ': activate to sort column descending', 'eform-es' ),
				),
				'filters' => array(
					'form' => __( 'Select form to filter', 'eform-es' ),
					'category' => __( 'Select category to filter', 'eform-es' ),
				),
			),
			'allLabel' => __( 'All', 'eform-es' ),
			'allFilter' => __( 'Show all', 'eform-es' ),
			'dpPlaceholderf' => __( 'From', 'eform-es' ),
			'dpPlaceholdert' => __( 'To', 'eform-es' ),
			'sPlaceholder' => __( 'Search', 'eform-es' ),
		) );
	}

	public function show_list( $appearance, $content = null ) {
		// First setup the leaderboard type stuff and enqueue
		// Get the basic instance of form
		$form = new IPT_FSQM_Form_Elements_Front( null, $this->form_id );

		// Check if form exists
		if ( null == $form->form_id ) {
			$form->container( array( array( $form->ui, 'msg_error' ), array( __( 'Please check the code.', 'eform-es' ), true, __( 'Invalid ID', 'eform-es' ) ) ), true );
			return;
		}

		// Get the form theme
		$theme = $form->settings['theme']['template'];

		// Get the theme element needed for printing
		$theme_element = $form->get_theme_by_id( $theme );

		// Enqueue
		$this->enqueue( $theme_element );

		// Start our table
		?>
		<div class="ipt_eform_leaderboard ipt_eform_leaderboard_form ipt_uif_front ipt_uif_common" data-ui-theme="<?php echo esc_attr( json_encode( $theme_element['include'] ) ); ?>" data-ui-theme-id="<?php echo esc_attr( $theme ); ?>">
			<noscript>
				<div class="ipt_fsqm_form_message_noscript ui-widget ui-widget-content ui-corner-all">
					<div class="ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
						<h3><?php _e( 'Javascript is disabled', 'ipt_fsqm' ); ?></h3>
					</div>
					<div class="ui-widget-content ui-corner-bottom">
						<p><?php _e( 'Javascript is disabled on your browser. Please enable it in order to use this form.', 'ipt_fsqm' ); ?></p>
					</div>
				</div>
			</noscript>
			<?php $form->ui->ajax_loader( false, '', array(), true, __( 'Loading', 'ipt_fsqm' ), array( 'ipt_uif_init_loader' ) ); ?>
			<div style="display: none" class="ipt_uif_hidden_init ipt_eform_lb_main_container ui-widget-content ui-corner-all">
				<div class="ipt_eform_leaderboard_welcome">
					<?php if ( '' !== $form->settings['theme']['logo'] && true == $appearance['image'] ) : ?>
						<div class="ipt_eform_leaderboard_form_logo">
							<img src="<?php echo esc_attr( $form->settings['theme']['logo'] ); ?>" class="ipt_eform_lb_logo ui-corner-all" />
						</div>
					<?php endif; ?>
					<?php if ( true === $appearance['heading'] ) : ?>
						<h2 class="ipt_eform_lb_title"><?php echo $form->name; ?></h2>
					<?php endif; ?>
					<?php if ( null !== $content && '' !== $content ) : ?>
						<div class="ipt_fsqm_lb_msg">
							<?php echo do_shortcode( wpautop( $content ) ); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="ipt_eform_leaderboard_data">
					<table class="ipt_eform_lb_table ipt-eform-lb-table">
						<thead>

						</thead>
						<tfoot>

						</tfoot>
						<tbody>

						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}

	private function get_all_data() {
		global $wpdb, $ipt_fsqm_info;
		$query = $wpdb->prepare( "SELECT * FROM {$ipt_fsqm_info['data_table']} WHERE form_id = %d ORDER BY ", $this->form_id ); // WPCS: unprepared sql ok
		$query .= $this->orderby . ' ' . $this->order; // WPCS: unprepared sql ok
		if ( $this->limit > 0 ) {
			$query .= $wpdb->prepare( ' LIMIT 0, %d', $this->limit );
		}
		return $wpdb->get_results( $query );
	}
}
