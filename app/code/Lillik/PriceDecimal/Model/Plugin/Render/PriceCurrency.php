<?php
namespace Lillik\PriceDecimal\Model\Plugin\Render;

class PriceCurrency
{
  public function beforeConvertAndRound(
    $subject,
    $amount,
    $scope = null,
    $currency = null,
    $precision = \Magento\Directory\Model\PriceCurrency::DEFAULT_PRECISION
  ) {
    return [$amount, $scope, $currency, 13];
  }

  public function beforeFormat(
    $subject,
    $amount,
    $includeContainer = true,
    $precision = \Magento\Directory\Model\PriceCurrency::DEFAULT_PRECISION,
    $scope = null,
    $currency = null
  ) {
    return [$amount, $includeContainer, 13, $scope, $currency];
  }
}