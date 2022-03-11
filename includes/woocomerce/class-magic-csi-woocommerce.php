<?php

namespace Magic_CSI;

/**
 * Class Option
 * @package Magic_CSI
 */
class WooCommerce {

	/**
	 * Option constructor.
	 */
	public function __construct() {

		// Override max linked variations
		if ( ! defined( 'WC_MAX_LINKED_VARIATIONS' ) ) {
			define( 'WC_MAX_LINKED_VARIATIONS', 150 );
		}

		add_filter( 'woocommerce_ajax_variation_threshold', [ $this, 'removeVariationLimit' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'loadScripts' ] );
		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'addFieldsThatWeeNeed' ], 10 );
		// Logic to Save products custom fields values into the cart item data
		add_action( 'woocommerce_add_cart_item_data', [ $this, 'saveCustomDataOnItem' ], 10, 2 );
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'addCustomPriceToProduct' ], 10 );
		add_filter( 'woocommerce_cart_item_price', [ $this, 'updateCustomPriceMiniCart' ], 10, 3 );
		add_filter( 'woocommerce_get_item_data', [ $this, 'showCustomDataOnCart' ], 1, 2 );
		add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'addDataItemsToOrder' ], 10, 4 );
	}


	/**
	 * Add items data to the order
	 *
	 * @param $item
	 * @param $cart_item_key
	 * @param $values
	 * @param $order
	 *
	 * @return void
	 */
	public function addDataItemsToOrder( $item, $cart_item_key, $values, $order ) {

		$customData = $values['custom_data'] ?? '';

		if ( $customData && ! empty( $customData['magic_shape'] ) ) {
			$suffix = ' Inch';
			$item->update_meta_data( 'Width', $customData['magic_width'] . $suffix );
			$item->update_meta_data( 'Height', $customData['magic_height'] . $suffix );
			$item->update_meta_data( 'Style', $customData['magic_shape'] );
		}
	}

	/**
	 * Show Custom data on cart page
	 *
	 * @param $item_data
	 * @param $cart_item
	 *
	 * @return mixed|string
	 */
	public function showCustomDataOnCart( $item_data, $cart_item ) {


		$customData = $cart_item['custom_data'] ?? '';

		if ( $customData && ! empty( $customData['magic_shape'] ) ) {

			$suffix     = ' Inch';
			$itemWidth  = $customData['magic_width'] . $suffix;
			$itemheight = $customData['magic_height'] . $suffix;
			$itemShape  = $customData['magic_shape'];

			return array_merge( $item_data, [ [ 'key' => 'Width', 'value' => $itemWidth ], [ 'key' => 'Height', 'value' => $itemheight ], [ 'key' => 'Style', 'value' => $itemShape ] ] );
		}
	}

	/**
	 * Save our custom data on items WooCommerce Cart
	 *
	 * @param $cart_item_data
	 * @param $product_id
	 *
	 * @return array
	 */
	public function saveCustomDataOnItem( $cart_item_data, $product_id ) {
		$data = [];

		if ( isset( $_REQUEST['magic_final_price'] ) && isset( $_REQUEST['attribute_pa_size'] ) && $_REQUEST['attribute_pa_size'] == 'custom-size' ) {
			$cart_item_data['custom_data']['magic_final_price'] = $_REQUEST['magic_final_price'];
			$data['magic_final_price']                          = $_REQUEST['magic_final_price'];

			$pa_width                                       = $_REQUEST['shape_width'] ?? 'Not set';
			$cart_item_data['custom_data']['magic_width'] = $pa_width;

			$pa_height                                       = $_REQUEST['shape_height'] ?? 'Not set';
			$cart_item_data['custom_data']['magic_height'] = $pa_height;

			$selectedStyle                                  = ucfirst( $_REQUEST['selected_shape'] ) ?? 'Not set';
			$cart_item_data['custom_data']['magic_shape'] = $selectedStyle;
		}

		// below statement make sure every add to cart action as unique line item
		$cart_item_data['custom_data']['unique_key'] = md5( microtime() . rand() );

		WC()->session->set( 'price_calculation', $data );

		return $cart_item_data;
	}

	/**
	 * Update mini cart item price to show correct price
	 *
	 * @param $price_html
	 * @param $cart_item
	 * @param $cart_item_key
	 *
	 * @return void
	 */
	public function updateCustomPriceMiniCart( $price_html, $cart_item, $cart_item_key ) {
		$customData = $cart_item['custom_data'] ?? '';

		if ( $customData && ! empty( $customData['magic_shape'] ) ) {

			$args = [ 'price' => $customData['magic_final_price'] ];

			if ( WC()->cart->display_prices_including_tax() ) {
				$product_price = wc_get_price_including_tax( $cart_item['data'], $args );
			} else {
				$product_price = wc_get_price_excluding_tax( $cart_item['data'], $args );
			}

			return wc_price( $product_price );
		}

		return $price_html;

	}

	/**
	 * Add our new custom price to our products
	 *
	 * @param $cart_object
	 *
	 * @return void
	 */
	public function addCustomPriceToProduct( $cart_object ) {

		foreach ( $cart_object->get_cart() as $item_values ) {


			##  Get cart item data
//			$item_id        = $item_values['data']->id; // Product ID
//			$original_price = $item_values['data']->price; // Product original price

			## Get your custom fields values
			if ( isset( $item_values['custom_data']['magic_final_price'] ) ) {
				$final_price = $item_values['custom_data']['magic_final_price'];

				## Set the new item price in cart
				$item_values['data']->set_price( $final_price );
			}
		}

	}

	/**
	 * Remove Variation limitation on WooCommerce
	 *
	 * @param $default
	 * @param $product
	 *
	 * @return int
	 */
	public function removeVariationLimit( $default, $product ) {
		return WC_MAX_LINKED_VARIATIONS;
	}

	/**
	 * Load scripts that we need for products WooCommerce
	 *
	 * @return void
	 */
	public function loadScripts() {
		global $post;

		if ( isset( $post->post_type ) && $post->post_type === 'product' && get_field( 'custom_integration', $post->ID ) ) {
			wp_enqueue_script( 'Magic-CSI', MAGIC_CSI_URL . 'public/assets/js/scripts.js', [ 'jquery' ], MAGIC_CSI_PLUGIN_VERSION );
			wp_enqueue_style( 'Magic-CSI', MAGIC_CSI_URL . 'public/assets/css/style.css', [], MAGIC_CSI_PLUGIN_VERSION );

			// Add localization
//				wp_localize_script( 'Magic-CSI', 'magic-variables',
//					[
//						'product_' => 'value 1',
//						'data_var_2' => 'value 2',
//					]
//				);
		}
	}

	/**
	 * Add Custom fields to variation products
	 *
	 * @return void
	 */
	public function addFieldsThatWeeNeed() {
		global $product;
		$productID = $product->get_id();

		if ( get_field( 'custom_integration', $productID ) ) {

			include "variation-form.php";
		}
	}

}

new WooCommerce();