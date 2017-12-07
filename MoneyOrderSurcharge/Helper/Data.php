<?php

namespace Distinctive\MoneyOrderSurcharge\Helper;


class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * Recipient fixed amount of custom payment config path
     */
    const CONFIG_PAYMENT_FEE = 'payment/checkmo/config/';

    /**
     * Total Code
     */
    const TOTAL_CODE = 'fee_amount';

    const MONEY_ORDER_PAYMENT_CODE = 'checkmo';

    /**
     * @var string|float
     */
    public $methodFee = NULL;

    /**
     * Constructor
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
        parent::__construct($context);
        $this->_getMethodFee();
    }

    /**
     * Retrieve Payment Method Fees from Store Config
     * @return int|float
     */
    protected function _getMethodFee()
    {

        if(is_null($this->methodFee))
        {
            $this->methodFee = $this->getConfig('fee');
        }
        return $this->methodFee;
    }

    /**
     * Retrieve Store Config
     * @param string $field
     * @return mixed|null
     */
    public function getConfig($field = '')
    {
        if($field)
        {
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            return $this->scopeConfig->getValue(self::CONFIG_PAYMENT_FEE . $field, $storeScope);
        }
        return NULL;
    }

    /**
     * Check if Extension is Enabled config
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getConfig('enabled');
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return bool
     */
    public function canApply(\Magento\Quote\Model\Quote $quote)
    {
        if($this->isEnabled())
        {
            if($quote->getPayment()->getMethod() == self::MONEY_ORDER_PAYMENT_CODE)
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return float|int
     */
    public function getFee(\Magento\Quote\Model\Quote $quote)
    {
        $fee     = $this->methodFee;
        $feeType = $this->getFeeType();
        if($feeType == \Magento\Shipping\Model\Carrier\AbstractCarrier::HANDLING_TYPE_FIXED)
        {
            return $fee;
        }
        else
        {
            $sum = $quote->getSubtotal();
            return ($sum * ($fee / 100));
        }
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getFeeType()
    {
        return $this->getConfig('fee_type');
    }

    /**
     * Retrieve Fee type from Store config (Percent or Fixed)
     * @return string
     */
    public function getFeeTitle()
    {
        return $this->getConfig('fee_title');
    }
}
