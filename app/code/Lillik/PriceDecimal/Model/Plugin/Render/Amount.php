<?php namespace Lillik\PriceDecimal\Model\Plugin\Render;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class Amount
{
  public function beforeFormatCurrency(
    $subject,
    $amount,
    $includeContainer = true,
    $precision = PriceCurrencyInterface::DEFAULT_PRECISION
  ) {
    return [$amount, $includeContainer, 13];
  }
}
?>