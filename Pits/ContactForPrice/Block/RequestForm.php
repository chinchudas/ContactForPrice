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
use Magento\Framework\UrlInterface;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;

class RequestForm extends Template
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * RequestForm constructor.
     *
     * @param Template\Context $context
     * @param UrlInterface $urlBuilder
     * @param ConfigHelper $configHelper
     * @param array $data
     * @return void
     */
    public function __construct(
        Template\Context $context,
        UrlInterface $urlBuilder,
        ConfigHelper $configHelper,
        $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get price request form submit url
     *
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->urlBuilder->getUrl('requestprice/request');
    }

    /**
     * Return template html
     *
     * @return string
     */
    public function _toHtml()
    {
        if ($this->configHelper->isModuleEnabled() && !$this->configHelper->isCallForPriceEnabled()) {
            return parent::_toHtml();
        }

        return '';
    }
}
