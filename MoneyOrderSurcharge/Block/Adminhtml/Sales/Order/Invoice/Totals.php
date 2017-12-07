<?php

namespace Distinctive\MoneyOrderSurcharge\Block\Adminhtml\Sales\Order\Invoice;

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

    /**
     * @return mixed
     */
    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    /**
     * Initialize payment fee totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if(!$this->getSource()->getFeeAmount()) {
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