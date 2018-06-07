<?php

namespace Netenrich\Webhook\Model\ResourceModel;

/**
 * Company Resource Model
 */
class Webhook extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('netenrich_webhook', 'api_id');
    }
}
