<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\ClientOrdersInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class ClientOrders implements ClientOrdersInterface
{





    protected $_orderCollectionFactory;
	protected $_customercollection;
	protected $customerFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Netenrich\Company\Model\ResourceModel\Salesorders\CollectionFactory $_orderCollectionFactory,

		\Magento\Customer\Model\CustomerFactory $customerFactory
    ) {
        $this->_orderCollectionFactory = $_orderCollectionFactory;
    		$this->customerFactory = $customerFactory;

	}





          public function listoforders($divauserids)
          {

		    if(!isset($_GET['pagesize']))
			{
				$pagesize=15;
			}
			  else
			{
				$pagesize=$_GET['pagesize'];
			}

			if(!isset($_GET['currentpage']))
			{
				$currentpage=1;
			}
			  else
			{
				$currentpage=$_GET['currentpage'];
			}

			



			
			$customermodel= $this->customerFactory->create()->getCollection()->addAttributeToSelect('customer_id')->addFieldToFilter('divauserid',$divauserids);
			if($customermodel->count()==0)
			 {

                       throw new AlreadyExistsException(__( 'No Such Entity id data is there'));
                       return $resp;
            }
			else {

			foreach($customermodel as $clients)
				{
					$cid[]=$clients->getId();
				}



			$collection = $this->_orderCollectionFactory->create();
			$collectioncount=$collection->addFieldToFilter('customer_id',['in'=>$cid]);

				if(isset($_GET['sortkey']) && isset($_GET['order']))
				{
					$collections=$collectioncount->setOrder($_GET['sortkey'],$_GET['order']);
				}
				else
				{
					$collections=$collectioncount->setOrder('increment_id','ASC');
				}


			if(isset($_GET['searchkey']))
			{
				$strkey=urldecode($_GET['searchkey']);
				$collections=$collection->addFieldToFilter(
                     array('increment_id','billing_name','shipping_name','status','base_grand_total','grand_total'),
                           array(
                                   array('like'=>'%'.$_GET['searchkey'].'%'),
								  array('like'=>'%'.$_GET['searchkey'].'%'),
								  array('like'=>'%'.$_GET['searchkey'].'%'),
								  array('like'=>'%'.$_GET['searchkey'].'%'),
								  array('like'=>'%'.$_GET['searchkey'].'%'),
								  array('like'=>'%'.$_GET['searchkey'].'%')
                                ));
			}

			if($pagesize!='All')
			{
			$collection=$collections->setCurPage($currentpage);
			$collection=$collection->setPageSize($pagesize);
            }


            $distibutors= array();
			$distibutors[]=array('count'=>$collectioncount->getsize());

			  if($collectioncount->getsize()>0)
				{
				foreach($collection as $distibutor)
				{

					$reponselist[$distibutor->getIncrementId()]=array('increment_id'=>$distibutor->getIncrementId(),'store_name'=>$distibutor->getStoreName(),'billing_name'=>$distibutor->getBillingName(),'shipping_name'=>$distibutor->getShippingName(),'status'=>$distibutor->getStatus(),'base_grand_total'=>number_format($distibutor->getBaseGrandTotal(),2,'.',''),'grand_total'=>number_format($distibutor->getGrandTotal(),2,'.',''),'created_at'=>$distibutor->getCreatedAt(),'order_currency_code'=>$distibutor->getOrderCurrencyCode());

					$finalobject = (object) $reponselist;
				}
				 $distibutors[] = array('data'=>$finalobject);
			    }
				else{
					     $reponselist=array();
						$finalobject = (object) $reponselist;
						$distibutors[] = array('data'=>$finalobject);
				}
			   return $distibutors;


		  }

		}
}
