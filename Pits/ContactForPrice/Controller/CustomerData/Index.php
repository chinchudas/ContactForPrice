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

namespace Pits\ContactForPrice\Controller\CustomerData;

use Exception;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Magento\Customer\Model\Session as CustomerSession;

class Index extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param JsonSerializer $jsonSerializer
     * @param LoggerInterface $logger
     * @param ConfigHelper $configHelper
     * @param CustomerSession $customerSession
     * @return void
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        JsonSerializer $jsonSerializer,
        LoggerInterface $logger,
        ConfigHelper $configHelper,
        CustomerSession $customerSession
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->logger = $logger;
        $this->configHelper = $configHelper;
        $this->customerSession = $customerSession;
    }

    /**
     * Get logged in customer data
     *
     * @return ResponseInterface|Json|Redirect|ResultInterface
     */
    public function execute()
    {
        $customerData = [];
        try {
            $request = $this->getRequest();
            if (!$request->isXmlHttpRequest()) {
                $redirectUrl = $this->configHelper->getBaseUrl();

                return $this->resultRedirectFactory->create()->setPath($redirectUrl);
            }

            if ($this->customerSession->isLoggedIn() && $customer = $this->customerSession->getCustomer()) {
                $billingAddress = $customer->getPrimaryBillingAddress();
                $customerData['customer_name'] = $customer->getFirstname() . ' ' . $customer->getLastname();
                $customerData['customer_email'] = $customer->getEmail();
                $customerData['customer_telephone'] = ($billingAddress) ? $billingAddress->getTelephone() : '';
            }
        } catch (Exception $e) {
            $this->logger->critical($e);
        }
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($this->jsonSerializer->serialize($customerData));
    }
}
