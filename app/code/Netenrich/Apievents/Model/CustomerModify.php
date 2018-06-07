<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CustomerModifyInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Encryption\Helper\Security;

/**
 * Defines the implementaiton class of the calculator service contract.
 */
class CustomerModify implements CustomerModifyInterface
{



     protected $customerFactory;
     protected $addressFactory;
     public function __construct(
\Magento\Framework\App\Action\Context $context,
\Magento\Customer\Model\CustomerFactory $customerFactory,
\Magento\Customer\Model\AddressFactory $addressFactory,
\Magento\Indexer\Model\IndexerFactory $indexerFactory,
\Magento\Framework\App\ResourceConnection $ResourceConnection

)
{
    $this->customerFactory = $customerFactory;
    $this->addressFactory = $addressFactory;
    $this->ResourceConnection=$ResourceConnection;
    $this->_indexerFactory = $indexerFactory;

}




    public function modifyCustomer($firstname=null,$lastname=null,$email=null,$phone=null,$zip=null,$country=null,$city=null,$street=null,$state=null,$timezone=null,$notifyemail=null,$organizationtype=null,$organizationname=null,$divauserid=null,$orgparentid=null,$orgcustomerstatus=null,$userimage=null)
            {
              try{

               $connection=$this->ResourceConnection->getConnection();
               $connection->beginTransaction();

         $customercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divauserid);
        foreach ($customercollection as $pd)
         {
          $entityid=$pd->getEntityId();
         }


         $customermodel=$this->customerFactory->create();
         $customermodel->load($entityid);

         $customermodel->setFirstname($firstname);
         $customermodel->setLastname($lastname);
         $customermodel->setEmail($email);

         $customermodel->setOrgparentid($orgparentid);
         $customermodel->setOrganizationtype($organizationtype);
         $customermodel->setOrganizationname($organizationname);
         $customermodel->setDivauserid($divauserid);
         $customermodel->setOrgcustomerstatus($orgcustomerstatus);
         $customermodel->setOrgtimezone($timezone);
         $customermodel->setNotificationemail($notifyemail);
         $customermodel->setUserimage($userimage);

         $customermodel->save();

         $addresscollection= $this->addressFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('parent_id',$entityid);
        foreach ($addresscollection as $ac)
         {
          $addressentityid=$ac->getEntityId();
         }


         $addressmodel= $this->addressFactory->create()->load($addressentityid);
         $addressmodel->setTelephone($phone);
         $addressmodel->setPostcode($zip);
         $addressmodel->setCity($city);
         $addressmodel->setCountryId($country);
         $addressmodel->setFirstname($firstname);
         $addressmodel->setLastname($lastname);
         $addressmodel->setRegion($state);
         $addressmodel->setStreet($street);


         $addressmodel->save();



  $connection->commit();
            try{
              $idx = $this->_indexerFactory->create()->load("customer_grid");
              $idx->reindexRow("customer_grid");
            }
            catch(\Exception $e)
            {
              $resp[]= array('response' => 'success');
               return $resp;
            }

              $resp[]= array('response' => 'success');
             return  $resp;


              }

              catch(\Exception $e){

                $connection->rollBack();
                return $e->getMessage();

              }


            }


}
