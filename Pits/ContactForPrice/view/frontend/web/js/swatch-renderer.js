/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
 define([
    'jquery',
    'mage/url',
    'jquery/ui',
    'jquery-ui-modules/widget'
], function ($, url) {
    'use strict';

    return function (SwatchRenderer) {
        $.widget('mage.SwatchRenderer', SwatchRenderer, {

            /** @inheritdoc */
            _OnClick: function ($this, widget) {
                this._super($this, widget);
                let productId = widget.getProductId();
                if (productId != null) {
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
            }
        });

        return $.mage.SwatchRenderer;
    };
});
