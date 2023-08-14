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
namespace Pits\ContactForPrice\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Pits\ContactForPrice\Api\Data\ContactInterface;
use Pits\ContactForPrice\Model\ResourceModel\Contact as ResourceModel;

/**
 * Contact For Price model file
 */
class Contact extends AbstractModel implements ContactInterface, IdentityInterface
{
    /**
     * Constant
     */
    const CACHE_TAG = 'contact_table';

    /**
     * Init
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get entity ID
     *
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity ID
     *
     * @param $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get Name
     *
     * @return int
     */
    public function getName() : string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set Name
     *
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get Email ID
     *
     * @return string
     */
    public function getEmail() : string
    {
        return $this->getData(self::EMAIL);
    }

    /**
     * Set Email ID
     *
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        return $this->setData(self::EMAIL, $email);
    }

    /**
     * Get Telephone
     *
     * @return string
     */
    public function getTelephone(): string
    {
        return $this->getData(self::TELEPHONE);
    }

    /**
     * Set Telephone
     *
     * @param $telephone
     * @return $this
     */
    public function setTelephone($telephone)
    {
        return $this->setData(self::TELEPHONE, $telephone);
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set Description
     *
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get SKU
     *
     * @return string
     */
    public function getSku(): string
    {
        return $this->getData(self::SKU);
    }

    /**
     * Set SKU
     *
     * @param $sku
     * @return $this
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get Status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->getData(self::SKU);
    }

    /**
     * Set Status
     *
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
