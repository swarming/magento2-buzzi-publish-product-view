<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */

namespace Buzzi\PublishProductView\Model;

use Buzzi\Publish\Api\PackerInterface;

class Packer implements PackerInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Buzzi\PublishProductView\Model\DataBuilder
     */
    private $dataBuilder;

    /**
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Buzzi\PublishProductView\Model\DataBuilder $dataBuilder
     */
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Buzzi\PublishProductView\Model\DataBuilder $dataBuilder
    ) {
        $this->productRepository = $productRepository;
        $this->dataBuilder = $dataBuilder;
    }

    /**
     * @param array $inputData
     * @param \Magento\Customer\Model\Customer|null $customer
     * @param string|null $customerEmail
     * @return array|null
     */
    public function pack(array $inputData, $customer = null, $customerEmail = null)
    {
        if (empty($inputData['product_sku'])) {
            throw new \InvalidArgumentException('Product SKU is required.');
        }

        $product = $this->productRepository->get($inputData['product_sku']);
        return $customer || $customerEmail ? $this->dataBuilder->getPayload($product, $customer, $customerEmail) : null;
    }
}
