<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

namespace Payplug\PaymentsHyvaCheckout\Model\Magewire\Payment;

use Hyva\Checkout\Model\Magewire\Component\EvaluationResultFactory;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultInterface;
use Hyva\Checkout\Model\Magewire\Payment\AbstractPlaceOrderService;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\OrderRepositoryInterface;

class ApplepayPlaceOrderService extends AbstractPlaceOrderService
{
    /**
     * @param CartManagementInterface $cartManagement
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        CartManagementInterface $cartManagement,
        protected OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($cartManagement);
    }

    /**
     * @param Quote $quote
     * @param int|null $orderId
     * @return string
     */
    public function getRedirectUrl(Quote $quote, ?int $orderId = null): string
    {
        $order = $this->orderRepository->get($orderId);

        $checkoutUrl = $order->getPayment()->getAdditionalInformation('payment_url');
        if ($checkoutUrl) {
            return $checkoutUrl;
        }

        return parent::REDIRECT_PATH;
    }

    public function canRedirect(): bool
    {
        return false;
    }

    public function placeOrder(Quote $quote): int
    {
        return (int) $this->cartManagement->placeOrder($quote->getId(), $quote->getPayment());
    }

    public function evaluateCompletion(
        EvaluationResultFactory $resultFactory,
        ?int $orderId = null
    ): EvaluationResultInterface {
        if ($orderId) {
            return $resultFactory->createBatch()->push(
                $resultFactory->createExecutable('make:pay-plug:payment')
            );
        }

        return parent::evaluateCompletion($resultFactory, $orderId);
    }
}
