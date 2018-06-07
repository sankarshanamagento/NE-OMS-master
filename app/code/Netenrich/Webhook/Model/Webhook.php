<?php

namespace Netenrich\Webhook\Model;

/**
 * Company Model
 *
 * @method \Netenrich\Company\Model\Resource\Page _getResource()
 * @method \Netenrich\Company\Model\Resource\Page getResource()
 */
class Webhook extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Webhook\Model\ResourceModel\Webhook');
    }

}
