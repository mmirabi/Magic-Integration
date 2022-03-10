<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Magic_CSI {

    public function __construct() {
        $this->set_construct();
        $this->includes();
        $this->init_hooks();
    }

    private function set_constants() {
        define( 'MAGIC_CSI_ABSPATH', plugin_dir_path( MAGIC_CSI_PLUGIN_FINE )
    }
}
