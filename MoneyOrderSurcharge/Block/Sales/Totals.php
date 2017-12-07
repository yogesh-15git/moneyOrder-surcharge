<?php

namespace Distinctive\MoneyOrderSurcharge\Block\Sales;

use Magento\Framework\View\Element\Template;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var \Distinctive\MoneyOrderSurcharge\Helper\Data
     * */
    protected $_helper;

    /**
     * @param Template\Context                             $context
     * @param array                                        $data
     * @param \Distinctive\MoneyOrderSurcharge\Helper\Data $helper
     * */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \Distinctive\MoneyOrderSurcharge\Helper\Data $helper
    )
    {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return TRUE;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent        = $this->getParentBlock();
        $this->_order  = $parent->getOrder();
        $this->_source = $parent->getSource();
        if(!$this->_source->getFeeAmount())
        {
            return $this;
        }
        $fee = new \Magento\Framework\DataObject(
            [
                'code'   => 'fee',
                'strong' => FALSE,
                'value'  => $this->_source->getFeeAmount(),
                'label'  => $this->_helper->getFeeTitle(),
            ]
        );

        $parent->addTotal($fee, 'fee');
        return $this;
    }
}