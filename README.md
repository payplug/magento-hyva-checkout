# Payplug for Hyvä Checkout

Official compatibility module of [Magento 2 Payplug module](https://github.com/payplug/payplug-magento2/) for Hyvä Checkout.

> If you also use Hyvä Theme, you will need to install the specific [https://github.com/payplug/magento-hyva-theme](payplug/magento-hyva-theme) module.

## About Payplug

[Payplug](https://www.payplug.com/fr/) is a French omnichannel payment solution dedicated to small and medium-sized businesses and enabling the cashing of payments made via Visa, Mastercard, and CB credit cards. 15,000 merchants in Europe trust us daily.

Please refer to https://www.payplug.com/fr for more informations.

## Requirements

In order to work, this module requires a [Hyvä Checkout](https://www.hyva.io/hyva-checkout.html) licence.

Please refer to https://docs.hyva.io for more instructions.

## Installation

1 - Install the payplug-magento-hyva module via composer:

```bash
composer require payplug/payplug-magento-hyva-checkout
```

2 - Enable module:

```bash
bin/magento module:enable Payplug_PaymentsHyvaCheckout
bin/magento setup:upgrade
```

3 - Generate Tailwind CSS styles:

```bash
# Register module to Hyvä Tailwind config
bin/magento hyva:config:generate

# Replace path by the Hyvä default theme
cd vendor/hyva-themes/magento2-default-theme/web/tailwind/
# Or replace path by your Hyvä custom theme if you have one
cd path/to/project/app/design/frontend/Vendor/default/web/tailwind

# Switch to the good Node.js version
nvm use

# Install dependencies
nvm i

# Compile Tailwind CSS file
npm run build
```
