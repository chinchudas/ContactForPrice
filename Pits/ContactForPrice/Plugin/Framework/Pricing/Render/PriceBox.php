<?php
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
 * @author PIT Solutions Pvt. Ltd.
 * @copyright Copyright (c) 2020 PIT Solutions AG. (www.pitsolutions.ch)
 * @license https://www.webshopextension.com/en/licence-agreement/
 */

namespace Pits\ContactForPrice\Plugin\Framework\Pricing\Render;

use Exception;
use Psr\Log\LoggerInterface;
use Pits\ContactForPrice\Model\PriceLogic;
use Magento\Framework\Pricing\SaleableInterface;
use Pits\ContactForPrice\Block\Button as ContactButton;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Magento\Framework\Pricing\Render\PriceBox as PriceBoxRenderer;

class PriceBox
{
    /**
     * @var ContactButton
     */
    private $contactButton;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var PriceLogic
     */
    private $priceLogicModel;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * PriceBox constructor.
     *
     * @param ContactButton $contactButton
     * @param ConfigHelper $configHelper
     * @param PriceLogic $priceLogicModel
     * @param LoggerInterface $logger
     * @return void
     */
    public function __construct(
        ContactButton $contactButton,
        ConfigHelper $configHelper,
        PriceLogic $priceLogicModel,
        LoggerInterface $logger
    ) {
        $this->contactButton = $contactButton;
        $this->configHelper = $configHelper;
        $this->priceLogicModel = $priceLogicModel;
        $this->logger = $logger;
    }

    /**
     * Replace price html with 'Request for price' button or empty string.
     *
     * @param PriceBoxRenderer $priceRenderer
     * @param string $result
     * @return string
     */
    public function afterToHtml(PriceBoxRenderer $priceRenderer, $result)
    {
        try {
            $priceTemplate = $priceRenderer->getTemplate();
            $saleableItem = $priceRenderer->getSaleableItem();
            $canHidePriceForProduct = $this->priceLogicModel->canHidePriceForProduct($saleableItem->getId());
            if ($canHidePriceForProduct && $this->isValidPriceTemplate($priceTemplate)) {
                if ($this->configHelper->displayContactButton()) {
                    $buttonData = $this->getDataForButtonTemplate($saleableItem);

                    return $this->contactButton
                        ->setData($buttonData)
                        ->setTemplate('Pits_ContactForPrice::button.phtml')
                        ->toHtml();
                }

            }
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return $result;
    }

    /**
     * Check if the current price template is a valid one
     *
     * @param string $template
     * @return bool|false|string
     */
    private function isValidPriceTemplate($template)
    {
        $priceTemplates = ['final_price.phtml', 'configured_price.phtml'];
        foreach ($priceTemplates as $priceTemplate) {
            if (strstr($template, $priceTemplate) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get data for button template
     *
     * @param SaleableInterface $saleableItem
     * @return array
     */
    private function getDataForButtonTemplate($saleableItem)
    {
        $productSku = '';
        $isCallForPriceEnabled = $this->configHelper->isCallForPriceEnabled();
        if (!$isCallForPriceEnabled) {
            $productSku = $saleableItem->getSku();
        }

        return [
            'label'             => $this->configHelper->getButtonLabel(),
            'product_sku'       => $productSku,
            'is_call_for_price' => $isCallForPriceEnabled,
            'telephone_number'  => $this->configHelper->getCallForPriceTelephoneNumber(),
        ];
    }
}
