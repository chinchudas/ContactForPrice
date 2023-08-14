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

namespace Pits\ContactForPrice\Model;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Pits\ContactForPrice\Helper\Config as ConfigHelper;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Catalog\Model\ProductRepository;
use Exception;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\LocalizedException;

class PriceLogic extends AbstractModel
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * PriceLogic constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param LoggerInterface $logger
     * @param ConfigHelper $configHelper
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     * @return void
     */
    public function __construct(
        Context $context,
        Registry $registry,
        LoggerInterface $logger,
        ConfigHelper $configHelper,
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->logger = $logger;
        $this->configHelper = $configHelper;
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Check if product price can be hidden
     *
     * @param int $productId
     * @return bool
     */
    public function canHidePriceForProduct($productId)
    {
        $product = $this->getProductById($productId);
        if (($product && $this->configHelper->isModuleEnabled())
            && ((int)$product->getHidePrice()
                || $this->canHidePriceForProductCategory($product))) {
            return true;
        }

        return false;
    }

    /**
     * Get product visiblity
     *
     * @param int $productId
     * @return int
     */
    public function canHidePriceVisibility($productId)
    {
        try {
            $product = $this->getProductById($productId);
            return $product->getVisibility();
        } catch (\Exception $e) {
            throw new LocalizedException(__($e->getMessage));
        }
    }

    /**
     * Check if product price can be hidden based on product category setting
     *
     * @param Product $product
     * @return bool
     */
    private function canHidePriceForProductCategory($product)
    {
        $productCategoryIds = $product->getCategoryIds();
        foreach ($productCategoryIds as $categoryId) {
            $category = $this->getCategoryById($categoryId);
            if ($category && $category->getHidePrice()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get product by product id
     *
     * @param int $productId
     * @return ProductInterface|mixed|null
     */
    private function getProductById($productId)
    {
        try {
            return $this->productRepository->getById($productId, false, $this->configHelper->getStoreId());
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return null;
    }

    /**
     * Get category by category id
     *
     * @param int $categoryId
     * @return CategoryInterface|mixed|null
     */
    private function getCategoryById($categoryId)
    {
        try {
            return $this->categoryRepository->get($categoryId, $this->configHelper->getStoreId());
        } catch (Exception $e) {
            $this->logger->info($e->getMessage());
        }

        return null;
    }
}
