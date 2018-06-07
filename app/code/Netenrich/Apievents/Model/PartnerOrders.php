<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\PartnerOrdersInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class PartnerOrders implements PartnerOrdersInterface
{





    protected $_orderCollectionFactory;
	protected $_customercollection;
	protected $customerFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Netenrich\Company\Model\ResourceModel\Salesorders\CollectionFactory $_orderCollectionFactory,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
    \Magento\Sales\Api\Data\OrderInterface $order
    ) {
        $this->_orderCollectionFactory = $_orderCollectionFactory;
		$this->customerFactory = $customerFactory;
    $this->order = $order;
	}





          public function orderslists($divauserids)
          {

			/* @conditions for pagination params page size and pagenum */
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
			/* @conditions ends here for pagination params page size and pagenum */

			/* @conditions To check divauserid is there in magento customers or throws an error when api hits */

			$customercount= $this->customerFactory->create()->getCollection()->addFieldToFilter('divauserid',$divauserids);
			if($customercount->count()==0)
			 {
                throw new AlreadyExistsException(__( 'No Such Entity id data is there'));
             }
			else
			{ /* @ get collections of all clients for the  partner divauserid */

			$customermodel= $this->customerFactory->create()->getCollection()->addAttributeToSelect('customer_id')->addFieldToFilter('orgparentid',$divauserids);


			$cids= array();
			foreach($customermodel as $clients)
				{
					$cids[]=$clients->getId(); /*@ All cilent id are collecting in to array variable */
				}

			/*@ Collection of all orders for  partner   */
			$collection = $this->_orderCollectionFactory->create();
			$collectioncount=$collection->addFieldToFilter('customer_id',['in'=>$cids]);


			if(isset($_GET['sortkey']) && isset($_GET['order']))
			{

			   $collections=$collectioncount->setOrder($_GET['sortkey'],$_GET['order']);
			} else {
				$collections=$collectioncount->setOrder('increment_id','ASC');
			}

			if(isset($_GET['searchkey']))
			{ 
		         
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
			$collection=$collection->setCurPage($currentpage);
			$collection=$collectioncount->setPageSize($pagesize);
            }

			$orderlist= array();
			$orderlist[]=array('count'=>$collectioncount->getsize());
                 if($collectioncount->getsize()>0)
				{
					foreach($collection as $distibutor)
					{

						$reponselist[$distibutor->getIncrementId()]=array('increment_id'=>$distibutor->getIncrementId(),'store_name'=>$distibutor->getStoreName(),'billing_name'=>$distibutor->getBillingName(),'shipping_name'=>$distibutor->getShippingName(),'status'=>$distibutor->getStatus(),'base_grand_total'=>number_format($distibutor->getBaseGrandTotal(),2,'.',''),'grand_total'=>number_format($distibutor->getGrandTotal(),2,'.',''),'created_at'=>$distibutor->getCreatedAt(),'order_currency_code'=>$distibutor->getOrderCurrencyCode());

						$finalobject = (object) $reponselist;
					}
					 $orderlist[] = array('data'=>$finalobject);
				}
				else
				{
						$reponselist=array();
						$finalobject = (object) $reponselist;
						$orderlist[] = array('data'=>$finalobject);
				}

				return $orderlist;

			}
		  }


}
