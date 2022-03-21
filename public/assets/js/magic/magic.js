jQuery(document).ready(function ($) {
    let successExec = false, productLeft = '', productTop = '', originalEncodedSrc = '', widthInputName = '',
        heightInputName = '', shape = getParameterByName('shape') ?? '0',
        shapeWidth = getParameterByName('shape_width') ?? '0', shapeHeight = getParameterByName('shape_height') ?? '0',
        shapeTime = '', shapePrice = '', needToSetSize = false, productName = '';

    $('#MagicDesign').on('DOMSubtreeModified', function () {

        if (this.dataset.processing === '' && successExec === false && magic.stage().hasOwnProperty('product') && magic.stage().product.hasOwnProperty('origin_src')) {
            successExec = true;

            productName = magic.ops.product_data.name;

            $.ajax({
                url: magic.stage().product.origin_src, method: 'GET', dataType: 'text', statusCode: {
                    403: function () {
                        magic.fn.notice(magic.i(33) + '(403) ' + magic.stage().product.origin_src, 'error', 3500);
                        callback(null);
                    }, 404: function () {
                        magic.fn.notice(magic.i(33) + '(404) ' + magic.stage().product.origin_src, 'error', 3500);
                        callback(null);
                    }
                }, success: function (res) {
                    let encodedSVG = 'data:image/svg+xml;base64,' + btoa(res.toString());
                    originalEncodedSrc = encodedSVG;
                    magic.stage().product.set('src', encodedSVG);
                    magic.stage().product.type = 'svg'
                    magic.fn.set_svg_colors(magic.stage().product);
                    magic.stage().product.evented = true;
                    magic.stage().product.selectable = true;
                    magic.stage().product.hasControls = false;
                    magic.stage().canvas.setActiveObject(magic.stage().product);
                    magic.stack.save();
                    magic.stage().canvas.renderAll();
                    magic.stage().product.lockMovementX = true;
                    magic.stage().product.lockMovementY = true;
                    productLeft = magic.stage().product.getLeft();
                    productTop = magic.stage().product.getTop();

                    $(document).on('click', '.canvas-container', function () {
                        lockMovementObject();
                    });

                    $(document).on("keydown", function (e) {
                        lockMovementObject();
                    });

                    $(document).on('click', '#magic-product input[type="text"]', function () {
                        $(this).prop('type', 'number');
                    });

                    $.each(magic.ops.product_data.attributes, function (key, value) {
                        if (typeof value === 'object') {
                            $.each(value, function (keyVal, valueVal) {
                                if (typeof valueVal === 'string') {
                                    if (valueVal.includes("width") || valueVal.includes("Width")) {
                                        widthInputName = value.id;
                                    } else if (valueVal.includes("height") || valueVal.includes("Height")) {
                                        heightInputName = value.id;
                                    }
                                }
                            });
                        }
                    });


                    $('input[name="' + widthInputName + '"]').val(shapeWidth);
                    $('input[name="' + heightInputName + '"]').val(shapeHeight);
                    needToSetSize = true;

                    if (shape) {
                        $('#magic-product select').val(shape.charAt(0).toUpperCase() + shape.slice(1)).change();
                    }

                    // Insert formula price info and more
                    if (!$('.final-price').length > 0) {
                        $('#magic-cart-wrp').after('<p class="final-price">Final Price: $<span class="base-price"></span> x $<span class="custom-price">0</span> =\n' + '                               <span class="result-price"></span></p>\n' + '        <p class="final-production-time">Production Estimate Time: <span class="estimate-time">0</span> Days</p>\n' + '        <p class="formula">The Formula:\n' + '            <span class="formula-text" style="font-weight: 400;">base price x custom size price = Final price</span></p>');
                    } else {
                        $('.formula-text').text('base price x custom size price = Final price');
                    }

                    // Get and Set shape Info
                    $.ajax({
                        url: Listirs_CSI_ajax, method: 'POST', data: {
                            nonce: Listirs_CSI_nonce,
                            action: 'custom_magic_get_info',
                            shape: $('#magic-product select').val(),
                            product_id: getParameterByName('product_cms')
                        }, success: function (res) {
                            if (res && typeof res === 'object') {

                                $('input[name="' + widthInputName + '"]').prop('min', res.min);
                                $('input[name="' + widthInputName + '"]').prop('max', res.max);

                                $('input[name="' + heightInputName + '"]').prop('min', res.min);
                                $('input[name="' + heightInputName + '"]').prop('max', res.max);

                                shapePrice = res.price;
                                $('.base-price').text(shapePrice.toString())

                                shapeTime = res.time;
                            }

                            // On change width and height
                            $('input[name="' + widthInputName + '"], input[name="' + heightInputName + '"]').on('input', function () {

                                let $this = $(this), $basePrice = $('.base-price'), $customPrice = $('.custom-price'),
                                    $estimateTime = $('.estimate-time'), $resultPrice = $('.result-price'),
                                    selectedPrice = parseFloat(shapePrice),
                                    currentTime = parseFloat(shapeTime).toFixed(1),
                                    currentWidth = parseFloat($('input[name="' + widthInputName + '"]').val()),
                                    currentHeight = parseFloat($('input[name="' + heightInputName + '"]').val()),
                                    estimateTime = parseFloat(parseFloat(currentWidth * currentHeight).toFixed(0) * currentTime).toFixed(0),
                                    finalPrice = parseFloat(currentWidth * currentHeight) * 10,
                                    calculatedPrice = Math.ceil(selectedPrice * finalPrice), minEstimateTime = 30,
                                    maxEstimatetime = 180;


                                // console.log(selectedPrice);

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

                                changeCartPrice(calculatedPrice, 0);
                            });

                            $('input[name="' + widthInputName + '"]').trigger("input");
                        }
                    });

                }
            });
        }
    });


    function lockMovementObject() {
        magic.stage().canvas.setActiveObject(magic.stage().product);
        magic.stage().product.lockMovementX = true;
        magic.stage().product.lockMovementY = true;
        magic.stage().product.lockRotation = true;
        magic.stage().product.lockScalingX = true;
        magic.stage().product.lockScalingY = true;
        magic.stage().product.lockUniScaling = true;
        magic.stage().product.left = productLeft;
        magic.stage().product.top = productTop;
        magic.stage().canvas.renderAll();
    }

    magic.add_filter('before_import_object', function (object) {

        if (typeof object !== 'undefined') {

            $.ajax({
                url: magic.stage().product.origin_src, method: 'GET', dataType: 'text', statusCode: {
                    403: function () {
                        magic.fn.notice(magic.i(33) + '(403) ' + magic.stage().product.origin_src, 'error', 3500);
                        callback(null);
                    }, 404: function () {
                        magic.fn.notice(magic.i(33) + '(404) ' + magic.stage().product.origin_src, 'error', 3500);
                        callback(null);
                    }
                }, success: function (res) {
                    let encodedSVG = 'data:image/svg+xml;base64,' + btoa(res);
                    magic.stage().product.set('src', encodedSVG);
                    magic.stage().product.type = 'svg'
                    magic.fn.set_svg_colors(magic.stage().product);
                    magic.stage().product.evented = true;
                    magic.stage().product.selectable = true;
                    magic.stage().product.hasControls = false;
                    magic.stage().canvas.setActiveObject(magic.stage().product);
                    magic.stack.save();
                    magic.stage().canvas.renderAll();
                    magic.stage().product.lockMovementX = true;
                    magic.stage().product.lockMovementY = true;
                    productLeft = magic.stage().product.getLeft();
                    productTop = magic.stage().product.getTop();
                }
            });
        }

        // Get and Set shape Info
        $.ajax({
            url: Listirs_CSI_ajax, method: 'POST', data: {
                nonce: Listirs_CSI_nonce,
                action: 'custom_magic_get_info',
                shape: $('#magic-product select').val(),
                product_id: getParameterByName('product_cms')
            }, success: function (res) {
                if (res && typeof res === 'object') {
                    if (!needToSetSize) {
                        $('input[name="' + heightInputName + '"]').val('0');
                        $('input[name="' + widthInputName + '"]').val('0');
                    }

                    $('input[name="' + widthInputName + '"]').prop('min', res.min);
                    $('input[name="' + widthInputName + '"]').prop('max', res.max);

                    $('input[name="' + heightInputName + '"]').prop('min', res.min);
                    $('input[name="' + heightInputName + '"]').prop('max', res.max);

                    shapePrice = res.price;
                    $('.base-price').text(shapePrice.toString())

                    shapeTime = res.time;
                }

                needToSetSize = false;

                // On change width and height
                $('input[name="' + widthInputName + '"], input[name="' + heightInputName + '"]').on('input', function () {

                    let $this = $(this), $basePrice = $('.base-price'), $customPrice = $('.custom-price'),
                        $estimateTime = $('.estimate-time'), $resultPrice = $('.result-price'),
                        selectedPrice = parseFloat(shapePrice), currentTime = parseFloat(shapeTime).toFixed(1),
                        currentWidth = parseFloat($('input[name="' + widthInputName + '"]').val()),
                        currentHeight = parseFloat($('input[name="' + heightInputName + '"]').val()),
                        estimateTime = parseFloat(parseFloat(currentWidth * currentHeight).toFixed(0) * currentTime).toFixed(0),
                        finalPrice = parseFloat(currentWidth * currentHeight) * 10,
                        calculatedPrice = Math.ceil(selectedPrice * finalPrice), minEstimateTime = 30,
                        maxEstimatetime = 180;


                    // console.log(selectedPrice);

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

                    changeCartPrice(calculatedPrice, 0);
                });

                $('input[name="' + widthInputName + '"]').trigger("input");
            }
        });


        $('.formula-text').text('base price x custom size price = Final price');

        return undefined;
    });

    function changeCartPrice(newBasePrice = 0, newAttrPrice = 0) {
        magic.cart.price.base = newBasePrice;
        magic.cart.price.attr = newAttrPrice;
        magic.cart.display();
    }

    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'), results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
});