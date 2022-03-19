<?php
global $magic;

?>
<script>
    let Listirs_CSI_ajax = '<?php echo admin_url( 'admin-ajax.php' ); ?>',
        Listirs_CSI_nonce = '<?php echo wp_create_nonce( 'ajax-nonce' ); ?>';
</script>
<script src="<?php echo LISTIRS_CSI_URL . 'public/assets/js/magic/magic.js'; ?>?version=<?php echo LISTIRS_CSI_PLUGIN_VERSION; ?>"></script>


