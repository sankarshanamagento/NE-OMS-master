<?php
/**
 * Copyright © 2015 Netn. All rights reserved.
 */
namespace Netn\Distibutorproducts\Model\ResourceModel;

/**
 * Distibutorproducts resource
 */
class Distibutorproducts extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('distibutorproducts_distibutorproducts', 'id');
    }

  
}
