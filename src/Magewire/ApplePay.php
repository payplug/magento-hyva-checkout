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
    public function getShipping()
    {
        return $this->sessionCheckout->getQuote();
    }
}
