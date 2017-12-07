<?php

namespace Distinctive\MoneyOrderSurcharge\Model\Quote\Address\Total;

class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var string
     */
    protected $_code = 'fee';
    /**
     * @var \Distinctive\MoneyOrderSurcharge\Helper\Data
     */
    protected $_helperData;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;


    protected $_quoteValidator = null;

    protected $_structure;


    /**
     * Payment Fee constructor.
     * @param \Magento\Quote\Model\QuoteValidator          $quoteValidator
     * @param \Magento\Checkout\Model\Session              $checkoutSession
     * @param \Magento\Quote\Api\Data\PaymentInterface     $payment
     * @param \Distinctive\MoneyOrderSurcharge\Helper\Data $helperData
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\Data\PaymentInterface $payment,
        \Distinctive\MoneyOrderSurcharge\Helper\Data $helperData,
        \Magento\Framework\View\Layout\Data\Structure $structure
    )
    {
        $this->_quoteValidator  = $quoteValidator;
        $this->_helperData      = $helperData;
        $this->_checkoutSession = $checkoutSession;
        $this->_structure       = $structure;
    }

    /**
     * Collect totals process.
     *
     * @param \Magento\Quote\Model\Quote                          $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total            $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);

        if(!count($shippingAssignment->getItems()))
        {
            return $this;
        }

        $fee = 0;
        if($this->_helperData->canApply($quote))
        {
            $fee = $this->_helperData->getFee($quote);
        }

        if($fee == 0)
        {
            $this->_structure->unsetElement('checkmo_surcharge');
        }

        $quote->setFeeAmount($fee);
        $quote->setBaseFeeAmount($fee);

        $total->setFeeAmount($fee);
        $total->setBaseFeeAmount($fee);

        $total->setTotalAmount('fee_amount', $fee);
        $total->setBaseTotalAmount('base_fee_amount', $fee);

        $total->setGrandTotal($total->getGrandTotal() + $total->getFeeAmount());
        $total->setBaseGrandTotal($total->getBaseGrandTotal() + $total->getBaseFeeAmount());

        return $this;
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param \Magento\Quote\Model\Quote               $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        $result = [
            'code'  => $this->getCode(),
            'title' => $this->_helperData->getFeeTitle(),
            'value' => $total->getFeeAmount()
        ];
        return $result;
    }

    /**
     * Get Subtotal label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->_helperData->getFeeTitle();
    }
}