<?php

/**
 * Copyright 2016 NetEnrich. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Model;

use Netenrich\Apievents\Api\CompanyModifyInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\InputException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Defines the implementaiton class of the CompanyAdd service contract.
 */
class CompanyModify implements CompanyModifyInterface
{





    protected $companyFactory;
    protected $companypathFactory;
    public function __construct(
\Magento\Framework\App\Action\Context $context,
\Netenrich\Company\Model\CompanyFactory $companyFactory,
\Magento\Customer\Model\CustomerFactory $customerFactory,
\Magento\Customer\Model\AddressFactory $addressFactory,
\Magento\Framework\ObjectManagerInterface $objectManager,
\Magento\Indexer\Model\IndexerFactory $indexerFactory,
\Magento\Framework\App\ResourceConnection $ResourceConnection,
\Netenrich\Company\Model\CompanypathFactory $companypathFactory,
CustomerRepositoryInterface $customerRepository,
\Netenrich\Apievents\Api\Data\StatusDataInterfaceFactory $dataFactory


)
{
    $this->companyFactory = $companyFactory;
    $this->customerFactory = $customerFactory;
    $this->addressFactory = $addressFactory;
    $this->ResourceConnection=$ResourceConnection;
    $this->_objectManager = $objectManager;
    $this->_indexerFactory = $indexerFactory;
	$this->companypathFactory=$companypathFactory;
      $this->customerRepository = $customerRepository;
      $this->dataFactory = $dataFactory;

}


