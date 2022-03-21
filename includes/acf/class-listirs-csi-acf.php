<?php

namespace Listirs_CSI;

/**
 * Class Admin
 * @package Listirs_CSI
 */
class ACF {
	/**
	 * ACF constructor.
	 */
	public function __construct() {

		add_filter( 'acf/settings/load_json', [ $this, 'loadAcfJson' ] );


		add_filter( 'aljm_save_json', function ( $folders ) {
			$folders['Listirs'] = LISTIRS_CSI_ABSPATH . 'includes/acf/local-json';

			return $folders;
		} );

		add_filter( 'acf/load_field/name=custom_product_id', [ $this, 'setFieldValues' ] );
	}

	/**
	 * Load saved ACF config
	 *
	 * @param $paths
	 *
	 * @return mixed
	 */
	public function loadAcfJson( $paths ) {

		// append path
		$paths[] = LISTIRS_CSI_ABSPATH . 'includes/acf/local-json';

		return $paths;
	}

	public function setFieldValues( $field ) {


		global $wpdb;

		$field['choices'] = [];
		$choices          = $wpdb->get_results( "SELECT `id`, `name` FROM `magic_products`", 'ARRAY_A' );

		if ( ! empty( $choices ) ) {
			foreach ( $choices as $choice ) {
				$field['choices'][ $choice['id'] ] = $choice['name'];
			}
		}

		return $field;
	}
}

new ACF();
