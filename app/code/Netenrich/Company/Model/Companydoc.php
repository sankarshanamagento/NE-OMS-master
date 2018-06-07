<?php

namespace Netenrich\Company\Model;

/**
 * Company Model
 *
 * @method \Netenrich\Company\Model\Resource\Page _getResource()
 * @method \Netenrich\Company\Model\Resource\Page getResource()
 */
class Companydoc extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Company\Model\ResourceModel\Companydoc');
    }

}
