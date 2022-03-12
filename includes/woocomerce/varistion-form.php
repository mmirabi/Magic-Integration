<style>
    .radio-img-container {
        background: url("<?php echo get_the_post_thumbnail_url($productID)?>") no-repeat center center;
        background-size: 100px;
    }
</style>
<div class="input_fields custom-input-fields">
    <input type="hidden" name="circle_price" value="<?php the_field( 'circle_price' ); ?>"/>
    <input type="hidden" name="rectangle_price" value="<?php the_field( 'rectangle_price' ); ?>"/>
    <input type="hidden" name="oval_price" value="<?php the_field( 'oval_price' ); ?>"/>
    <input type="hidden" name="runner_price" value="<?php the_field( 'runner_price' ); ?>"/>
    <input type="hidden" name="circle_time" value="<?php the_field( 'circle_time' ); ?>"/>
    <input type="hidden" name="rectangle_time" value="<?php the_field( 'rectangle_time' ); ?>"/>
    <input type="hidden" name="oval_time" value="<?php the_field( 'oval_time' ); ?>"/>
    <input type="hidden" name="runner_time" value="<?php the_field( 'runner_time' ); ?>"/>
    <input type="hidden" name="circle_min_size" value="<?php the_field( 'circle_min_size' ); ?>"/>
    <input type="hidden" name="circle_max_size" value="<?php the_field( 'circle_max_size' ); ?>"/>
    <input type="hidden" name="rectangle_min_size" value="<?php the_field( 'rectangle_min_size' ); ?>"/>
    <input type="hidden" name="rectangle_max_size" value="<?php the_field( 'rectangle_max_size' ); ?>"/>
    <input type="hidden" name="oval_min_size" value="<?php the_field( 'oval_min_size' ); ?>"/>
    <input type="hidden" name="oval_max_size" value="<?php the_field( 'oval_max_size' ); ?>"/>
    <input type="hidden" name="runner_min_size" value="<?php the_field( 'runner_min_size' ); ?>"/>
    <input type="hidden" name="runner_max_size" value="<?php the_field( 'runner_max_size' ); ?>"/>
    <input type="hidden" name="magic_final_price" value=""/>
    <input type="hidden" name="magic_final_time" value=""/>
    <label class="radio-btn-label">Select style</label>
	<?php
	global $wpdb;

	if ( ! empty( $product->get_id() ) ) {
		$pid  = $product->get_id();
		$tool = site_url( '/?magic=design' );

		$magicProduct = get_field( 'custom_product_id' );
		if ( $magicProduct ):
			$link_design = str_replace( '?&', '?', $tool . '&product_base=' . $magicProduct . '&product_cms=' . $pid . '&shape_width=0&shape_height=0&shape=' );
			?>
            <input type="hidden" name="magic_product_base" value="<?php echo $link_design; ?>"/>
		<?php endif;
	} ?>
    <br>
    <div class="radio-btns">
		<?php if ( get_field( 'runner_enable' ) ): ?>
            <label class="radio-runner">
                <span>$ <?php the_field( 'runner_price' ); ?></span>
                <input type="radio" name="selected_shape" value="runner">
                <div class="radio-img-container">
                </div>
                <span>Runner</span>
            </label>
		<?php endif;
		if ( get_field( 'oval_enable' ) ): ?>
            <label class="radio-oval">
                <span>$ <?php the_field( 'oval_price' ); ?></span>
                <input type="radio" name="selected_shape" value="oval">
                <div class="radio-img-container">
                </div>
                <span>Oval</span>
            </label>
		<?php endif;
		if ( get_field( 'circle_enable' ) ): ?>
            <label class="radio-circle">
                <span>$ <?php the_field( 'circle_price' ); ?></span>
                <input type="radio" name="selected_shape" value="circle">
                <div class="radio-img-container">
                </div>
                <span>Circle</span>
            </label>
		<?php endif;
		if ( get_field( 'rectangle_enable' ) ): ?>
            <label class="radio-rectangle">
                <span>$ <?php the_field( 'rectangle_price' ); ?></span>
                <input type="radio" name="selected_shape" value="rectangle">
                <div class="radio-img-container">
                </div>
                <span>Rectangle</span>
            </label>
		<?php endif; ?>
    </div>
    <div class="input-group width-height">
        <div class="inline-inputs">
            <label class="label">Width size (Foot)
                <input type="number" class="number-field" id="shape_width" name="shape_width" value="0"/></label>
            <span>X</span>
            <label class="label">Height size (Foot)
                <input type="number" class="number-field" id="shape_height" name="shape_height" value="0"/></label>
        </div>
        <p class="final-price">Final Price: $<span class="base-price"></span> x $<span class="custom-price">0</span> =
                               <span class="result-price"></span></p>
        <p class="final-production-time">Production Estimate Time: <span class="estimate-time">0</span> Days</p>
        <p class="formula">The Formula:
            <span style="color: #444; font-weight: 400;">base price x custom size price = Final price</span></p>
    </div>
</div>