/**
 * Payplug - https://www.payplug.com/
 * Copyright © Payplug. All rights reserved.
 * See LICENSE for license details.
 * 
 * Fallback configuration (only for Tailwind V3)
 */

const oneyColor = '#81BC00';

module.exports = {
  theme: {
    extend: {
      colors: {
        oney: {
          'DEFAULT': oneyColor,
          lighter: '#ECF5D9',
          darker: '#2F2930',
          error: '#2F2930'
        },
        payment: {
          error: '#E91932'
        },
        standard: {
          error: '#E91932'
        },
        payplug: {
          'DEFAULT': '#212225'
        }
      },
      borderColor: {
        oney: {
          'DEFAULT': oneyColor,
          lighter: '#DCE0E8'
        }
      },
      width: {
        'payment-card': '33px',
      },
      height: {
        'payment-card': '22px',
      }
    }
  },
  content: [
      '../layout/**/*.xml',
      '../templates/**/*.phtml'
  ]
}
