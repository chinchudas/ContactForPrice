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
namespace Pits\ContactForPrice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Event\Observer;
use Pits\ContactForPrice\Model\PriceLogic;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
 
class RestrictAddToCart implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

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
     * @param ManagerInterface $messageManager
     * @param PriceLogic $priceLogicModel
     * @param ConfigHelper $configHelper
     * @return void
     */
    public function __construct(
        ManagerInterface $messageManager,
        PriceLogic $priceLogicModel,
        ConfigHelper $configHelper
    ) {
        $this->messageManager = $messageManager;
        $this->priceLogicModel = $priceLogicModel;
        $this->configHelper= $configHelper;
    }
 
    /**
     * The event handler for checking any one of the products in the grouped list has enabled hide price feature.
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $params = $observer->getRequest()->getParams();
        $canHidePrice = false;
        $displayButton = $this->configHelper->displayContactButton();
        $isModuleEnabled = $this->configHelper->isModuleEnabled();
        if ($displayButton && $isModuleEnabled) {
            if (isset($params['selected_configurable_option'])) {
                $productId = $params['selected_configurable_option'];
                $canHidePrice = $this->priceLogicModel->canHidePriceForProduct($productId);
            }
            if (isset($params['super_group'])) {
                foreach ($params['super_group'] as $key => $items) {
                    if ($items > 0) {
                        $canHidePrice = $this->priceLogicModel->canHidePriceForProduct($key);
                        if ($canHidePrice) {
                            break;
                        }
                    }
                }
            }
            if ($canHidePrice) {
                $message = __('You can\'t add product to cart right now. ');
                $message .= __('Because one of the products have enabled hide price feature.');
                $this->messageManager->addErrorMessage($message);
                $observer->getRequest()->setParam('product', false);
                return $this;
            }
        }
        return $this;
    }
}
