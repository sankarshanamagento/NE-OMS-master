<?php
namespace Netenrich\Company\Model\Order;

class Freeshipping extends \Magento\Sales\Model\Order
{
	
	public function setShippingMethod($shippingMethod)
    {
        return $this->setData('shipping_method', 'freeshipping_freeshipping');
    }
}