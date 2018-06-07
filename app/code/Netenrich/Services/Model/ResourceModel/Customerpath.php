<?php

namespace Netenrich\Services\Model\ResourceModel;

/**
 * Company Resource Model
 */
class Companypath extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich_Organisation_Type', 'path_id');
    }
}
