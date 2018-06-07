<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Netenrich\Company\Model\Sales\AdminOrder;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Model\Metadata\Form as CustomerForm;
use Magento\Quote\Model\Quote\Item;

/**
 * Order create model
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Create extends \Magento\Sales\Model\AdminOrder\Create
{

  /**
      * Validate quote data before order creation
      *
      * @return $this
      * @throws \Magento\Framework\Exception\LocalizedException
      * @SuppressWarnings(PHPMD.CyclomaticComplexity)
      * @SuppressWarnings(PHPMD.NPathComplexity)
      */
     protected function _validate()
     {
         $customerId = $this->getSession()->getCustomerId();
         if (is_null($customerId)) {
             throw new \Magento\Framework\Exception\LocalizedException(__('Please select a customer'));
         }

         if (!$this->getSession()->getStore()->getId()) {
             throw new \Magento\Framework\Exception\LocalizedException(__('Please select a store'));
         }
         $items = $this->getQuote()->getAllItems();

         if (count($items) == 0) {
             $this->_errors[] = __('Please specify order items.');
         }

         foreach ($items as $item) {
             $messages = $item->getMessage(false);
             if ($item->getHasError() && is_array($messages) && !empty($messages)) {
                 $this->_errors = array_merge($this->_errors, $messages);
             }
         }

        /* if ($this->getQuote()->isVirtual()) {
             if (!$this->getQuote()->getShippingAddress()->getShippingMethod()) {
                 $this->_errors[] = __('Please specify a shipping method.');
             }
         }*/

         if (!$this->getQuote()->getPayment()->getMethod()) {
             $this->_errors[] = __('Please specify a payment method.');
         } else {
             $method = $this->getQuote()->getPayment()->getMethodInstance();
             if (!$method->isAvailable($this->getQuote())) {
                 $this->_errors[] = __('This payment method is not available.');
             } else {
                 try {
                     $method->validate();
                 } catch (\Magento\Framework\Exception\LocalizedException $e) {
                     $this->_errors[] = $e->getMessage();
                 }
             }
         }
         if (!empty($this->_errors)) {
             foreach ($this->_errors as $error) {
                 $this->messageManager->addError($error);
             }
             throw new \Magento\Framework\Exception\LocalizedException(__('Validation is failed.'));
         }

         return $this;
     }

}
