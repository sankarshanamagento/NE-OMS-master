<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\WebstoreslistInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class Webstoreslist implements WebstoreslistInterface
{




    protected $_orderCollectionFactory;
	protected $_customercollection;
	protected $customerFactory;
	protected $dataFactory;
    protected $currencycode;
	protected $_storeRepository;
    



    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Netenrich\Company\Model\ResourceModel\Salesorders\CollectionFactory $_orderCollectionFactory,
		\Magento\Framework\Pricing\Helper\Data   $currencycode,
		\Netenrich\Apievents\Api\Data\TestwebdataInterfaceFactory $dataFactory,
        \Magento\Sales\Api\Data\OrderInterface $order,
		\Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->_orderCollectionFactory = $_orderCollectionFactory;
		$this->customerFactory = $customerFactory;
        $this->order = $order;
		$this->dataFactory = $dataFactory;
		$this->currencycode = $currencycode;
	}





          public function getstorelist()
          {
			$page_object = $this->dataFactory->create();
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$storeManager = $objectManager->create('Magento\Store\Model\WebsiteRepository');
			$websiteGroups = $storeManager->getList();

			foreach ($websiteGroups as $group) {
			 //echo $group->getName();
			 //echo $group->getWebsiteId();
			 if($group->getWebsiteId()!=0)
			 { $weblist[]=array($group->getWebsiteId()=>$group->getName());}
			 
			}
			
			$page_object->setStatus('Sucessfull');
			$page_object->setOrders($weblist);
            return $page_object;
            
		  }
}

