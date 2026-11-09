<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Magewire;

use Hyva\Checkout\Magewire\Main;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Oney extends Main
{
    protected $listeners = [
        'billing_address_saved' => 'refresh',
        'billing_address_activated' => 'refresh',
        'billing_as_shipping_address_updated' => 'refresh',
        'shipping_address_saved' => 'refresh',
        'shipping_address_activated' => 'refresh',
        'shipping_method_selected' => 'refresh',
        'coupon_code_applied' => 'refresh',
        'coupon_code_revoked' => 'refresh',
    ];

    protected array $uncallables = ['getErrorMessage'];

    public function setOneyType($oney_type)
    {
        $quote = $this->sessionCheckout->getQuote();
        $quote->getPayment()->setAdditionalInformation(
            'payplug_payments_oney_option',
            $oney_type
        );
        $quote->getPayment()->save();
    }

    /**
     * Get the Oney eligibility error of the current cart, empty when eligible
     *
     * @param array $config
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getErrorMessage(array $config): string
    {
        $quote = $this->sessionCheckout->getQuote();
        $billingCountry = $quote->getBillingAddress()->getCountryId();
        $shippingCountry = $quote->isVirtual() ? null : $quote->getShippingAddress()->getCountryId();
        $country = $billingCountry ?: $shippingCountry;

        if ($billingCountry && $shippingCountry && $billingCountry !== $shippingCountry) {
            return (string) __('Shipping and billing adresses must be both in the same country.');
        }

        if ($country && isset($config['allowed_countries'])
            && !in_array($country, $config['allowed_countries'], true)
        ) {
            return (string) __('Unavailable for the specified country');
        }

        if (!empty($config['max_items']) && (float) $quote->getItemsQty() >= $config['max_items']) {
            return (string) __('To pay with Oney, your cart must contain less than %1 items.', $config['max_items']);
        }

        return '';
    }
}
