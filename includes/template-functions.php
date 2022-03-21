<?php

use Listirs_CSI\Option;

/**
 * @param $option_name
 *
 * @return string
 */
function listirs_csi_get_option( $option_name ) {
	return Option::get( $option_name );
}