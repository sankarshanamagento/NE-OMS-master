<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Services\Model\ResourceModel\Customerpath;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Services\Model\Customerpath', 'Netenrich\Services\Model\ResourceModel\Customerpath');
    }
}
