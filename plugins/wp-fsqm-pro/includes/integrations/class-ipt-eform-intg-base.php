<?php

/**
 * Integration base class.
 */
abstract class IPT_EForm_Intg_Base {
	/**
	 * Configuration data for this integration.
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Additional metadata for custom fields as recovered from value class.
	 *
	 * @var array
	 */
	protected $additional_metadata = [];

	/**
	 * Basic information for the subscriber.
	 *
	 * @var array
	 */
	protected $basic_info = [
		'f_name' => '',
		'l_name' => '',
		'full_name' => '',
		'email' => '',
		'phone' => '',
		'ip' => '',
	];

	/**
	 * Create an integration instance.
	 *
	 * @param array $config Config variable as understood by it.
	 * @param IPT_FSQM_Form_Elements_Data $data Data instance
	 */
	public function __construct( $config, $data ) {
		$this->config = $config;
		$this->basic_info = [
			'f_name' => $data->data->f_name !== ''
				? $data->data->f_name
				: __( 'Anonymous', 'ipt_fsqm' ),
			'l_name' => $data->data->l_name,
			'email' => $data->data->email,
			'phone' => $data->data->phone,
			'ip' => $data->data->ip,
		];
		$this->basic_info['full_name'] = $this->basic_info['f_name'];
		if ( $this->basic_info['l_name'] !== '' ) {
			$this->basic_info['full_name'] .= ' ' . $this->basic_info['l_name'];
		}

		if (
			isset( $config['meta'] )
			&& $config['meta']['active'] === true
			&& ! empty( $config['meta']['data'] )
		) {
			$data_values = new IPT_eForm_Form_Elements_Values( $data->data_id );
			// Loop through all and add the meta
			foreach ( $config['meta']['data'] as $metadata ) {
				$meta_key = $metadata['meta_key'];
				$meta_value = $data_values->get_value(
					$metadata['m_type'],
					$metadata['key'],
					'string',
					'label'
				);
				$this->additional_metadata[ $meta_key ] = strip_tags( $meta_value );
			}
		}
	}

	/**
	 * Run the integration.
	 *
	 * @return void
	 */
	abstract public function do_integration();
}
