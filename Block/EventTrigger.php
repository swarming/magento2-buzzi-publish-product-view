<?php
/**
 * Copyright © Swarming Technology, LLC. All rights reserved.
 */
namespace Buzzi\PublishProductView\Block;

use Buzzi\PublishProductView\Model\DataBuilder;
use Magento\Catalog\Model\Product;

class EventTrigger extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Buzzi\Publish\Model\Config\Events
     */
    private $configEvents;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Buzzi\Publish\Model\Config\Events $configEvents
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Buzzi\Publish\Model\Config\Events $configEvents,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->configEvents = $configEvents;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->configEvents->isEventEnabled(DataBuilder::EVENT_TYPE);
    }

    /**
     * @return string
     */
    public function getEventType()
    {
        return DataBuilder::EVENT_TYPE;
    }

    /**
     * @return string|null
     */
    public function getProductSku()
    {
        $product = $this->registry->registry('product');
        return $product instanceof Product
            ? $product->getSku()
            : null;
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return $this->isEnabled() && $this->getProductSku()
            ? parent::_toHtml()
            : '';
    }
}
