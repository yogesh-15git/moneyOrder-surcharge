<?php

namespace Distinctive\MoneyOrderSurcharge\Block\Adminhtml\Sales\Order;

use Magento\Framework\View\Element\Template;

class Totals extends \Magento\Framework\View\Element\Template
{
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
     * Retrieve current order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if(!$this->getSource()->getFeeAmount())
        {
            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code'  => 'fee',
                'value' => $this->getSource()->getFeeAmount(),
                'label' => $this->_helper->getFeeTitle(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}