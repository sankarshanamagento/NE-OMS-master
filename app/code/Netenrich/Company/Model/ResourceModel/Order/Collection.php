<?php
/**
 * Customer Grid Collection
 *
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Company\Model\ResourceModel\Order;

class Collection extends \Magento\Customer\Model\ResourceModel\Customer\Collection
{
    /**
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->addNameToSelect()->addAttributeToSelect(
            'usertype'
        )->addAttributeToSelect(
            'organizationname'
        )->addAttributeToSelect(
            'email'
        )->addAttributeToSelect(
            'created_at'
        )->addAttributeToSelect(
            'store_id'
        )->addFieldToFilter(
  'group_id', array('neq' => 1)
  )->joinAttribute(
            'billing_postcode',
            'customer_address/postcode',
            'default_billing',
            null,
            'left'
        )->joinAttribute(
            'billing_city',
            'customer_address/city',
            'default_billing',
            null,
            'left'
        )->joinAttribute(
            'billing_telephone',
            'customer_address/telephone',
            'default_billing',
            null,
            'left'
        )->joinAttribute(
            'billing_regione',
            'customer_address/region',
            'default_billing',
            null,
            'left'
        )->joinAttribute(
            'billing_country_id',
            'customer_address/country_id',
            'default_billing',
            null,
            'left'
        )->joinAttribute(
            'shipping_company',
            'customer_address/company',
            'default_shipping',
            null,
            'left'
        )->joinAttribute(
            'billing_company',
            'customer_address/company',
            'default_billing',
            null,
            'left'
        )

        ->joinField(
            'group_name',
            'customer_group',
            'customer_group_code',
            'customer_group_id=group_id',
            null,
            'left'
        )
        ->joinField(
            'store_name',
            'store',
            'name',
            'store_id=store_id',
            null,
            'left'
        )->joinField(
            'website_name',
            'store_website',
            'name',
            'website_id=website_id',
            null,
            'left'
        );
        return $this;
    }
}
