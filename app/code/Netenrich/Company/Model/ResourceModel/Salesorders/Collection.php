<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Company\Model\ResourceModel\salesorders;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Company\Model\Salesorders', 'Netenrich\Company\Model\ResourceModel\Salesorders');
    }
}
