jQuery(document).ready(function ($) {

    let originalUrl = $('#magic-customize-button').attr('href');
    let newUrl = $('input[name="listirs_product_base"]').val();

    // Detect change size of the product option
    $(document).on('change', 'select[name="attribute_pa_size"]', function () {

        let $customFields = $('.input_fields.custom-input-fields'), $addToCartButton = $('.single_add_to_cart_button'),
            $priceTop = $('.summary .woocommerce-Price-amount'), $priceLabel = $('.sale-item.product-label'),
            $magicButton = $('#magic-customize-button');

        if (this.value === 'custom-size') {
            $magicButton.attr('href', newUrl);
            $priceTop.fadeOut('slow');
            $priceLabel.fadeOut('slow');
            $addToCartButton.addClass('disabled wc-variation-selection-needed');
            $magicButton.addClass('disabled');
            $customFields.fadeIn('slow');
        } else {
            // $addToCartButton.removeClass('disabled wc-variation-selection-needed');
            // $magicButton.removeClass('disabled');
            $magicButton.attr('href', originalUrl)
            $priceTop.fadeIn('slow');
            $priceLabel.fadeIn('slow');
            $customFields.fadeOut('slow');
        }
    });

    // Override Magic button action
    $('#magic-customize-button').unbind("click").bind("click", function (e) {
        checkVariables(e);
    });

    /**
     * Override Add to cart button
     */
    $('.single_add_to_cart_button').unbind("click").bind("click", function (e) {
        checkVariables(e);
    });

    /**
     * When click on shapes
     */
    $('input[name="selected_shape"]').change(function () {
        $('input[name="shape_width"], input[name="shape_height"]').val('0');
        $('.input-group.width-height').fadeIn('slow');

        let $this = $(this), $basePrice = $('.base-price'), $customPrice = $('.custom-price'),
            $resultPrice = $('.result-price'), selectedPrice = $('input[name="' + $this.val() + '_price"]').val(),
            selectedMin = $('input[name="' + $this.val() + '_min_size"]').val(),
            selectedMax = $('input[name="' + $this.val() + '_max_size"]').val();

        $basePrice.text(selectedPrice.toString());
        $customPrice.text('0');
        $resultPrice.text(selectedPrice.toString());

        $('input[name="shape_width"], input[name="shape_height"]').attr('min', selectedMin);
        $('input[name="shape_width"], input[name="shape_height"]').attr('max', selectedMax);


        $('.single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed');
        $('#magic-customize-button').removeClass('disabled');

        $('input[name="listirs_final_price"]').val(selectedPrice);

        newUrl = updateUrlParameter(newUrl, 'shape', $this.val());
        $('#magic-customize-button').attr('href', newUrl);
    });


    // On change width and height
    $('input[name="shape_width"], input[name="shape_height"]').on('input', function () {
        let $this = $(this), $basePrice = $('.base-price'), $customPrice = $('.custom-price'),
            $estimateTime = $('.estimate-time'), $resultPrice = $('.result-price'),
            currentShape = jQuery('input[name="selected_shape"]:checked').val(),
            selectedPrice = parseFloat($('input[name="' + currentShape + '_price"]').val()),
            currentTime = parseFloat($('input[name="' + currentShape + '_time"]').val()).toFixed(1),
            currentWidth = parseFloat($('input[name="shape_width"]').val()),
            currentHeight = parseFloat($('input[name="shape_height"]').val()),
            estimateTime = parseFloat(parseFloat(currentWidth * currentHeight).toFixed(0) * currentTime).toFixed(0),
            finalPrice = parseFloat(currentWidth * currentHeight) * 10,
            calculatedPrice = Math.ceil(selectedPrice * finalPrice), minEstimateTime = 30, maxEstimatetime = 180;


        if (estimateTime >= maxEstimatetime) {
            estimateTime = maxEstimatetime;
        } else if (estimateTime <= minEstimateTime) {
            estimateTime = minEstimateTime
        }

        $basePrice.text(selectedPrice.toString());
        $customPrice.text(finalPrice.toString());
        $resultPrice.text(calculatedPrice.toLocaleString('en-US', {
            style: 'currency', currency: 'USD',
        }));
        $estimateTime.text(estimateTime.toString())

        $('input[name="listirs_final_price"]').val(calculatedPrice.toString());
        $('input[name="listirs_final_time"]').val(estimateTime.toString());

        newUrl = updateUrlParameter(newUrl, $(this).attr('name'), $this.val());
        newUrl = updateUrlParameter(newUrl, 'price', calculatedPrice.toString());
        $('#magic-customize-button').attr('href', newUrl);
    });

    // Check variables entered or not
    function checkVariables(e) {

        if ($('select[name="attribute_pa_size"]').val() === 'custom-size') {
            let shapes = false;

            $('input[name="selected_shape"]').each(function (index, value) {
                if ($(this).prop('checked') === true) {
                    shapes = true;
                }
            });

            if (shapes === false) {
                e.preventDefault();
                alert('Please select an style first.');
                return;
            }
            let width = $('input[name="shape_height"]').val(), height = $('input[name="shape_height"]').val();
            if (width == '0' || height == '0') {
                e.preventDefault();
                alert('Please set Width & Height value first, not zero.');
                return;
            }
        }
    }
});

function updateUrlParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'), results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

jQuery(window).load(function () {
    setTimeout(() => {
        jQuery('select[name="attribute_pa_size"]').change();
    }, 500);

});