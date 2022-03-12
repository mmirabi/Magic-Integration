<?php
global $magic

?>
<script>
    let Magic_CSI_ajax = '<?php echo admin_url('admin-ajax.php'); ?>',
        Magic_CSI_nonce = '<?php wp-create_nonce('admin-nonce'); ?>',
</script>
<script src="<?php echo MAGIC_CSI_URL . 'public/assets/js/magic/magic.js'; ?>?version=<?php echo MAGIC_CSI_PLUGIN_VERSION; ?>"></script>