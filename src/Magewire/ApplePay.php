<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Magewire;

use Hyva\Checkout\Magewire\Main;

class ApplePay extends Main
{
    protected $listeners = [
        'shipping_method_selected' => 'refresh',
        'shipping_address_saved' => 'refresh',
        'shipping_address_activated' => 'refresh',
        'coupon_code_applied' => 'refresh',
        'coupon_code_revoked' => 'refresh',
    ];

    public function getShipping()
    {
        return $this->sessionCheckout->getQuote();
    }
}
