<?php

namespace Netenrich\Company\Model\ResourceModel;

/**
 * Company Resource Model
 */
class Companydoc extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('netenrich_organisation_doc', 'docid');
    }
}
