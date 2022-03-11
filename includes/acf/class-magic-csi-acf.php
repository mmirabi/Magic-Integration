<?php

namespace Magic_CSI;

/**
 * Class Admin
 * @package Magic_CSI
 */
class ACF {
	/**
	 * ACF constructor.
	 */
	public function __construct() {

		add_filter( 'acf/settings/load_json', [ $this, 'loadAcfJson' ] );


		add_filter( 'aljm_save_json', function ( $folders ) {
			$folders['Magic'] = MAGIC_CSI_ABSPATH . 'includes/acf/local-json';

			return $folders;
		} );
	}

	/**
	 * Load saved ACF config
	 *
	 * @param $paths
	 *
	 * @return mixed
	 */
	function loadAcfJson( $paths ) {

		// append path
		$paths[] = MAGIC_CSI_ABSPATH . 'includes/acf/local-json';

		return $paths;
	}

}

new ACF();
