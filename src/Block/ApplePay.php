<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

namespace Payplug\PaymentsHyvaCheckout\Block;

use Magento\Framework\View\Element\Template;
use Payplug\Payments\Model\Payment\ApplePay\ConfigProvider as Config;

class ApplePay extends Template
{
    public function __construct(
        Template\Context $context,
        private Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getConfig()
    {
        return $this->config->getConfig();
    }
}
