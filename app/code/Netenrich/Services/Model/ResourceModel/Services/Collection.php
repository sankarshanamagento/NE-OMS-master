<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Services\Model\ResourceModel\Services;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Services\Model\Services', 'Netenrich\Services\Model\ResourceModel\Services');
    }
}
