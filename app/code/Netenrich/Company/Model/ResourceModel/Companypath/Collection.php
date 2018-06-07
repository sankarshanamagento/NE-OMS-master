<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Company\Model\ResourceModel\Companypath;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Company\Model\Companypath', 'Netenrich\Company\Model\ResourceModel\Companypath');
    }
}
