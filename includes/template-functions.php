<?php 

use Magic_CSI\Option;

/**
 * @param $option_name
 * 
 * @return string
 */
function magic_csi_get_option( $option_name ) {
    return Option::get( $option_name );
}