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

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Cache\StateInterface;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\AbstractModel;
use Pits\ContactForPrice\Api\ContactRepositoryInterface;
use Pits\ContactForPrice\Api\Data\ContactInterface;
use Psr\Log\LoggerInterface;
use Exception;

/**
 * Contact For Price model file
 */
class ContactMethods extends AbstractModel
{

    /**
     * @var StateInterface
     */
    protected $_cacheState;

    /**
     * @var Config
     */
    protected $_resourceConfig;

    /**
     * @var ContactFactory
     */
    protected $_contactFactory;

    /**
     * @var TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var ContactRepositoryInterface
     */
    protected $_contactRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Construct function
     *
     * @param Config $resourceConfig
     * @param LoggerInterface $logger
     * @param StateInterface $cacheState
     * @param ContactFactory $_contactFactory
     * @param TypeListInterface $cacheTypeList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        Config $resourceConfig,
        LoggerInterface $logger,
        StateInterface $cacheState,
        ContactFactory $_contactFactory,
        TypeListInterface $cacheTypeList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ContactRepositoryInterface $contactRepository
    ) {
        $this->logger = $logger;
        $this->_cacheState = $cacheState;
        $this->_resourceConfig = $resourceConfig;
        $this->_contactFactory = $_contactFactory;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_contactRepository = $contactRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Save contact form details for cron
     *
     * @param array $data
     * @return bool
     */
    public function saveContactInfo(array $data)
    {
        try {
            $contactInfo = $this->_contactFactory->create();
            $contactInfo->setName($data['name']);
            $contactInfo->setEmail($data['email']);
            if ($data['telephone']) {
                $contactInfo->setTelephone($data['telephone']);
            }
            $contactInfo->setDescription($data['description']);
            $contactInfo->setSku($data['sku']);
            $this->_contactRepository->save($contactInfo);

            return true;
        } catch (Exception $e) {
            $this->logger->critical($e);
        }

        return false;
    }

    /**
     * Get Contact details with status 0
     *
     * @return array
     */
    public function getContactsToSendMail()
    {
        $this->searchCriteriaBuilder->addFilter(ContactInterface::STATUS, 0);
        $searchResult = $this->_contactRepository->getList($this->searchCriteriaBuilder->create());
        if ($searchResult->getTotalCount()) {
            return $searchResult->getItems();
        }

        return [];
    }

    /**
     * Update status after email
     *
     * @param int $entityId
     * @return void
     */
    public function updateStatus(int $entityId)
    {
        try {
            $updateStatus = $this->_contactRepository->getById($entityId);
            $updateStatus->setStatus(1);
            $this->_contactRepository->save($updateStatus);
        } catch (Exception $e) {
            $this->logger->critical($e);
        }
    }
}
