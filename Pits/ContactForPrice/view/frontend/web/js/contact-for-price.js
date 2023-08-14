/**
 * Pits ContactForPrice
 *
 * NOTICE OF LICENSE
 *
 * This source file is licenced under Webshop Extensions software license.
 * Once you have purchased the software with PIT Solutions AG or one of its
 * authorised resellers and provided that you comply with the conditions of this contract,
 * PIT Solutions AG grants you a non-exclusive license, unlimited in time for the usage of
 * the software in the manner of and for the purposes specified in the documentation according
 * to the subsequent regulations.
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer versions in the future.
 *
 * @category Pits
 * @package Pits_ContactForPrice
 * @author PIT Solutions Pvt. Ltd.
 * @copyright Copyright (c) 2020 PIT Solutions AG. (www.pitsolutions.ch)
 * @license https://www.webshopextension.com/en/licence-agreement/
 */
require(['jquery', 'Magento_Ui/js/modal/modal', 'mage/url'], function ($, modal, url) {
    let modalOptions = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        clickableOverlay: false,
        buttons: [],
        opened: function () {
            loadCustomerData();
        }
    };
    let messageContainerElement = $('#contact-for-price-message');
    let formContainerElement = $('.form-content');
    let modalElement = $('#contact-for-price-modal');
    let requestPriceSkuElement = $('#request-price-sku');
    modal(modalOptions, modalElement);

    $(".request-for-price").on('click', function () {
        messageContainerElement.hide();
        formContainerElement.show();
        messageContainerElement.removeClass();
        messageContainerElement.html('');
        requestPriceSkuElement.val('');
        $('#request-description').val('');
        let productSku = $(this).attr('product-sku');
        requestPriceSkuElement.val(productSku);
        modalElement.modal("openModal");
    });
    let modalFormId = '#request-price-form';
    $("#submit-request").on('click', function (e) {
        e.preventDefault();
        let dataForm = $(modalFormId);
        if (dataForm.valid()) {
            let ajaxUrl = url.build('requestprice/request');
            let param = dataForm.serialize();
            let response = doAjax(ajaxUrl, param);
            if (response) {
                let iconHtml = '<a href="javascript:void(0);"></a>';
                let parsedResponse = $.parseJSON(response);
                if (parsedResponse.error) {
                    messageContainerElement.addClass('message-error error message');
                } else {
                    messageContainerElement.addClass('message-success success message');
                }
                messageContainerElement.html(iconHtml + ' ' + parsedResponse.message);
                formContainerElement.hide();
                messageContainerElement.show();
            }
        }
    });

    /**
     * Load customer data if logged in
     */
    function loadCustomerData() {
        let ajaxUrl = url.build('requestprice/customerdata');
        let customerData = doAjax(ajaxUrl, {});
        console.log(customerData);
        customerData = $.parseJSON(customerData);
        if (customerData) {
            $.each(customerData, function (key, value) {
                $(modalFormId + ' #' + key).val(value);
            })
        }
    }

    /**
     * Do ajax call
     *
     * @param ajaxUrl
     * @param data
     * @returns {{}}
     */
    function doAjax(ajaxUrl, data) {
        let ajaxResponse = {};
        $.ajax({
            showLoader: true,
            url: ajaxUrl,
            type: "POST",
            data: data,
            async: false
        }).done(function (response) {
            ajaxResponse = response;
        });

        return ajaxResponse;
    }
});
