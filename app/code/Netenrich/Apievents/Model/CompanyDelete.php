<?php

/**
 * Copyright 2017 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CompanyDeleteInterface;

/**
 * Defines the implementaiton class of the CompanyDelete service contract.
 */
class CompanyDelete implements CompanyDeleteInterface
{


    public function __construct(

        \Magento\Framework\App\ResourceConnection $ResourceConnection,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Netenrich\Company\Model\CompanyFactory $companyFactory,
        \Netenrich\Company\Model\CompanypathFactory $companypathFactory,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        \Netenrich\Apievents\Api\Data\StatusDataInterfaceFactory $dataFactory

          )
        {
        $this->customerFactory = $customerFactory;
        $this->dataFactory = $dataFactory;
        $this->ResourceConnection=$ResourceConnection;
        $this->_addressRepository = $addressRepository;
        $this->companyFactory = $companyFactory;
        $this->companypathFactory=$companypathFactory;
        $this->_indexerFactory = $indexerFactory;
        }

        /**
        * Deletes new customer
        *@api
        *@param string $divaid
        *@return \Netenrich\Apievents\Api\Data\StatusDataInterface
         */
    public function deleteCompany($divaid)

      {

      $customerCollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
      if($customerCollection->getSize()==0)
      {
        $page_object = $this->dataFactory->create();
        $page_object->setStatus('failed');
        $page_object->setResponse('A customer with this ID doesn\'t exists');
        return $page_object;
      }
      else {
        try{

            $connection=$this->ResourceConnection->getConnection();
            $connection->beginTransaction();
            foreach ($customerCollection as $customerData)
                {
                  $customerId=$customerData->getEntityId();
                  $billingId=$customerData->getDefaultBilling();
                  $shippingId=$customerData->getDefaultShipping();
                  $orgtype=$customerData->getOrganizationtype();
                }

            $this->_addressRepository->deleteById($billingId);
            if($orgtype!=1)
              {
              $this->_addressRepository->deleteById($shippingId);
              }
            $customer=$this->customerFactory->create()->load($customerId);
            $customer->delete();

              $company=$this->companyFactory->create()->load($divaid,"divauniqueid");
              $companyId=$company->getOrgId();
              $companyLoad=$this->companyFactory->create()->load($companyId);
              $companyLoad->delete();

   //----------Deleting company path--------------------------//

            $companypathCollection=$this->companypathFactory->create()->getCollection()->addFieldToFilter('org_id',$companyId);
            foreach ($companypathCollection as $companypathData)
                    {
                    $pathid=$companypathData->getPathId();
                    $companypathLoad=$this->companypathFactory->create()->load($pathid);
                    $companypathLoad->delete();
                    }

          $connection->commit();
                try {
                  $idx = $this->_indexerFactory->create()->load("customer_grid");
                  $idx->reindexRow("customer_grid");
                }
                      catch(\Exception $e)
                      {
                         $page_object = $this->dataFactory->create();
                        $page_object->setStatus('success');
                        $page_object->setResponse('organisation successfully deleted');
                        return   $page_object;
                      }

                $page_object = $this->dataFactory->create();
                $page_object->setStatus('success');
                $page_object->setResponse('organisation successfully deleted');
                return   $page_object;

          }
        catch(\Exception $e){
                  $connection->rollBack();
                  $page_object = $this->dataFactory->create();
                  $page_object->setStatus('failed');
                  $page_object->setResponse('error occured and roll backed the data'.$e->getMessage());
                  return $page_object;

              }
          }
      }
}
