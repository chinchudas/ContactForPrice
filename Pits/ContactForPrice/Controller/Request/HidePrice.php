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

namespace Pits\ContactForPrice\Controller\Request;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Pits\ContactForPrice\Model\PriceLogic;
use Magento\Framework\Exception\LocalizedException;

class HidePrice extends Action
{
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var ConfigHelper
     */
    private $configHelper;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var PriceLogic
     */
    private $priceLogicModel;

    /**
     * Constructor.
     *
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param ConfigHelper $configHelper
     * @param ProductRepositoryInterface $productRepository
     * @param PriceLogic $priceLogicModel
     * @return void
     */
    public function __construct(
        Context     $context,
        JsonFactory $resultJsonFactory,
        ConfigHelper $configHelper,
        ProductRepositoryInterface $productRepository,
        PriceLogic $priceLogicModel
    ) {

        $this->resultJsonFactory = $resultJsonFactory;
        $this->configHelper = $configHelper;
        $this->productRepository = $productRepository;
        $this->priceLogicModel = $priceLogicModel;
        parent::__construct($context);
    }

    /**
     * Controller for hide price
     *
     * @return Json
     * @throws \Exception
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $data = $this->getDataForButton();
        $html = '<a id="contact_for_price" href="javascript:void(0);" class="request-price-button request-for-price"';
        $html .= 'product-sku="WP09">' . __("Request for Price") . '</a>';
        $response = [
            'product_sku'    => $data['product_sku'],
            'can_hide_price' => $data['can_hide_price']
        ];
        $result->setData($response);
        return $result;
    }

    /**
     * Get data for button template
     *
     * @return array
     */
    private function getDataForButton()
    {
        $productSku = '';
        $canHidePrice = false;
        try {
            $id = $this->getRequest()->getPostValue('id');
            $productRepo = $this->productRepository->getById($id);
            $isCallForPriceEnabled = $this->configHelper->isCallForPriceEnabled();
            if (!$isCallForPriceEnabled) {
                $productSku = $productRepo->getSku();
            }
            if ($this->priceLogicModel->canHidePriceForProduct($id)) {
                if ($this->configHelper->displayContactButton()) {
                    $canHidePrice = true;
                }
            }
            return [
                'product_sku'    => $productSku,
                'can_hide_price' => $canHidePrice
            ];

        } catch (\Exception $e) {
            throw new LocalizedException(__('%1', $e->getMessage()));
        }
    }
}
