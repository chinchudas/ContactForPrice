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

namespace Pits\ContactForPrice\Block\Wishlist\Customer\Wishlist;

use Magento\Wishlist\Block\Customer\Wishlist\Button as WishListButton;

class Button extends WishListButton
{
    /**
     * Check if all the products from wishlist can be added to cart
     *
     * @return bool
     */
    public function canAddAllToCart()
    {
        $wishList = $this->getWishlist();
        foreach ($wishList->getItemCollection() as $item) {
            if (!$item->getProduct()->isSaleable()) {
                return false;
            }
        }

        return true;
    }
}
