<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Magewire;

use Hyva\Checkout\Magewire\Main;
use Payplug\Payments\Api\Data\OrderPaymentInterface;

class Standard extends Main
{
    public function setAdditionalData(string $card_id)
    {
        $quote = $this->sessionCheckout->getQuote();
        $quote->getPayment()->setAdditionalInformation('payplug_payments_customer_card_id', $card_id);
        $quote->getPayment()->save();
    }

    public function setHostedFieldsAdditionalData(
        string $cardId,
        string $token,
        string $brand,
        bool $saveCard,
        string $cardHolder
    ): void {
        $payment = $this->sessionCheckout->getQuote()->getPayment();
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_CARD_ID_KEY, $cardId);
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_PAYMENT_KEY, true);
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_TOKEN_KEY, $token);
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_BRAND_KEY, $brand);
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_SAVE_CARD_KEY, $saveCard);
        $payment->setAdditionalInformation(OrderPaymentInterface::HF_CARD_HOLDER_KEY, $cardHolder);
        $payment->save();
    }
}
