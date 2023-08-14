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

namespace Pits\ContactForPrice\Plugin\Wishlist\Block\Customer;

use Pits\ContactForPrice\Model\PriceLogic;
use Magento\Wishlist\Block\Customer\Sidebar as WishlistSidebar;
use Magento\Catalog\Model\Product;

class Sidebar
{
    /**
     * @var PriceLogic
     */
    private $priceLogicModel;

    /**
     * Sidebar constructor.
     *
     * @param PriceLogic $priceLogicModel
     * @return void
     */
    public function __construct(
        PriceLogic $priceLogicModel
    ) {
        $this->priceLogicModel = $priceLogicModel;
    }

    /**
     * Remove price html for products in wishlist sidebar
     *
     * @param WishlistSidebar $subject
     * @param string $result
     * @param Product $product
     * @return string
     */
    public function afterGetProductPriceHtml(WishlistSidebar $subject, $result, Product $product)
    {
        if ($product && $this->priceLogicModel->canHidePriceForProduct($product->getId())) {
            return '';
        }

        return $result;
    }
}
