<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Services\Model;


class Testmodel extends \Magento\Framework\Model\AbstractModel 
{
    const KEY_STATUS = 'name';
   
   


     public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }


    public function getStatus()
    {
        return $this->_getData(self::KEY_STATUS);
    }


    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setStatus($name)
    {
        return $this->setData(self::KEY_STATUS, $name);
    }


 



}