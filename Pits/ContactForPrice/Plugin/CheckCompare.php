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
namespace Pits\ContactForPrice\Plugin;

use Magento\Catalog\Controller\Product\Compare\Add;
use Magento\Framework\Exception\NoSuchEntityException;
use Pits\ContactForPrice\Model\PriceLogic;
use Magento\Catalog\CustomerData\CompareProducts;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Response\RedirectInterface;

/**
 * Class CheckCompare
 */
class CheckCompare
{
    /**
     * @var PriceLogic
     */
    private $priceLogicModel;

    /**
     * @var CompareProducts
     */
    protected $compareProducts;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    /**
     * Construct function
     *
     * @param PriceLogic $priceLogicModel
     * @param CompareProducts $compareProducts ,
     * @param RequestInterface $request ,
     * @param ProductRepositoryInterface $productRepository ,
     * @param ManagerInterface $messageManager ,
     * @param ResultFactory $resultFactory ,
     * @param RedirectInterface $redirect
     */
    public function __construct(
        PriceLogic                 $priceLogicModel,
        CompareProducts            $compareProducts,
        RequestInterface           $request,
        ProductRepositoryInterface $productRepository,
        ManagerInterface           $messageManager,
        ResultFactory              $resultFactory,
        RedirectInterface          $redirect
    ) {
        $this->priceLogicModel = $priceLogicModel;
        $this->compareProducts = $compareProducts;
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
    }

    /**
     * Around function for hide price enabled products
     *
     * @param Add $subject
     * @param $proceed
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function aroundExecute(Add $subject, $proceed)
    {
        $resultRedirect = $this->resultFactory->create(
            ResultFactory::TYPE_REDIRECT
        );
        $productIdtoBeAdd = $this->request->getParam('product');
        $productRepository = $this->productRepository->getById($productIdtoBeAdd);
        if ($this->priceLogicModel->canHidePriceForProduct($productIdtoBeAdd)) {
            $this->messageManager->addWarningMessage(__('You cannot add product %1 in comparison list.',
                    $productRepository->getName())
            );
            $result = $resultRedirect->setUrl($this->redirect->getRefererUrl());
        } else {
            $result = $proceed();
        }

        return $result;
    }

}
