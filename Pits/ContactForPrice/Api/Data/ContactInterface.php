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

namespace Pits\ContactForPrice\Api\Data;

/**
 * Interface ContactInterface
 *
 * @api
 */
interface ContactInterface
{
    /**
     * Pits Contact for price Table
     */
    const PITS_CONTACT_FOR_PRICE_TABLE = 'pits_contact_for_price';

    /**
     * Entity ID
     */
    const ENTITY_ID = 'entity_id';

    /**
     * Name
     */
    const NAME = 'name';

    /**
     * Email ID
     */
    const EMAIL = 'email';

    /**
     * Telephone
     */
    const TELEPHONE = 'telephone';

    /**
     * Description
     */
    const DESCRIPTION = 'description';

    /**
     * SKU
     */
    const SKU = 'sku';

    /**
     * Status
     */
    const STATUS = 'status';

    /**
     * Get entity ID
     *
     * @return int
     */
    public function getEntityId(): int;

    /**
     * Set entity ID
     *
     * @param $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * Get Name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set Name
     *
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * Get Email ID
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Set Email ID
     *
     * @param $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Get Telephone
     *
     * @return string
     */
    public function getTelephone(): string;

    /**
     * Set Telephone
     *
     * @param $telephone
     * @return $this
     */
    public function setTelephone($telephone);

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set Description
     *
     * @param $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get SKU
     *
     * @return string
     */
    public function getSku(): string;

    /**
     * Set SKU
     *
     * @param $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Get Status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set Status
     *
     * @param $status
     * @return $this
     */
    public function setStatus($status);
}
