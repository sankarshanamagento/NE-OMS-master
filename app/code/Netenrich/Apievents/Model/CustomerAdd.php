<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CustomerAddInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Encryption\EncryptorInterface as Encryptor;
use Magento\Framework\Encryption\Helper\Security;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
/**
 * Defines the implementaiton class of the CustomerAdd service contract.
 */
class CustomerAdd implements CustomerAddInterface
{


    /**
     * @var \Magento\Indexer\Model\IndexerFactory
     */
     protected $_indexerFactory;
     /**
     * @var \Magento\Indexer\Model\Indexer\CollectionFactory
     */
     protected $_indexerCollectionFactory;
     protected $customerFactory;
     protected $addressFactory;
     public function __construct(
\Magento\Framework\App\Action\Context $context,
\Magento\Indexer\Model\IndexerFactory $indexerFactory,

\Magento\Customer\Model\CustomerFactory $customerFactory,
\Magento\Customer\Model\AddressFactory $addressFactory,
CustomerRepositoryInterface $customerRepository,
\Magento\Framework\App\ResourceConnection $ResourceConnection

)
{
    $this->customerFactory = $customerFactory;
    $this->addressFactory = $addressFactory;
    $this->ResourceConnection=$ResourceConnection;
    $this->_indexerFactory = $indexerFactory;

    $this->customerRepository = $customerRepository;

}




    public function addCustomer($firstname=null,$lastname=null,$email=null,$phone=null,$zip=null,$country=null,$city=null,$street=null,$state=null,$timezone=null,$notifyemail=null,$organizationtype=null,$organizationname=null,$divauserid=null,$orgparentid=null,$orgcustomerstatus=null,$userimage=null,$usertype=null)
            {
                try{
                       $s=$this->customerRepository->get($email,$websiteId=null);
                       $resp[]= array('response' => 'A customer with the same email id already Exists');
                       return $resp;
                    }
                      catch(NoSuchEntityException $e)
                      {
                        try{


                        $connection=$this->ResourceConnection->getConnection();
                          $connection->beginTransaction();
                            $customermodel= $this->customerFactory->create();
                            $customermodel->setFirstname($firstname);
                            $customermodel->setLastname($lastname);
                            $customermodel->setEmail($email);
                            $customermodel->setGroupId("1");

                            /*$customermodel->setOrgparentid($orgparentid);
                            $customermodel->setOrganizationtype($organizationtype);
                            $customermodel->setOrganizationname($organizationname);
                            $customermodel->setDivauserid($divauserid);
                            $customermodel->setOrgcustomerstatus($orgcustomerstatus);
                            $customermodel->setOrgtimezone($timezone);
                            $customermodel->setNotificationemail($notifyemail);
                            $customermodel->setUserimage($userimage);
                            $customermodel->setData("usertype",$usertype);*/

                            $customermodel->save();
                            $customerentityid=$customermodel->getEntityId();

                            $this->saveAttributes($customerentityid,$timezone,$notifyemail,$usertype,$organizationname,$orgparentid,$divauserid,$organizationtype,$orgcustomerstatus);

                            $addressmodel= $this->addressFactory->create();
                            $addressmodel->setTelephone($phone);
                            $addressmodel->setPostcode($zip);
                            $addressmodel->setCity($city);
                            $addressmodel->setCountryId($country);
                            $addressmodel->setFirstname($firstname);
                            $addressmodel->setLastname($lastname);
                            $addressmodel->setStreet($street);
                            $addressmodel->setRegion($state);
                            $addressmodel->setParentId($customermodel->getEntityId());

                            $addressmodel->save();

                            $customermodel->setDefaultBilling($addressmodel->getEntityId());
                            $customermodel->save();

                             $connection->commit();
                             $resp[]= array('response' => 'success');
                             try{
                               $idx = $this->_indexerFactory->create()->load("customer_grid");
                               $idx->reindexRow("customer_grid");
                             }
                             catch(\Exception $e)
                             {
                               $resp[]= array('response' => 'success');
                                return $resp;
                             }

                            return  $resp;

                            }
                            catch(\Exception $e){
                               $connection->rollBack();
                               return $e;
                            }

                      }

            }


public function saveAttributes($customerentityid,$timezone,$notifyemail,$usertype,$organizationname,$orgparentid,$divauserid,$organizationtype,$orgcustomerstatus)
           {

          //  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
          //  $customerRepository = $objectManager->get('Magento\Customer\Api\CustomerRepositoryInterface');
            $customer = $this->customerRepository->getById($customerentityid);
            $customer->setCustomAttribute("usertype",$usertype);
                        $customer->setCustomAttribute("orgtimezone",$timezone);
                        $customer->setCustomAttribute("organizationtype",$organizationtype);
                        $customer->setCustomAttribute("orgcustomerstatus",$orgcustomerstatus);
                        $customer->setCustomAttribute("notificationemail",$notifyemail);
                        $customer->setCustomAttribute("organizationname",$organizationname);
                        $customer->setCustomAttribute("divauserid",$divauserid);
                          $customer->setCustomAttribute("orgparentid",$divauserid);

            $this->customerRepository->save($customer);
          //  $customerAttrValue = $customer->getCustomAttribute('usertype');
           }


}
