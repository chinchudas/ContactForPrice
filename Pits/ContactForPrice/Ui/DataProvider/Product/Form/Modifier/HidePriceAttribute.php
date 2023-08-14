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

namespace Pits\ContactForPrice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\Stdlib\ArrayManager;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Pits\ContactForPrice\Setup\Patch\Data\AddProductAttribute;

class HidePriceAttribute extends AbstractModifier
{
    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @param ArrayManager $arrayManager
     * @param ConfigHelper $configHelper
     * @return void
     */
    public function __construct(
        ArrayManager $arrayManager,
        ConfigHelper $configHelper
    ) {
        $this->arrayManager = $arrayManager;
        $this->configHelper=$configHelper;
    }

    /**
     * Function modifyData
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Function modifyMeta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $attribute = (AddProductAttribute::HIDE_PRICE_ATTRIBUTE_CODE);
        $path = $this->arrayManager->findPath($attribute, $meta, null, 'children');
        if ($this->configHelper->isModuleEnabled()) {
            $meta = $this->arrayManager->set(
                "{$path}/arguments/data/config/disabled",
                $meta,
                false
            );
        } else {
            $meta = $this->arrayManager->set(
                "{$path}/arguments/data/config/disabled",
                $meta,
                true
            );
        }

        return $meta;
    }
}
