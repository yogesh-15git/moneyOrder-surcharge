<?php

namespace Distinctive\MoneyOrderSurcharge\Model\Order\Total\Invoice;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $invoice->setFeeAmount(0);
        $invoice->setBaseFeeAmount(0);

        $feeAmount = $invoice->getOrder()->getFeeAmount();
        $invoice->setFeeAmount($feeAmount);

        $baseFeeAmount = $invoice->getOrder()->getBaseFeeAmount();
        $invoice->setBaseFeeAmount($baseFeeAmount);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $invoice->getFeeAmount());
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $invoice->getFeeAmount());

        return $this;
    }
}