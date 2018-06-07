<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CompanyStatusInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;

/**
 * Defines the implementaiton class of the Company service contract.
 */
class CompanyStatus implements CompanyStatusInterface
{





    protected $companyFactory;
    protected $companypathFactory;
    public function __construct(
\Magento\Framework\App\Action\Context $context,
\Netenrich\Company\Model\CompanyFactory $companyFactory,
\Magento\Framework\App\ResourceConnection $ResourceConnection,
\Magento\Customer\Model\CustomerFactory $customerFactory,
\Magento\Indexer\Model\IndexerFactory $indexerFactory,
\Magento\Customer\Model\AddressFactory $addressFactory



)
  {
      $this->companyFactory = $companyFactory;
      $this->ResourceConnection=$ResourceConnection;
      $this->customerFactory = $customerFactory;
      $this->addressFactory = $addressFactory;
      $this->_indexerFactory = $indexerFactory;
  }



          public function statusCompany($statusid=null,$divaids)
          {
            if($statusid==0 || $statusid==1)
              {

             foreach ($divaids as $divaid)
                   {
                    $update=$this->companyFactory->create();
                    $update->load($divaid,"divauniqueid");
                    $orgType=$update->getOrgTypeId();
                    $update->setStatus($statusid);
                    $update->save();


                    $companyCollectionInCustomer= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
                    foreach ($companyCollectionInCustomer as $pd)
                    {
                     $companyentityid=$pd->getEntityId();
                    }

                    $companyupdate=$this->customerFactory->create();
                    $companyupdate->load($companyentityid);
                    $companyupdate->setOrgcustomerstatus($statusid);
                    $companyupdate->save();



                 $customercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('orgparentid',$divaid)->addFieldToFilter('usertype',"user");;
                 if($customercollection->getSize()!=0)
                 {
                   foreach ($customercollection as $pd)
                    {
                     $entityid=$pd->getEntityId();
                     $customerstatus=$this->customerFactory->create()->load($entityid);
                     $customerstatus->setOrgcustomerstatus($statusid);
                     $customerstatus->save();
                   }
                }
                  }

                  $resp[]= array('response' => 'success');
                  $idx = $this->_indexerFactory->create()->load("customer_grid");
                  $idx->reindexRow("customer_grid");
                   return $resp;
                    }
                    else {
                        $resp[]= array('response' => 'invalid statusid');
                        return $resp;
                    }
          }
}
