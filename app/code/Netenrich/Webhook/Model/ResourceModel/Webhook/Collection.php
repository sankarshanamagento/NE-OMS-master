<?php

/**
 * Company Resource Collection
 */
namespace Netenrich\Webhook\Model\ResourceModel\Webhook;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Netenrich\Webhook\Model\Webhook', 'Netenrich\Webhook\Model\ResourceModel\Webhook');
    }
}
