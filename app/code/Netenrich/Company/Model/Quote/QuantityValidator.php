<?php namespace Netenrich\Company\Model\Quote;

class QuantityValidator extends \Magento\CatalogInventory\Model\Quote\Item\QuantityValidator
{
	
	public function validate(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
        $quoteItem = $observer->getEvent()->getItem();

        if (!$quoteItem ||
            !$quoteItem->getProductId() ||
            !$quoteItem->getQuote() ||
            $quoteItem->getQuote()->getIsSuperMode()
        ) {
            return;
        }

        $qty = $quoteItem->getQty();

        /** @var \Magento\CatalogInventory\Model\Stock\Item $stockItem */
        $stockItem = $this->stockRegistry->getStockItem(
            $quoteItem->getProduct()->getId(),
            $quoteItem->getProduct()->getStore()->getWebsiteId()
        );
        /* @var $stockItem \Magento\CatalogInventory\Api\Data\StockItemInterface */
        if (!$stockItem instanceof \Magento\CatalogInventory\Api\Data\StockItemInterface) {
            throw new \Magento\Framework\Exception\LocalizedException(__('The stock item for Product is not valid.'));
        }

        $parentStockItem = false;

        /**
         * Check if product in stock. For composite products check base (parent) item stock status
         */
        if ($quoteItem->getParentItem()) {
            $product = $quoteItem->getParentItem()->getProduct();
            $parentStockItem = $this->stockRegistry->getStockItem(
                $product->getId(),
                $product->getStore()->getWebsiteId()
            );
        }

        if ($stockItem) {
            if (!$stockItem->getIsInStock() || $parentStockItem && !$parentStockItem->getIsInStock()) {
                /* $quoteItem->addErrorInfo(
                    'cataloginventory',
                    \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                    __('This product is out of stock.')
                );
                $quoteItem->getQuote()->addErrorInfo(
                    'stock',
                    'cataloginventory',
                    \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                    __('Some of the products are out of stock.')
                );
                return; */
            } else {
                // Delete error from item and its quote, if it was set due to item out of stock
                //$this->_removeErrorsFromQuoteAndItem($quoteItem, \Magento\CatalogInventory\Helper\Data::ERROR_QTY);
            }
        }

        /**
         * Check item for options
         */
        if (($options = $quoteItem->getQtyOptions()) && $qty > 0) {
            $qty = $quoteItem->getProduct()->getTypeInstance()->prepareQuoteItemQty($qty, $quoteItem->getProduct());
            $quoteItem->setData('qty', $qty);
            if ($stockItem) {
                $result = $this->stockState->checkQtyIncrements(
                    $quoteItem->getProduct()->getId(),
                    $qty,
                    $quoteItem->getProduct()->getStore()->getWebsiteId()
                );
                if ($result->getHasError()) {
                    $quoteItem->addErrorInfo(
                        'cataloginventory',
                        \Magento\CatalogInventory\Helper\Data::ERROR_QTY_INCREMENTS,
                        $result->getMessage()
                    );

                    $quoteItem->getQuote()->addErrorInfo(
                        $result->getQuoteMessageIndex(),
                        'cataloginventory',
                        \Magento\CatalogInventory\Helper\Data::ERROR_QTY_INCREMENTS,
                        $result->getQuoteMessage()
                    );
                } else {
                    // Delete error from item and its quote, if it was set due to qty problems
                    $this->_removeErrorsFromQuoteAndItem(
                        $quoteItem,
                        \Magento\CatalogInventory\Helper\Data::ERROR_QTY_INCREMENTS
                    );
                }
            }

            foreach ($options as $option) {
                $result = $this->optionInitializer->initialize($option, $quoteItem, $qty);
                if ($result->getHasError()) {
                    $option->setHasError(true);

                    $quoteItem->addErrorInfo(
                        'cataloginventory',
                        \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                        $result->getMessage()
                    );

                    $quoteItem->getQuote()->addErrorInfo(
                        $result->getQuoteMessageIndex(),
                        'cataloginventory',
                        \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                        $result->getQuoteMessage()
                    );
                } else {
                    // Delete error from item and its quote, if it was set due to qty lack
                    $this->_removeErrorsFromQuoteAndItem($quoteItem, \Magento\CatalogInventory\Helper\Data::ERROR_QTY);
                }
            }
        } else {
            $result = $this->stockItemInitializer->initialize($stockItem, $quoteItem, $qty);
            if ($result->getHasError()) {
                $quoteItem->addErrorInfo(
                    'cataloginventory',
                    \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                    $result->getMessage()
                );

                $quoteItem->getQuote()->addErrorInfo(
                    $result->getQuoteMessageIndex(),
                    'cataloginventory',
                    \Magento\CatalogInventory\Helper\Data::ERROR_QTY,
                    $result->getQuoteMessage()
                );
            } else {
                // Delete error from item and its quote, if it was set due to qty lack
                $this->_removeErrorsFromQuoteAndItem($quoteItem, \Magento\CatalogInventory\Helper\Data::ERROR_QTY);
            }
        }
    }
}