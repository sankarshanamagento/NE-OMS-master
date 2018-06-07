<?php

namespace Netenrich\Services\Model\ResourceModel;

/**
 * Company Resource Model
 */
class Services extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('netenrich_partnumbermap', 'part_id');
    }
}
