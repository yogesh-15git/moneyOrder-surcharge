<?php

namespace Distinctive\MoneyOrderSurcharge\Controller\Checkout;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;

class Totals extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJson;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    public function __construct(
        Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Json\Helper\Data $helper,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    )
    {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_helper          = $helper;
        $this->_resultJson      = $resultJson;
        $this->quoteRepository  = $quoteRepository;
    }

    /**
     * Trigger to re-calculate the collect Totals
     */
    public function execute()
    {
        $response = [
            'errors'  => FALSE,
            'message' => 'Re-calculate successful.'
        ];
        try
        {
            $this->quoteRepository->get($this->_checkoutSession->getQuoteId());
            $quote = $this->_checkoutSession->getQuote();
            //Trigger to re-calculate totals
            $payment = $this->_helper->jsonDecode($this->getRequest()->getContent());
            $this->_checkoutSession->getQuote()->getPayment()->setMethod($payment['payment']);
            $this->quoteRepository->save($quote->collectTotals());
        }
        catch (\Exception $e)
        {
            $response = [
                'errors'  => TRUE,
                'message' => $e->getMessage()
            ];
        }

        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($response);
    }
}
