# Changelog - Hyvä compatibility module for Payplug payments

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.2](https://github.com/payplug/magento-hyva-checkout/releases/tag/v4.0.2) - 2026-26-08

### Main feature
- Add configuration fallback for Tailwind V3

### Added
- Add configuration fallback for Tailwind V3 [#](https://github.com/payplug/magento-hyva-checkout/commit/1c175324fa9ca0fb07bdd6e3b83b28f5008efd8f)

## [4.0.1](https://github.com/payplug/magento-hyva-checkout/releases/tag/v4.0.1) - 2026-06-20

### Main feature
- Add Payplug module 4.7.2 version support

### Updated
- Dynamize PPRO payment methods [#754](https://github.com/payplug/magento-hyva-checkout/pull/18/changes/6a50dc493f1812d9c66a86b2f163aebeee852934)

### Fixed
- Fix dataset access console error [#779](https://github.com/payplug/magento-hyva-checkout/pull/18/changes/182c068db79e95939037d5c8ba75fa7374742fdc)

**[View diff](https://github.com/payplug/magento-hyva-checkout/compare/v4.0.0...v4.0.1)**

## [4.0.0](https://github.com/payplug/magento-hyva-checkout/releases/tag/v4.0.0) - 2026-06-01

> [!NOTE]
>
> The [previous module](https://github.com/payplug/magento-hyva-theme) including developments for both the theme and the checkout is now deprecated.
> Starting from this release, developments on this module now only include the Hyvä Theme compatibility developements.
> Hyvä Checkout compatibility developments are available on the [https://github.com/payplug/magento-hyva-checkout](payplug/magento-hyva-checkout) dedicated module.

### Main feature
- Add Payplug module 4.7.0 version support
- Add new payment methods : Bizum, Scalapay and Wero

### Added
- Add error message generic class [#715](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/3d10b7939d80607cc9729227c608fdb09b39d0f8)
- Add new payment methods : Bizum, Scalapay and Wero [#587](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/4a0f93a38eb28e490982c71dbec5dfe16b1905ac)
- Add url provider for external lib urls [#587](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/5bc3b5432dc7432f908c141392648926ad93d863)

### Changed
- Normalize payment methods render sizes [#715](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/c35420c74f44b6c37bd3167b155205c127b3eb22)
- Update Payplug module supported version [#587](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/943eb818b96be8214062d0314709f35b680bf4f9)

### Removed
- Remove residual Sofort and Giropay payment methods [#587](https://github.com/payplug/magento-hyva-checkout/pull/13/changes/334a98bdf054cca5b11bce3d31e3a87b80ba566a)
