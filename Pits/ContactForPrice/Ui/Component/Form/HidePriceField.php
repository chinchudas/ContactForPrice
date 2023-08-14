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

namespace Pits\ContactForPrice\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Form\Field;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;

class HidePriceField extends Field
{
    /**
     * configHelper to get helper class
     *
     * @var mixed
     */
    private $configHelper;
    
    /**
     * Constructor function
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ConfigHelper $configHelper
     * @param array $components
     * @param array $data
     * @return void
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ConfigHelper $configHelper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->configHelper=$configHelper;
    }

    /**
     * Function to get Configuration values
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        $config = parent::getConfiguration();
        if ($this->configHelper->isModuleEnabled()) {
            $config['disabled']=(bool) false;
        } else {
            $config['disabled']=(bool) true;
        }
        return $config;
    }
}
