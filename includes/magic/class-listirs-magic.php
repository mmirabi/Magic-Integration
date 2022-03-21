<?php

namespace Listirs_CSI;

/**
 * Class Option
 * @package Listirs_CSI
 */
class Magic {

	/**
	 * Option constructor.
	 */
	public function __construct() {
		add_action( 'magic_editor-header', [ $this, 'loadOnHeader' ] );
		add_action( 'magic_editor-footer', [ $this, 'loadOnFooter' ] );
		add_filter( 'magic_product_extra_price', [ $this, 'changeProductPrice' ], 99, 2 );
		add_filter( 'magic_product_base_price', [ $this, 'setBaseProductToZero' ], 99 );
		add_action( 'woocommerce_process_product_meta', [ $this, 'setProductIdOnSave' ], 99 );
	}


	/**
	 * Load header contents
	 *
	 * @return void
	 */
	public function loadOnHeader() {
		include 'header.php';
	}

	/**
	 * Load footer contents
	 *
	 * @return void
	 */
	public function loadOnFooter() {
		include 'footer.php';
	}

	/**
	 * Set and change product price on checkout Magic
	 *
	 * @param $array | empty
	 * @param $data
	 *
	 * @return mixed
	 */
	public function changeProductPrice( $array, $data ) {

		return [ $data->price_total ] ?? [];
	}

	/**
	 * Set any default base price to Zero
	 *
	 * @return int
	 */
	public function setBaseProductToZero( $price ) {

		return 0;
	}

	/**
	 * Set product ID to Magic products
	 *
	 * @param $productID
	 *
	 * @return void
	 */
	public function setProductIdOnSave( $productID ) {
		if ( get_field( 'custom_integration', $productID ) ) {

			$getCustomProductId = get_field( 'custom_product_id', $productID );
			if ( $getCustomProductId ) {

				global $wpdb;
				$wpdb->query( "UPDATE `magic_products` SET `product` = $productID WHERE `id` = $getCustomProductId" );
			}
		}
	}
}

new Magic();