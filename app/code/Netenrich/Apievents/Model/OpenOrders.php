<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\OpenOrdersInterface;
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
	protected $categoryCollectionFactory;
    



    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Netenrich\Company\Model\ResourceModel\Salesorders\CollectionFactory $_orderCollectionFactory,
		\Magento\Framework\Pricing\Helper\Data   $currencycode,
		\Netenrich\Apievents\Api\Data\TestdataInterfaceFactory $dataFactory,
        \Magento\Sales\Api\Data\OrderInterface $order,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->_orderCollectionFactory = $_orderCollectionFactory;
		$this->customerFactory = $customerFactory;
        $this->order = $order;
		$this->dataFactory = $dataFactory;
		$this->currencycode = $currencycode;
		$this->categoryCollectionFactory = $categoryCollectionFactory; 
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
									
									
									$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
			$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
			$connection = $resource->getConnection();
			$tableName = $resource->getTableName('sales_order_grid'); //gives table name with prefix
			 
			//Select Data from table
		   $sql = "Select entity_id FROM " . $tableName." where increment_id='".$distibutor->getIncrementId()."'";
			$result = $connection->fetchAll($sql);	
			
			foreach($result as $row=>$data)
			{
			 $id=$data['entity_id'];
			}

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $order = $objectManager->create('Magento\Sales\Model\Order')->load($id);
            $orderItems = $order->getAllItems();
            $qtylist=array();
			 foreach($orderItems as $item)
			{
                $productObject = $objectManager->get('Magento\Catalog\Model\Product');
                $product = $productObject->loadByAttribute('sku', $item->getSku());
                //$categories=$product->getCategoryIds();
				
			    
			  				
				 
			
			   /*if(count($categories)==2)
			   {
				   foreach($categories as $data=>$value)
				{
					
					$category = $objectManager->create('Magento\Catalog\Model\Category')->load($value);
						if($category->getLevel()==2)
					 {
						 $categoryid=$value;
					 }
					 else if($category->getLevel()==3)
					 {
						 $subcategoryid=$value;
					 }
				}
			   }
			   else if(count($categories)==1)
			   {
				   $category = $objectManager->create('Magento\Catalog\Model\Category')->load($categories[0]);
						if($category->getLevel()==2)
					 {
						 $categoryid=$categories[0];
						 $subcategoryid="";
					 }
					 else if($category->getLevel()==3)
					 {
						 $subcategoryid=$categories[0];
						 $categoryid=$category->getParentCategory()->getId();
					 }
			   } */
			   
			    
				$qtylist[]=array('qty'=>$item->getQtyOrdered(),'orderid'=>$distibutor->getIncrementId(),'sku'=>$item->getSku(),'partnumber'=>$product->getPartnumber());
									
			}  
			
			//$orderslis[]=array('orderid'=>$distibutor->getIncrementId(),'total'=>$formatprice,'code'=>$distibutor->getOrderCurrencyCode(),'items'=>$qtylist);
				$orderslis[]=array('orderid'=>$distibutor->getIncrementId(),'total'=>$formatprice,'code'=>$distibutor->getOrderCurrencyCode(),'items'=>$qtylist);					
								} 

								$page_object->setStatus('Sucessfull');
								$page_object->setOrders($orderslis);
								//$page_object->setItems($qtylist);
						    }
							  else
							{
						        $emtpty=array();
						        $page_object->setStatus('Sucessfull');
							    $page_object->setOrders($emtpty);
								$page_object->setItems($emtpty);
							}

						 return $page_object;



		  }
}
}
