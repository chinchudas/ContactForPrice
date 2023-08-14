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
declare(strict_types=1);

namespace Pits\ContactForPrice\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Pits\ContactForPrice\Model\PriceLogic;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;

class ContactForPrice implements ArgumentInterface
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
     * Constructor
     *
     * @param PriceLogic $priceLogicModel
     * @param ConfigHelper $configHelper
     * @return void
     */
    public function __construct(
        PriceLogic $priceLogicModel,
        ConfigHelper $configHelper
    ) {
        $this->priceLogicModel = $priceLogicModel;
        $this->configHelper= $configHelper;
    }

    /**
     * To hide qty of grouped price
     *
     * @param int $productId
     * @return bool
     */
    public function canHideQty($productId)
    {
        $displayButton = $this->configHelper->displayContactButton();
        $isModuleEnabled = $this->configHelper->isModuleEnabled();
        $canHidePrice = $this->priceLogicModel->canHidePriceForProduct($productId);
        if ($displayButton && $isModuleEnabled && $canHidePrice) {
            return false;
        }
        return true;
    }
}

