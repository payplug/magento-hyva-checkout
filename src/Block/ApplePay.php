<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

namespace Payplug\PaymentsHyvaCheckout\Block;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\Information;
use Magento\Store\Model\ScopeInterface;
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

    /**
     * Get the merchant country code expected by the Apple Pay session request
     *
     * @return string
     */
    public function getMerchantCountryId(): string
    {
        return (string) (
            $this->_scopeConfig->getValue(Information::XML_PATH_STORE_INFO_COUNTRY_CODE, ScopeInterface::SCOPE_STORE)
            ?: $this->_scopeConfig->getValue(DirectoryHelper::XML_PATH_DEFAULT_COUNTRY, ScopeInterface::SCOPE_STORE)
        );
    }
}
