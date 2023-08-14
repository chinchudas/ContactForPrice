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

namespace Pits\ContactForPrice\Plugin;

use Pits\ContactForPrice\Model\PriceLogic;
use Magento\Catalog\Model\Product;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;

class HideAddToCartButton
{
    /**
     * @var PriceLogic
     */
    private $priceLogicModel;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * HideAddToCartButton constructor.
     *
     * @param PriceLogic $priceLogicModel
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        PriceLogic $priceLogicModel,
        ConfigHelper $configHelper
    ) {
        $this->priceLogicModel = $priceLogicModel;
        $this->configHelper = $configHelper;
    }

    /**
     * Hide 'Add To Cart' button in front-end.
     *
     * @param Product $productModel
     * @param array $result
     * @return array
     */
    public function afterIsSalable(Product $productModel, $result)
    {
        $visibility = $this->priceLogicModel->canHidePriceVisibility($productModel->getId());
        if ($this->priceLogicModel->canHidePriceForProduct($productModel->getId())) {
            if ($this->configHelper->displayContactButton() && $visibility != 1) {
                return [];
            }
        }
        
        return $result;
    }

    /**
     * Display Stock status label in front-end.
     *
     * @param Product $productModel
     * @param bool $result
     * @return bool
     */
    public function afterGetIsSalable(Product $productModel, $result)
    {
        if ($this->priceLogicModel->canHidePriceForProduct($productModel->getId()) && $productModel->isAvailable()) {
            return true;
        }

        return $result;
    }
}
