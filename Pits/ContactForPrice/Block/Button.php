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
namespace Pits\ContactForPrice\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;

class Button extends Template
{
    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * Construct
     *
     * @param Context $context
     * @param ConfigHelper $configHelper
     * @return void
     */
    public function __construct(
        Context $context,
        ConfigHelper $configHelper
    ) {
        $this->configHelper = $configHelper;
        parent::__construct($context);
    }

    /**
     * Get Button Label
     *
     * @return string
     */
    public function getButtonLabel()
    {
        return $this->configHelper->getButtonLabel();
    }

    /**
     * Is call for price
     *
     * @return bool
     */
    public function getIsCall()
    {
        return $this->configHelper->isCallForPriceEnabled();
    }

    /**
     * Get Telphone Number
     *
     * @return string
     */
    public function getCallForPriceTelephoneNumber()
    {
        return $this->configHelper->getCallForPriceTelephoneNumber();
    }
}
