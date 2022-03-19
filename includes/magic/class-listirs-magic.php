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

}

new Magic();