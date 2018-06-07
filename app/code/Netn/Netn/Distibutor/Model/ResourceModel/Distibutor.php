<?php
/**
 * Copyright © 2015 Netn. All rights reserved.
 */
namespace Netn\Distibutor\Model\ResourceModel;

/**
 * Distibutor resource
 */
class Distibutor extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('distibutor_distibutor', 'id');
    }

  
}
