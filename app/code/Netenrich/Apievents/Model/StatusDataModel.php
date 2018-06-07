<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Apievents\Model;


class StatusDataModel extends \Magento\Framework\Model\AbstractModel
{
    const KEY_STATUS = 'status';
    const KEY_RESPONSE = 'response';



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
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData(self::KEY_STATUS, $status);
    }


 public function getResoponse()
    {
        return $this->_getData(self::KEY_RESPONSE);
    }


    /**
     * Set name
     *
     * @param string $response
     * @return $this
     */
    public function setResponse($response)
    {
        return $this->setData(self::KEY_RESPONSE, $response);
    }



}
