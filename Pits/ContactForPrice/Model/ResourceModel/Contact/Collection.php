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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer versions in the future.
 *
 * @category Pits
 * @package  Pits_ContactForPrice
 * @author   Pit Solutions Pvt. Ltd.
 * @copyright Copyright (c) 2023 PIT Solutions AG. (www.pitsolutions.ch)
 * @license https://www.webshopextension.com/en/licence-agreement/
 */
namespace Pits\ContactForPrice\Model\ResourceModel\Contact;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Pits\ContactForPrice\Model\Contact as Model;
use Pits\ContactForPrice\Model\ResourceModel\Contact as ResourceModel;

/**
 * Contact For Price model collection
 */
class Collection extends AbstractCollection
{
    /**
     * Init
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