    public function modifyCompany($companyname=null,$firstname=null,$lastname=null,$address=null,$country=null,$state=null,$city=null,$zip=null,$website=null,$phone=null,$fax=null,$organisationtype=null,$parent_id=null,$companyemail=null,$status=null,$divaid=null,$timezone=null,$usertype=null,$useremail=null,$webstoreid=null) {


        //-----  Updates the Company Information in Company Module ------

            $customerId=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
            if($customerId->getSize()==0)
            {
              /* $page_object = $this->dataFactory->create();
              $page_object->setStatus('failed');
              $page_object->setResponse('customer with this unique id does not exists');
              return $page_object; */
			  
			  return $this->addCompany($companyname,$firstname,$lastname,$address,$country,$state,$city,$zip,$website,$phone,$fax,$organisationtype,$parent_id,$companyemail,$status,$divaid,$timezone,$usertype,$useremail,$webstoreid);
			  
            }



try{

        $connection=$this->ResourceConnection->getConnection();
        $connection->beginTransaction();

         $company1 = $this->companyFactory->create();
         $company1->load($divaid,"divauniqueid");
         $company1->setName($companyname);
         $company1->setAddress($address);
         $company1->setCountry($country);
         $company1->setState($state);
         $company1->setCity($city);
         $company1->setZip($zip);
         $company1->setWebsite($website);
         $company1->setPhone($phone);
         $company1->setFax($fax);
         $company1->setEmail($companyemail);
         $company1->setTimzone($timezone);
         $company1->save();


        //----- Update the Company Information in Customer Module  ----
        $customerId=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
		
		foreach ($customerId as $customerIdl) {
          # code...
          $entity_id_customer=$customerIdl->getEntityId();
          $entity_id_shipping=$customerIdl->getDefaultShipping();
        }
        $customermodel= $this->customerFactory->create()->load($entity_id_customer);
		/* sankarshana when store updates change entrie tree under distibutor */
		if($customermodel->getWebsiteId()!=$webstoreid)
		{
			$customerorgId=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('orgparentid',$divaid);
		if($customerorgId->getSize()>0)
		{
			foreach ($customerorgId as $customerorgId) 
			{
			  $customerIds=$customerorgId->getEntityId();
			  $custDivaid=$customerorgId->getDivauserid();
			  $this->Webstoreidforuser($customerIds,$custDivaid,$webstoreid);
			}
		 }
		}
		/* sankarshana when store updates change entrie tree under distibutor */
        $customermodel->setFirstname($firstname);
        $customermodel->setLastname($lastname);
        $customermodel->setEmail($useremail);
		$customermodel->setWebsiteId($webstoreid);
		$customermodel->setStoreId($webstoreid);
        //$customermodel->setOrgtimezone($timezone);
        $customermodel->save();
		
		
		
		
        //---------- Saving custom Attribute of customer-------//
        $customer = $this->customerRepository->getById($entity_id_customer);
        $customer->setCustomAttribute("orgtimezone",$timezone);
        $customer->setCustomAttribute("organizationname",$companyname);
        $this->customerRepository->save($customer);

        //----------  Update the company shipping address set in customer module    ---------//
        $addressmodel= $this->addressFactory->create()->load($entity_id_shipping);
        $addressmodel->setTelephone($phone);
        $addressmodel->setPostcode($zip);
        $addressmodel->setCity($city);
        $addressmodel->setCountryId($country);
        $addressmodel->setFirstname($firstname);
        $addressmodel->setLastname($lastname);
        $addressmodel->setCompany($companyname);
        $addressmodel->setStreet($address);
        $addressmodel->setRegion($state);
        $addressmodel->save();


        //--------- Update the Child Companies Billing Address Sets  --------- //

          $childCompanyId=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('orgparentid',$divaid)->addFieldToFilter('usertype','company');
          foreach ($childCompanyId as $cd) {
            # code...
            $entity_id_billing=$cd->getDefaultBilling();
            $addressmodel= $this->addressFactory->create()->load($entity_id_billing);
            $addressmodel->setTelephone($phone);
            $addressmodel->setPostcode($zip);
            $addressmodel->setCity($city);
            $addressmodel->setCountryId($country);
            $addressmodel->setFirstname($firstname);
            $addressmodel->setLastname($lastname);
            $addressmodel->setCompany($companyname);
            $addressmodel->setStreet($address);
            $addressmodel->setRegion($state);
            $addressmodel->save();

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
          $page_object->setResponse('Organisation modified successfully');
          return $page_object;

        }




        $page_object = $this->dataFactory->create();
        $page_object->setStatus('success');
        $page_object->setResponse('Organisation modified successfully');
        return $page_object;

    }
    catch(\Exception $e)
        {
          $connection->rollBack();
          $page_object = $this->dataFactory->create();
          $page_object->setStatus('failed');
          $page_object->setResponse('error occured and roll backed the insertion data'.$e->getMessage());
          return  $page_object;
        }

  }
  
  
  public function addCompany($companyname=null,$firstname=null,$lastname=null,$address=null,$country=null,$state=null,$city=null,$zip=null,$website=null,$phone=null,$fax=null,$organisationtype=null,$parent_id=null,$companyemail=null,$status=null,$divaid=null,$timezone=null,$usertype=null,$useremail=null,$webstoreid=null)
     {





       //Handeling exception if email id already exists

      try{


              $s=$this->customerRepository->get($useremail,$websiteId=null);
              $page_object = $this->dataFactory->create();
              $page_object->setStatus('failed');
              $page_object->setResponse('A customer with the same email id already Exists');
              return $page_object;

           }
             catch(NoSuchEntityException $e)
             {

             }

            $parentcustomercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$parent_id);
            if($parentcustomercollection->getSize()==0 && $organisationtype!=1)
            {
              $page_object = $this->dataFactory->create();
              $page_object->setStatus('failed');
              $page_object->setResponse('Parent Organisation does not exists');
              return $page_object;

            }
            $uniquecustomercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$divaid);
            if($uniquecustomercollection->getSize()!=0)
            {
              $page_object = $this->dataFactory->create();
              $page_object->setStatus('failed');
              $page_object->setResponse('Organisation already exists');
              return $page_object;
            }

            else {
              # code...



        //-----  INSERTS THE INFORMATION OF COMPANY INTO COMPANY MODULE ------
    try
    {

      $connection=$this->ResourceConnection->getConnection();
      $connection->beginTransaction();

        $orgtypevalid=$this->validOrgType($organisationtype);

          $companyCollection= $this->companyFactory->create()->getCollection()->addFieldToFilter('name',$companyname);

          if($companyCollection->getSize()==0)
{

        $company1 = $this->companyFactory->create();
         $company1->setName($companyname);
         $company1->setAddress($address);
         $company1->setCountry($country);
         $company1->setState($state);
         $company1->setCity($city);
         $company1->setZip($zip);
         $company1->setWebsite($website);
         $company1->setPhone($phone);
         $company1->setFax($fax);
         $company1->setOrgTypeId($organisationtype);
         $company1->setParent_id($parent_id);
         $company1->setEmail($companyemail);
         $company1->setStatus($status);
         $company1->setDivauniqueid($divaid);
         $company1->setTimzone($timezone);
         $company1->save();
         $organisationId=$company1->getOrgId();

}
else {
  foreach($companyCollection as $company)
  {
    $organisationId=$company->getOrgId();
  }

}
    //------------- CREATE THE SAME COMPANY AS  A CUSTOMER  ---------------

        $groupid=array(1=>4,2=>5,3=>6,4=>7);

        $customermodel= $this->customerFactory->create();
        $customermodel->setFirstname($firstname);
        $customermodel->setLastname($lastname);
		$customermodel->setWebsiteId($webstoreid);
        $customermodel->setEmail($useremail);
        $customermodel->setGroupId($groupid[$organisationtype]);

        $customermodel->save();


        $customerentityid=$customermodel->getEntityId();
        $this->saveAttributes($customerentityid,$timezone,$usertype,$companyname,$parent_id,$divaid,$organisationtype,$status);

        //------------- Saving the Address Information For shipping Address-------------//
        $addressmodel= $this->addressFactory->create();
        $addressmodel->setTelephone($phone);
        $addressmodel->setPostcode($zip);
        $addressmodel->setCity($city);
        $addressmodel->setCountryId($country);
        $addressmodel->setFirstname($firstname);
        $addressmodel->setLastname($lastname);
        $addressmodel->setCompany($companyname);
        $addressmodel->setStreet($address);
        $addressmodel->setRegion($state);
        $addressmodel->setParentId($customermodel->getEntityId());

        $addressmodel->save();

        $customermodel->setDefaultShipping($addressmodel->getEntityId());
        $customermodel->save();

        //------------Save the Address set for  Billing Information-------------------//

        if($organisationtype==2 || $organisationtype==3 || $organisationtype==4)
        {
      $customercollection= $this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$parent_id);
        foreach ($customercollection as $pd)
         {
          $entityid=$pd->getEntityId();
          $billingid=$pd->getDefaultShipping();

        }


      $addressmodelb= $this->addressFactory->create()->load($billingid);
      $addressmodel= $this->addressFactory->create();
      $addressmodel->setTelephone($addressmodelb->getTelephone());
      $addressmodel->setPostcode($addressmodelb->getPostcode());
      $addressmodel->setCity($addressmodelb->getCity());
      $addressmodel->setCountryId($addressmodelb->getCountryId());
      $addressmodel->setFirstname($addressmodelb->getFirstname());
      $addressmodel->setLastname($addressmodelb->getLastname());
      $addressmodel->setCompany($addressmodelb->getCompany());
      $addressmodel->setStreet($addressmodelb->getStreet());
      $addressmodel->setRegion($addressmodelb->getRegion());
      $addressmodel->setParentId($customerentityid);

      $addressmodel->save();

      $customermodel->setDefaultBilling($addressmodel->getEntityId());
      $customermodel->save();

    }
    else {
      # code...
      $customermodel->setDefaultBilling($addressmodel->getEntityId());
      $customermodel->save();
    }


      //-----------    update netenrich_organisation_path data depending upon organisation type    ------//

        switch ($organisationtype) {
            case '4':
                $companypath1 = $this->companypathFactory->create();
                $companypath1->setOrgId($organisationId);
                $companypath1->setPath($organisationId);
                $companypath1->setDivaid($divaid);
                $companypath1->setlevel("400");
                $companypath1->save();

                $companypath2 = $this->companypathFactory->create();
                $companypath2->setOrgId($organisationId);
                $companypath2->setDivaid($divaid);
                    $Partner=$this->companyFactory->create();
                    $Partner->load($parent_id,"divauniqueid");
                $companypath2->setPath($Partner->getOrgId());
                $companypath2->setlevel("300");
                $companypath2->save();

                $companypath3 = $this->companypathFactory->create();
                $companypath3->setOrgId($organisationId);
                $companypath3->setDivaid($divaid);
                    $Distributer=$this->companyFactory->create();
                    $Distributer->load($Partner->getParentId(),"divauniqueid");
                $companypath3->setPath($Distributer->getOrgId());
                $companypath3->setlevel("200");
                $companypath3->save();

                $companypath4 = $this->companypathFactory->create();
                $companypath4->setOrgId($organisationId);
                $companypath4->setDivaid($divaid);
                    $ServiceProvider=$this->companyFactory->create();
                    $ServiceProvider->load($Distributer->getParentId(),"divauniqueid");
                $companypath4->setPath($ServiceProvider->getOrgId());
                $companypath4->setlevel("100");
                $companypath4->save();
                break;
            case '3':
                $companypath1 = $this->companypathFactory->create();
                $companypath1->setOrgId($organisationId);
                $companypath1->setPath($organisationId);
                $companypath1->setDivaid($divaid);
                $companypath1->setlevel("300");
                $companypath1->save();

                $companypath2 = $this->companypathFactory->create();
                $companypath2->setOrgId($organisationId);
                $companypath2->setDivaid($divaid);
                    $Distributer=$this->companyFactory->create();
                    $Distributer->load($parent_id,"divauniqueid");
                $companypath2->setPath($Distributer->getOrgId());
                $companypath2->setlevel("200");
                $companypath2->save();

                $companypath3 = $this->companypathFactory->create();
                $companypath3->setOrgId($organisationId);
                    $ServiceProvider=$this->companyFactory->create();
                    $ServiceProvider->load($Distributer->getParentId(),"divauniqueid");
                $companypath3->setPath($ServiceProvider->getOrgId());
                $companypath3->setDivaid($divaid);
                $companypath3->setlevel("100");
                $companypath3->save();
                break;
            case '2':
                $companypath1 = $this->companypathFactory->create();
                $companypath1->setOrgId($organisationId);
                $companypath1->setPath($organisationId);
                $companypath1->setDivaid($divaid);
                $companypath1->setlevel("200");
                $companypath1->save();

                $companypath2 = $this->companypathFactory->create();
                $companypath2->setOrgId($organisationId);
                    $ServiceProvider=$this->companyFactory->create();
                    $ServiceProvider->load($parent_id,"divauniqueid");
                $companypath2->setPath($ServiceProvider->getOrgId());
                $companypath2->setDivaid($divaid);
                $companypath2->setlevel("100");
                $companypath2->save();
                # code...
                break;
            case '1':
                $companypath1 = $this->companypathFactory->create();
                $companypath1->setOrgId($organisationId);
                $companypath1->setPath($organisationId);
                $companypath1->setDivaid($divaid);
                $companypath1->setlevel("100");
                $companypath1->save();
                # code...
                break;
            default:

                # code...
                break;
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
          $page_object->setResponse('organisation successfully created');
          return   $page_object;
        }
        $page_object = $this->dataFactory->create();
        $page_object->setStatus('success');
        $page_object->setResponse('organisation successfully created');
        return   $page_object;
       }


   catch(\Exception $e)
       {
         $connection->rollBack();
         $page_object = $this->dataFactory->create();
         $page_object->setStatus('failed');
         $page_object->setResponse('error occured and roll backed the insertion data'.$e->getMessage());
         return   $page_object;
         //return $e->getMessage();
       }
    }
}

 public function saveAttributes($customerentityid,$timezone,$usertype,$companyname,$parent_id,$divaid,$organisationtype,$status)
               {
                $customer = $this->customerRepository->getById($customerentityid);
                $customer->setCustomAttribute("usertype",$usertype);
                            $customer->setCustomAttribute("orgtimezone",$timezone);
                            $customer->setCustomAttribute("organizationtype",$organisationtype);
                            $customer->setCustomAttribute("orgcustomerstatus",$status);
                            $customer->setCustomAttribute("organizationname",$companyname);
                            $customer->setCustomAttribute("divauserid",$divaid);
                              $customer->setCustomAttribute("orgparentid",$parent_id);
                $this->customerRepository->save($customer);

               }
			   
			   
   public function validOrgType($organisationtype)
        {
            if ($organisationtype<=4 && $organisationtype>=1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
	

    public function Webstoreidforuser($customerid,$uniqueid,$webstoreid)
	{
		$partnercustomers=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('divauserid',$uniqueid);
		foreach ($partnercustomers as $partners) {
          
          $entity_id=$partners->getEntityId();
        }
        $customerpartnermodel= $this->customerFactory->create()->load($entity_id);
		if($customerpartnermodel->getWebsiteId()!=$webstoreid)
		{
			$cids= array();
			$customereclientorgId=$this->customerFactory->create()->getCollection()->addAttributeToSelect('*')->addFieldToFilter('orgparentid',$uniqueid);
			if($customereclientorgId->getSize()>0)
		{
			
			foreach ($customereclientorgId as $customerclientorgId) 
			{
			  $cids[]=$customerclientorgId->getEntityId();
			  $custclientDivaid=$customerclientorgId->getDivauserid();
			}
		 }
		 $customerpartnermodel->setWebsiteId($webstoreid);
		 //$customerpartnermodel->setStoreId($webstoreid);
        //$customermodel->setOrgtimezone($timezone);
        $customerpartnermodel->save();
		$this->Webstoreupdate($cids,$webstoreid);
		}
		
	}	
	
	
	public function Webstoreupdate($customerarray,$webstoreid)
	{
		//print_r($customerid);
		foreach ($customerarray as $customers) 
			{
			  $clientmodel= $this->customerFactory->create()->load($customers);
			  $clientmodel->setWebsiteId($webstoreid);
			  //$clientmodel->setStoreId($webstoreid);
               //$customermodel->setOrgtimezone($timezone);
              $clientmodel->save();
			}
		
		
		 
	}


}
