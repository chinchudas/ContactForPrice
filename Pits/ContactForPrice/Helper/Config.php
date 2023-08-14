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

namespace Pits\ContactForPrice\Helper;

use Exception;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\NoSuchEntityException;

class Config extends AbstractHelper
{
    /**
     * Module enable/disable config path
     */
    public const XML_PATH_MODULE_ENABLED = 'contact_for_price/general/enable';

    /**
     * Show contact button config path
     */
    public const XML_PATH_DISPLAY_CONTACT_BUTTON = 'contact_for_price/general/show_contact_button';

    /**
     * Custom button label config path
     */
    public const XML_PATH_CUSTOM_BUTTON_LABEL = 'contact_for_price/general/button_label';

    /**
     * Recepient email address config path
     */
    public const XML_PATH_EMAIL_ADDRESS = 'contact_for_price/general/email_address';

    /**
     * Sender email address config path
     */
    public const XML_PATH_SENDER_EMAIL_ID = 'trans_email/ident_support/email';

    /**
     * Sender name config path
     */
    public const XML_PATH_SENDER_EMAIL_NAME = 'trans_email/ident_support/name';

    /**
     * Call for price enabled config path
     */
    public const XML_PATH_CALL_FOR_PRICE_ENABLED = 'contact_for_price/general/call_for_price';

    /**
     * Call for price telephone number config path
     */
    public const XML_PATH_CALL_FOR_PRICE_TELEPHONE_NUMBER = 'contact_for_price/general/call_for_price_telephone_number';

    /**
     * Email template
     */
    public const EMAIL_TEMPLATE_ID = 'contact_for_price_general_email_template';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Config constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @return void
     */
    public function __construct(
        Context               $context,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $configPath
     * @return mixed
     */
    private function getConfig($configPath)
    {
        try {
            return $this->scopeConfig->getValue($configPath, ScopeInterface::SCOPE_STORE, $this->getStoreId());
        } catch (Exception $e) {
            $this->_logger->error($e);
        };

        return '';
    }

    /**
     * Check if module is enabled
     *
     * @return int
     */
    public function isModuleEnabled()
    {
        return (int)$this->getConfig(self::XML_PATH_MODULE_ENABLED);
    }

    /**
     * Check if contact button should be displayed
     *
     * @return int
     */
    public function displayContactButton()
    {
        return (int)$this->getConfig(self::XML_PATH_DISPLAY_CONTACT_BUTTON);
    }

    /**
     * Get default/custom button label
     *
     * @return Phrase|mixed
     */
    public function getButtonLabel()
    {
        $customButtonLabel = $this->getConfig(self::XML_PATH_CUSTOM_BUTTON_LABEL);
        if ($customButtonLabel) {
            return $customButtonLabel;
        }
        if ($this->isCallForPriceEnabled()) {
            return __('Call for price');
        }

        return __('Request for price');
    }

    /**
     * Get current store id
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * Get base url
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get Email Address to send contact form after submit
     *
     * @return string
     */
    public function getRecipientEmailAddress()
    {
        return $this->getConfig(self::XML_PATH_EMAIL_ADDRESS);
    }

    /**
     * Get Store Email Sender Address
     *
     * @return string
     */
    public function getStoreEmailSenderAddress()
    {
        return $this->getConfig(self::XML_PATH_SENDER_EMAIL_ID);
    }

    /**
     * Get Store Email Sender Name
     *
     * @return string
     */
    public function getStoreEmailSenderName()
    {
        return $this->getConfig(self::XML_PATH_SENDER_EMAIL_NAME);
    }

    /**
     * Check if call for price enabled
     *
     * @return int
     */
    public function isCallForPriceEnabled()
    {
        return (int)$this->getConfig(self::XML_PATH_CALL_FOR_PRICE_ENABLED);
    }

    /**
     * Get call for price telephone number
     *
     * @return int
     */
    public function getCallForPriceTelephoneNumber()
    {
        return (int)$this->getConfig(self::XML_PATH_CALL_FOR_PRICE_TELEPHONE_NUMBER);
    }
}
