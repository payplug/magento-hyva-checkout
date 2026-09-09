<?php
/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 */

namespace Payplug\PaymentsHyvaCheckout\Block;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Payplug\Payments\Api\Data\PaymentTokenInterface;
use Payplug\Payments\Helper\Card as CardHelper;
use Payplug\Payments\Helper\Config as PayplugConfig;
use Payplug\Payments\Model\Payment\Standard\ConfigProvider;
use Payplug\Payments\Service\GetHostedFieldsSavedCards;
use Throwable;

class Standard extends Template
{
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly CardHelper $cardHelper,
        private readonly ConfigProvider $configProvider,
        private readonly ResolverInterface $localeResolver,
        private readonly PayplugConfig $payplugConfig,
        private readonly GetHostedFieldsSavedCards $getHostedFieldsSavedCards,
        private readonly CheckoutSession $checkoutSession,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function getConfig(): array
    {
        return $this->configProvider->getConfig();
    }

    /**
     * Override the Hyva payment cards logo if we are on the IT local
     */
    protected function _prepareLayout(): self
    {
        parent::_prepareLayout();

        if ($this->localeResolver->getLocale() === 'it_IT') {
            $metadata = $this->getData('metadata');
            $metadata['icon']['src'] = 'Payplug_Payments::images/standard/payment-cards-it.svg';
            $metadata['icon']['attributes']['alt'] = 'Visa Mastercard Postepay';
            $this->setData('metadata', $metadata);
        }

        if ($this->canDisplayCardsLogo() === false) {
            $metadata = $this->getData('metadata');
            unset($metadata['icon']);
            $this->setData('metadata', $metadata);
        }

        return $this;
    }

    /**
     * Card scheme logos are only relevant on EUR quotes when Hosted Fields is active.
     *
     * @see \Payplug\Payments\Model\Payment\Standard\ConfigProvider for the Luma counterpart
     */
    public function canDisplayCardsLogo(): bool
    {
        try {
            $websiteId = (int) $this->_storeManager->getStore()->getWebsiteId();

            if ($this->payplugConfig->isHostedFieldsActive($websiteId) === false) {
                return true;
            }

            return $this->checkoutSession->getQuote()->getBaseCurrencyCode() === 'EUR';
        } catch (Throwable) {
            return true;
        }
    }

    /**
     * Get customer saved cards, normalized as ['id', 'brand', 'last4', 'exp_date']
     *
     * @see \Payplug\Payments\CustomerData\Cards for the Luma counterpart
     *
     * @return array[]
     */
    public function getPayplugCards(): array
    {
        $customerId = (int) $this->customerSession->getCustomer()->getId();

        if ($customerId === 0) {
            return [];
        }

        try {
            $websiteId = (int) $this->_storeManager->getStore()->getWebsiteId();
        } catch (Throwable) {
            return [];
        }

        if ($this->payplugConfig->isHostedFieldsActive($websiteId) === true) {
            return $this->getHostedFieldsCards($customerId);
        }

        return $this->getPayplugRetailCards($customerId);
    }

    /**
     * Format card expiration date
     */
    public function getFormattedExpDate(string $date): string
    {
        return $this->cardHelper->getFormattedExpDate($date);
    }

    /**
     * Build delete card url
     */
    public function getDeleteCardUrl(int $customerCardId): string
    {
        return $this->_urlBuilder->getUrl('payplug_payments/customer/cardDelete', [
            'customer_card_id' => $customerCardId
        ]);
    }

    /**
     * Get Payplug Retail saved cards
     *
     * @param int $customerId
     * @return array[]
     */
    private function getPayplugRetailCards(int $customerId): array
    {
        $cards = [];

        foreach ($this->cardHelper->getCardsByCustomer($customerId, true) as $card) {
            $cards[] = [
                'id' => (string) $card->getCustomerCardId(),
                'brand' => $card->getBrand(),
                'last4' => $card->getLastFour(),
                'exp_date' => $this->getFormattedExpDate((string) $card->getExpDate()),
            ];
        }

        return $cards;
    }

    /**
     * Get Hosted Fields saved cards, stored as Magento vault payment tokens
     *
     * @param int $customerId
     * @return array[]
     */
    private function getHostedFieldsCards(int $customerId): array
    {
        try {
            $storeId = (int) $this->_storeManager->getStore()->getId();
            $customerCards = $this->getHostedFieldsSavedCards->execute($customerId, $storeId);
        } catch (Throwable) {
            return [];
        }

        $cards = [];

        foreach ($customerCards as $customerCard) {
            $token = $customerCard[GetHostedFieldsSavedCards::TOKEN_OBJECT_KEY];
            $tokenDetails = $customerCard[GetHostedFieldsSavedCards::TOKEN_DETAILS_KEY];

            $brand = $tokenDetails[PaymentTokenInterface::DETAIL_BRAND] ?? null;
            $last4 = $tokenDetails[PaymentTokenInterface::MASKED_CC] ?? null;
            $expDate = $tokenDetails[PaymentTokenInterface::EXP_DATE] ?? null;

            if (empty($brand) || empty($last4) || empty($expDate)) {
                continue;
            }

            $cards[] = [
                'id' => (string) $token->getPublicHash(),
                'brand' => $brand,
                'last4' => $last4,
                'exp_date' => $expDate,
            ];
        }

        return $cards;
    }
}
