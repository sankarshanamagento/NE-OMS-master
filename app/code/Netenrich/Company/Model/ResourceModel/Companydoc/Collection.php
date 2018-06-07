<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Company\Model\ResourceModel\Companydoc;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Company\Model\Companydoc', 'Netenrich\Company\Model\ResourceModel\Companydoc');
    }
}
