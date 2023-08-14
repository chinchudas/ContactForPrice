<?php
/**
 * Pits ContactForPrice
 * NOTICE OF LICENSE
 * This source file is licenced under Webshop Extensions software license.
 * Once you have purchased the software with PIT Solutions AG or one of its
 * authorised resellers and provided that you comply with the conditions of this contract,
 * PIT Solutions AG grants you a non-exclusive license, unlimited in time for the usage of
 * the software in the manner of and for the purposes specified in the documentation according
 * to the subsequent regulations.
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this extension to newer versions in the future.
 *
 * @category Pits
 * @package Pits_CMSRule
 * @author PIT Solutions Pvt. Ltd.
 * @copyright Copyright (c) 2023 PIT Solutions AG. (www.pitsolutions.ch)
 * @license https://www.webshopextension.com/en/licence-agreement/
 */

namespace PITS\ContactForPrice\Cron;

use Exception;
use Magento\Framework\App\Area;
use Psr\Log\LoggerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Pits\ContactForPrice\Api\ContactRepositoryInterface;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Pits\ContactForPrice\Api\Data\ContactInterface;
use Pits\ContactForPrice\Model\ContactMethods;

/**
 * Class Send Mail
 * This class will be responsible for Sending email
 */
class SendMail
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var ContactRepositoryInterface
     */
    protected $_contactRepository;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ContactMethods
     */
    protected $contactMethods;

    /**
     * DisableStaticBlock Construct
     *
     * @param LoggerInterface $logger
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ContactRepositoryInterface $contactRepository
     * @param ConfigHelper $configHelper
     * @param TransportBuilder $transportBuilder
     * @param ContactMethods $contactMethods
     */
    public function __construct(
        LoggerInterface            $logger,
        SearchCriteriaBuilder      $searchCriteriaBuilder,
        ContactRepositoryInterface $contactRepository,
        ConfigHelper               $configHelper,
        TransportBuilder           $transportBuilder,
        ContactMethods             $contactMethods
    ) {
        $this->logger = $logger;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_contactRepository = $contactRepository;
        $this->configHelper = $configHelper;
        $this->transportBuilder = $transportBuilder;
        $this->contactMethods = $contactMethods;
    }

    /**
     * Send mail to user from table
     *
     * @return void
     */
    public function execute()
    {
        try {
            $contacts = $this->contactMethods->getContactsToSendMail();
            $store = $this->configHelper->getStoreId();
            $sender = [
                'email' => $this->configHelper->getStoreEmailSenderAddress(),
                'name' => $this->configHelper->getStoreEmailSenderName()
            ];
            $toEmail = $this->configHelper->getRecipientEmailAddress();
            if ($toEmail && $contacts) {
                foreach ($contacts as $contact) {
                    $this->transportBuilder
                        ->setTemplateIdentifier(ConfigHelper::EMAIL_TEMPLATE_ID)
                        ->setTemplateOptions(
                            [
                                'area' => Area::AREA_FRONTEND,
                                'store' => $store,
                            ]
                        )
                        ->setTemplateVars($contact->getData())
                        ->setFrom($sender)
                        ->addTo($toEmail)
                        ->getTransport()
                        ->sendMessage();
                    $this->contactMethods->updateStatus($contact->getData(ContactInterface::ENTITY_ID));
                }
            } else {
                $this->logger->error(__('Cannot send email. Recipient email address missing.'));
            }
        } catch (Exception $e) {
            $this->logger->error($e);
        }
    }
}
