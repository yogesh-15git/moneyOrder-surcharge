<?php

namespace Distinctive\MoneyOrderSurcharge\Block\Adminhtml\Sales\Order\Creditmemo;

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
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if(!$this->getSource()->getFeeAmount())
        {
            return $this;
        }
        $fee = new \Magento\Framework\DataObject(
            [
                'code'   => 'fee',
                'strong' => FALSE,
                'value'  => $this->getSource()->getFeeAmount(),
                'label'  => $this->_helper->getFeeTitle(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}