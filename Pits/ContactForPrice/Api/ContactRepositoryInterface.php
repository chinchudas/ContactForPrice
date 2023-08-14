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

namespace Pits\ContactForPrice\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Pits\ContactForPrice\Api\Data\ContactInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface ContactRepositoryInterface
 *
 * @api
 */
interface ContactRepositoryInterface
{
    /**
     * Create or update a Contact.
     *
     * @param ContactInterface $contact
     * @return ContactInterface
     */
    public function save(ContactInterface $contact): ContactInterface;

    /**
     * Get a Contact by ID
     *
     * @param int $id
     * @return ContactInterface
     * @throws NoSuchEntityException If Contact detail with the specified ID does not exist.
     * @throws LocalizedException
     */
    public function getById(int $id): ContactInterface;

    /**
     * Retrieve Contact details which match a specified criteria.
     *
     * @param SearchCriteriaInterface $criteria
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Delete a Contact
     *
     * @param ContactInterface $contact
     * @return ContactInterface
     * @throws NoSuchEntityException If Car with the specified ID does not exist.
     * @throws LocalizedException
     */
    public function delete(ContactInterface $contact): ContactInterface;

    /**
     * Delete a Contact detail by ID
     *
     * @param int $id
     * @return ContactInterface
     * @throws NoSuchEntityException If Contact details with the specified ID does not exist.
     * @throws LocalizedException
     */
    public function deleteById(int $id): ContactInterface;
}
