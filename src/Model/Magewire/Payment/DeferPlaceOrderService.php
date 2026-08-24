<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Model\Magewire\Payment;

use Hyva\Checkout\Model\Magewire\Component\EvaluationResultFactory;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultInterface;
use Hyva\Checkout\Model\Magewire\Payment\AbstractPlaceOrderService;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\PaymentInterfaceFactory;
use Magento\Quote\Model\Quote;
use Magento\Sales\Api\OrderRepositoryInterface;
use Payplug\Payments\Api\Data\OrderPaymentInterface;
use Payplug\Payments\Helper\Config;

class DeferPlaceOrderService extends AbstractPlaceOrderService
{
    private const HF_ADDITIONAL_DATA_KEYS = [
        OrderPaymentInterface::HF_CARD_ID_KEY,
        OrderPaymentInterface::HF_PAYMENT_KEY,
        OrderPaymentInterface::HF_TOKEN_KEY,
        OrderPaymentInterface::HF_BRAND_KEY,
        OrderPaymentInterface::HF_SAVE_CARD_KEY,
        OrderPaymentInterface::HF_CARD_HOLDER_KEY,
    ];

    protected $oneclick = false;

    public function __construct(
        protected CartManagementInterface $cartManagement,
        protected OrderRepositoryInterface $orderRepository,
        protected Config $payplugConfig,
        protected PaymentInterfaceFactory $paymentFactory
    ) {
        parent::__construct($cartManagement);
    }

    public function getRedirectUrl(Quote $quote, ?int $orderId = null): string
    {
        $order = $this->orderRepository->get($orderId);

        $checkoutUrl = $order->getPayment()->getAdditionalInformation('payment_url');
        if ($checkoutUrl) {
            return $checkoutUrl;
        } else {
            return parent::REDIRECT_PATH;
        }
    }

    public function canRedirect(): bool
    {
        if (($this->payplugConfig->isIntegrated() && $this->oneclick) || $this->isRedirect() || ($this->isPopup() && $this->oneclick)) {
            return true;
        }

        return false;
    }

    public function isRedirect(): bool
    {
        return !$this->isPopup() && !$this->payplugConfig->isIntegrated();
    }

    public function isPopup(): bool
    {
        return $this->payplugConfig->isEmbedded();
    }

    public function placeOrder(Quote $quote): int
    {
        $payment = $quote->getPayment();
        $additionalInformation = $payment->getAdditionalInformation();
        if (!empty($additionalInformation[OrderPaymentInterface::HF_CARD_ID_KEY])) {
            $this->oneclick = true;
        }

        $paymentMethod = $this->paymentFactory->create([
            'data' => [
                PaymentInterface::KEY_METHOD => $payment->getMethod(),
                PaymentInterface::KEY_ADDITIONAL_DATA => array_intersect_key(
                    $additionalInformation,
                    array_flip(self::HF_ADDITIONAL_DATA_KEYS)
                ),
            ],
        ]);

        return (int)$this->cartManagement->placeOrder($quote->getId(), $paymentMethod);
    }

    public function evaluateCompletion(EvaluationResultFactory $resultFactory, ?int $orderId = null): EvaluationResultInterface
    {

        if (!$this->payplugConfig->isIntegrated() || $this->oneclick) {
            return parent::evaluateCompletion($resultFactory, $orderId);
        }

        // The order ID is required and will only be passed in if the order was created.
        if ($orderId) {

            // Best practice is always to create a batch to let others inject more if required.
            return $resultFactory->createBatch()->push($resultFactory->createExecutable('make:pay-plug:payment'));
        }

        // Just let the abstraction layer dispatch a success result.
        return parent::evaluateCompletion($resultFactory, $orderId);
    }
}
