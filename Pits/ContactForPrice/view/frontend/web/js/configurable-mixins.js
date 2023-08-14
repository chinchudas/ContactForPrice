define([
    'jquery',
    'mage/url',
    'underscore',
    'jquery-ui-modules/widget',
], function ($, url, _) {
    'use strict';
    return function (configurable) {
        $.widget('mage.configurable', configurable, {
            _configureElement: function (element) {
                this._super(element);
                let productId = this._getSimpleProductId(element);
                if (productId != undefined) {
                    const ajaxUrl = url.build('requestprice/request/hideprice');
                    $.ajax({
                        url: ajaxUrl,
                        type: "POST",
                        data: {id: productId},
                    }).done(function (data) {
                        if (data.can_hide_price) {
                            $('.product-options-bottom').hide();
                            $('.price-box').hide();
                            $('#contact_for_price').show();
                            $('#contact_for_price').attr('product-sku', data.product_sku);
                        } else {
                            $('.product-options-bottom').show();
                            $('.price-box').show();
                            $('#contact_for_price').hide();
                            $('#contact_for_price').removeAttr('product-sku');
                        }
                    });
                }
            },
        });
        return $.mage.configurable;
    };
});