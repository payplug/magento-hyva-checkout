<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Payplug\PaymentsHyvaCheckout\Plugin;

use Payplug\PaymentsHyvaCheckout\Provider\Config as PayplugHyvaConfig;
use Payplug\Payments\Helper\Config;

class TrackHyvaVersion
{
    public function __construct(
        protected PayplugHyvaConfig $payplugHyvaConfig
    ) {
    }

    public function afterGetMagentoVersion(Config $subject, string $magentoVersion): string
    {
        return sprintf('%s) %s (%s',
            $magentoVersion,
            $this->payplugHyvaConfig->getHyvaModuleName(),
            $this->payplugHyvaConfig->getHyvaModuleVersion()
        );
    }
}
