<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Magewire;

use Hyva\Checkout\Magewire\Main;

class Standard extends Main
{
    public function setAditionalData($card_id)
    {
        $quote = $this->sessionCheckout->getQuote();
        $quote->getPayment()->setAdditionalInformation('payplug_payments_customer_card_id', $card_id);
        $quote->getPayment()->save();
    }

    public function setHostedFieldsAdditionalData(
        string $token,
        string $brand,
        bool $saveCard,
        string $cardHolder
    ): void {
        $payment = $this->sessionCheckout->getQuote()->getPayment();
        $payment->unsAdditionalInformation('payplug_payments_customer_card_id');
        $payment->setAdditionalInformation('payplug_hosted_fields_payment', true);
        $payment->setAdditionalInformation('payplug_hosted_fields_token', $token);
        $payment->setAdditionalInformation('payplug_hosted_fields_brand', $brand);
        $payment->setAdditionalInformation('payplug_hosted_fields_save_card', $saveCard);
        $payment->setAdditionalInformation('payplug_hosted_fields_card_holder', $cardHolder);
        $payment->save();
    }
}

