<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Company\Model\Quote;

use Magento\Quote\Model\Quote as QuoteEntity;

class QuoteValidator extends \Magento\Quote\Model\QuoteValidator
{
  /**
    * Validate quote before submit
    *
    * @param Quote $quote
    * @return $this
    * @throws \Magento\Framework\Exception\LocalizedException
    */
   public function validateBeforeSubmit(QuoteEntity $quote)
   {
       if (!$quote->isVirtual()) {
           if ($quote->getShippingAddress()->validate() !== true) {
               throw new \Magento\Framework\Exception\LocalizedException(
                   __(
                       'Please check the shipping address information. %1',
                       implode(' ', $quote->getShippingAddress()->validate())
                   )
               );
           }
           $method = $quote->getShippingAddress()->getShippingMethod();
           $rate = $quote->getShippingAddress()->getShippingRateByCode($method);
        /* if (!$quote->isVirtual() && (!$method || !$rate)) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please vijay specify a shipping method.'));
          }*/
       }
       if ($quote->getBillingAddress()->validate() !== true) {
           throw new \Magento\Framework\Exception\LocalizedException(
               __(
                   'Please check the billing address information. %1',
                   implode(' ', $quote->getBillingAddress()->validate())
               )
           );
       }
       if (!$quote->getPayment()->getMethod()) {
           throw new \Magento\Framework\Exception\LocalizedException(__('Please select a valid payment method.'));
       }
       return $this;
   }
}
