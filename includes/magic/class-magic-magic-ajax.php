<?php

namespace Magic_CSI;

/**
 * Class Option
 * @package Magic_CSI
 */
class MagicAjax {

	/**
	 * Option constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_custom_magic_get_info', [ $this, 'getInfo' ] );
		add_action( 'wp_ajax_nopriv_custom_magic_get_info', [ $this, 'getInfo' ] );
	}

	/**
	 * Get shape info
	 * Min,Max,Time,Price
	 *
	 * @return void
	 */
	public function getInfo() {
		$nonce     = $_POST['nonce'] ?? '';
		$shape     = lcfirst( $_POST['shape'] ) ?? '';
		$productID = $_POST['product_id'] ?? '';

		if ( empty( $nonce ) || empty( $shape ) || empty( $productID ) ) {
			wp_send_json( 'Variables not set!', 400 );
		}

		if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
			wp_send_json( 'Cannot verify the request.', 400 );
		}

		$info = [];

		$info['min']   = get_field( $shape . '_min_size', $productID );
		$info['max']   = get_field( $shape . '_max_size', $productID );
		$info['time']  = get_field( $shape . '_time', $productID );
		$info['price'] = get_field( $shape . '_price', $productID );

		wp_send_json( $info, 200 );
	}
}

new MagicAjax();