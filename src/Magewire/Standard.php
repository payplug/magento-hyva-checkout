<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Magewire;

use Exception;
use Hyva\Checkout\Magewire\Main;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Payplug\Payments\Api\Data\OrderPaymentInterface;

class Standard extends Main
{
    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws Exception
     */
    public function setCardIdOnAdditionalData(string $card_id): void
    {
        $quote = $this->sessionCheckout->getQuote();
        $quote->getPayment()->setAdditionalInformation('payplug_payments_customer_card_id', $card_id);
        $quote->getPayment()->save();
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     * @throws Exception
     */
    public function setHostedFieldsAdditionalData(
        string $token,
        string $brand,
        bool $saveCard,
        string $cardHolder,
        ?string $cardId = null
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
