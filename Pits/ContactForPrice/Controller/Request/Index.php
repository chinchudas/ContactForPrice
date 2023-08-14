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
 * @copyright Copyright (c) 2023 PIT Solutions AG. (www.pitsolutions.ch)
 * @license https://www.webshopextension.com/en/licence-agreement/
 */

namespace Pits\ContactForPrice\Controller\Request;

use Exception;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Psr\Log\LoggerInterface;
use Pits\ContactForPrice\Model\ContactMethods;

/**
 * Controller for form submit
 */
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
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var ContactMethods
     */
    protected $contactMethods;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param JsonSerializer $jsonSerializer
     * @param LoggerInterface $logger
     * @param ConfigHelper $configHelper
     * @param TransportBuilder $transportBuilder
     * @param ProductMetadataInterface $productMetadata
     * @param ContactMethods $contactMethods
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        JsonSerializer $jsonSerializer,
        LoggerInterface $logger,
        ConfigHelper $configHelper,
        TransportBuilder $transportBuilder,
        ProductMetadataInterface $productMetadata,
        ContactMethods $contactMethods
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->logger = $logger;
        $this->configHelper = $configHelper;
        $this->transportBuilder = $transportBuilder;
        $this->productMetadata = $productMetadata;
        $this->contactMethods = $contactMethods;
    }

    /**
     * Contact for price controller
     *
     * @return ResponseInterface|Json|Redirect|ResultInterface
     */
    public function execute()
    {
        $result = [];
        $error = false;
        $message = '';
        try {
            $request = $this->getRequest();
            $data = [
                'name'        => strip_tags($request->getParam('customer_name')),
                'telephone'   => strip_tags($request->getParam('customer_telephone')),
                'email'       => strip_tags($request->getParam('customer_email')),
                'description' => strip_tags($request->getParam('description')),
                'sku'         => strip_tags($request->getParam('sku')),
            ];
            if (!$request->isXmlHttpRequest()) {
                $redirectUrl = $this->configHelper->getBaseUrl();

                return $this->resultRedirectFactory->create()->setPath($redirectUrl);
            }
            $validationErrors = $this->validatedParams($data);
            if (empty($validationErrors)) {
                if ($this->contactMethods->saveContactInfo($data)) {
                    $message = __('Thank you for contacting us with your request. We\'ll respond to you very soon.');
                } else {
                    $error = true;
                    $message = __('Something went wrong while processing your request. Please try again later.');
                }
            } else {
                $error = true;
                $message = join('<br>', $validationErrors);
            }
        } catch (Exception $e) {
            $error = true;
            $this->logger->critical($e);
        }
        $result['error'] = $error;
        $result['message'] = $message;

        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($this->jsonSerializer->serialize($result));
    }

    /**
     * Validate field values
     *
     * @param array $data
     * @return array
     */
    private function validatedParams(array $data)
    {
        $errors = [];
        if (!$this->isValidField($data['name'])) {
            $errors[] = __('Please enter your name.');
        }
        if (!$this->isValidEmail($data['email'])) {
            $errors[] = __('Please enter your email address.');
        }
        if (!$this->isValidField($data['description'])) {
            $errors[] = __('Please enter the description.');
        }
        if (!$this->isValidField($data['sku'])) {
            $errors[] = __('Product sku is missing.');
        }

        return $errors;
    }

    /**
     * Check if field value is valid
     *
     * @param string $fieldValue
     * @return bool
     */
    private function isValidField(string $fieldValue)
    {
        if (trim($fieldValue)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if field value is valid email address
     *
     * @param string $fieldValue
     * @return bool
     */
    private function isValidEmail(string $fieldValue)
    {
        if (!filter_var(trim($fieldValue), FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
