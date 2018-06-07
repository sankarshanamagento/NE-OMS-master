<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CustomerStatusInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class CustomerStatus implements CustomerStatusInterface
{





    protected $companyFactory;
    protected $companypathFactory;
    public function __construct(
\Magento\Framework\App\Action\Context $context,

\Magento\Indexer\Model\IndexerFactory $indexerFactory,
\Magento\Customer\Model\CustomerFactory $customerFactory,
\Magento\Customer\Model\AddressFactory $addressFactory



)
  {

      $this->customerFactory = $customerFactory;
      $this->addressFactory = $addressFactory;
      $this->_indexerFactory = $indexerFactory;
  }



          public function statusCustomer($statusid=null,$divauserids)
          {
            if($statusid==0 || $statusid==1)
              {
                try{
             foreach ($divauserids as $divaid)
                   {

                 $customercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
                   foreach ($customercollection as $pd)
                    {
                     $entityid=$pd->getEntityId();
                     $customerstatus=$this->customerFactory->create()->load($entityid);
                     $customerstatus->setOrgcustomerstatus($statusid);
                     $customerstatus->save();
                   }



                  }
                  $resp[]= array('response' => 'success');
                  $idx = $this->_indexerFactory->create()->load("customer_grid");
                  $idx->reindexRow("customer_grid");
                 return  $resp;

                    }
                    catch(\Exception $e){
                    return $e;}
                  }
                    else {
                        $resp[]= array('response' => 'invalid statusid');
                      return $resp;
                    }
          }
}
