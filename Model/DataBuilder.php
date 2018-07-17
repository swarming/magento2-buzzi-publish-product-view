<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\PublishProductView\Model;

use Magento\Framework\DataObject;

class DataBuilder
{
    const EVENT_TYPE = 'buzzi.ecommerce.product-view';

    /**
     * @var \Buzzi\Publish\Helper\DataBuilder\Base
     */
    private $dataBuilderBase;

    /**
     * @var \Buzzi\Publish\Helper\DataBuilder\Customer
     */
    private $dataBuilderCustomer;

    /**
     * @var \Buzzi\Publish\Helper\DataBuilder\Product
     */
    private $dataBuilderProduct;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventDispatcher;

    /**
     * @param \Buzzi\Publish\Helper\DataBuilder\Base $dataBuilderBase
     * @param \Buzzi\Publish\Helper\DataBuilder\Customer $dataBuilderCustomer
     * @param \Buzzi\Publish\Helper\DataBuilder\Product $dataBuilderProduct
     * @param \Magento\Framework\Event\ManagerInterface $eventDispatcher
     */
    public function __construct(
        \Buzzi\Publish\Helper\DataBuilder\Base $dataBuilderBase,
        \Buzzi\Publish\Helper\DataBuilder\Customer $dataBuilderCustomer,
        \Buzzi\Publish\Helper\DataBuilder\Product $dataBuilderProduct,
        \Magento\Framework\Event\ManagerInterface $eventDispatcher
    ) {
        $this->dataBuilderBase = $dataBuilderBase;
        $this->dataBuilderCustomer = $dataBuilderCustomer;
        $this->dataBuilderProduct = $dataBuilderProduct;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Customer\Model\Customer $customer
     * @param string|null $customerEmail
     * @return mixed[]
     */
    public function getPayload($product, $customer = null, $customerEmail = null)
    {
        $payload = $this->dataBuilderBase->initBaseData(self::EVENT_TYPE);

        $payload['customer'] = $customer ? $this->dataBuilderCustomer->getCustomerData($customer) : ['email' => $customerEmail];
        $payload['product'] = $this->dataBuilderProduct->getProductData($product);

        $transport = new DataObject(['product' => $product, 'payload' => $payload]);
        $this->eventDispatcher->dispatch('buzzi_publish_product_view_payload', ['transport' => $transport]);

        return (array)$transport->getData('payload');
    }
}
