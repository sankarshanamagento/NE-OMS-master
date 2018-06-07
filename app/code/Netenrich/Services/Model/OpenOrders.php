<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Services\Model;

use Netenrich\Services\Api\OpenOrdersInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class OpenOrders implements OpenOrdersInterface
{




    protected $_orderCollectionFactory;
	protected $_customercollection;
	protected $customerFactory;
	protected $dataFactory;
    protected $currencycode;
    



    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Netenrich\Company\Model\ResourceModel\Salesorders\CollectionFactory $_orderCollectionFactory,
		\Magento\Framework\Pricing\Helper\Data   $currencycode,
		\Netenrich\Services\Api\Data\TestdataInterfaceFactory $dataFactory,
        \Magento\Sales\Api\Data\OrderInterface $order,
		\Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->_orderCollectionFactory = $_orderCollectionFactory;
		$this->customerFactory = $customerFactory;
        $this->order = $order;
		$this->dataFactory = $dataFactory;
		$this->currencycode = $currencycode;
	}





          public function openlistofids($divauserids)
          {

            
			
            
			

				


    



		    $page_object = $this->dataFactory->create();
			$customermodel= $this->customerFactory->create()->getCollection()->addAttributeToSelect('customer_id')->addFieldToFilter('divauserid',$divauserids);
		    if($customermodel->count()==0)
			 {

                       throw new AlreadyExistsException(__( 'No Such Entity id data is there'));
                       return $resp;
            }
			else {

								foreach($customermodel as $clients)
									{
										$cids[]=$clients->getId();
									}
							$collection = $this->_orderCollectionFactory->create();
							$collectioncount=$collection->addFieldToFilter('customer_id',['in'=>$cids]);
							
							if(isset($_GET['searchkey']))
			                 { 
						          $collections=$collectioncount->addFieldToFilter(
                                array('increment_id'),
                           array(
                                  array('like'=>'%'.$_GET['searchkey'].'%')
								));
							 }
							 
							 $collectioncount=$collection->addFieldToFilter('status','processing');
							$collections=$collectioncount->setOrder('increment_id','ASC');
							 

							if($collectioncount->getsize()>0)
							{

								foreach($collections as $distibutor)
								{

									$formatprice=number_format($distibutor->getGrandTotal(),2,'.','');
									$orderslis[]=array('orderid'=>$distibutor->getIncrementId(),'total'=>$formatprice,'code'=>$distibutor->getOrderCurrencyCode());
									//$orderslis[]=$distibutor->getIncrementId();
								} 

								$page_object->setStatus('Sucessfull');
								$page_object->setOrders($orderslis);
						    }
							  else
							{
						        $emtpty=array();
						        $page_object->setStatus('Sucessfull');
							    $page_object->setOrders($emtpty);
							}

						 return $page_object;



		  }
}
}
