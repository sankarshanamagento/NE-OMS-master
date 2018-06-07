<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;


class Save extends \Magento\Backend\App\Action
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     */
    public function __construct(Action\Context $context, PostDataProcessor $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Netenrich_Services::save');
    }

    /**
     * Save action
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
        $orgid= $customerObj->getDivauserid();
		$action="insert";
		
		
		
		
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            $model = $this->_objectManager->create('Netenrich\Services\Model\Services');
             
            $id = $this->getRequest()->getParam('part_id');
			$changedpart = $this->getRequest()->getParam('url');
            if ($id) {
                $model->load($id);
				$action="edit";
			
            }
            
            if (isset($data['uploadservices'])) {
				
                $imageData = $data['uploadservices'];
				
                unset($data['uploadservices']);
            } else {
                $imageData = array();
				
            }

            $model->addData($data);
			
            if (!$this->dataProcessor->validate($data)) {
                $this->_redirect('*/*/edit', ['services_id' => $model->getId(), '_current' => true]);
                return;
            }

            try {
                $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
				
				/* import services with bulk csv data  and sync with diva*/
                $imageFile = $imageHelper->uploadImage('uploadservices');
				$bulknumbers=array();
                $syncmissed=array();
				if ($imageFile) {
				    $imagepath="pub/media/".$imageFile;
					$file = fopen($imagepath,"r");
					while (($line = fgetcsv($file)) !== FALSE) 
					  {
					
					   if($line[0]!='partnumber')
					   {
						 if($imageHelper->getcounts($line[0])==0)
						 {
					   $uq=rand(1111111,9999999);
					   $storeid=$imageHelper->getwebsiteid($line[2]);
					   $catid=$imageHelper->getcatid($line[3],$storeid);
					   $subcatid=$imageHelper->getsubcatid($line[4],$catid);
					   $tower=$imageHelper->getoptionid('towers',$line[5]);
					   $assets=$imageHelper->getoptionid('assets',$line[6]);
					   $contract=$imageHelper->getoptionid('contractterm',$line[10]);
					   $servicepackage=$imageHelper->getoptionid('service_package',$line[7]);
					   $practice=$imageHelper->getoptionid('practice',$line[12]);
					   $payable=$imageHelper->getoptionid('payable',$line[11]);
					   $servicelevel=$imageHelper->getoptionid('servicelevel',$line[9]);
					   
					   
								 
					   
					   $modelcsv = $this->_objectManager->create('Netenrich\Services\Model\Services');
					    
							  $modelcsv->setPartnumber($line[0]);
							  $modelcsv->setServicename($line[1]);
							  $modelcsv->setStore($storeid);
							  $modelcsv->setCategoryid($catid);
							  $modelcsv->setSubcategoryid($subcatid);
							  $modelcsv->setTowers($tower);
							  $modelcsv->setAssets($assets);
							  $modelcsv->setPayable($payable);
							  $modelcsv->setContractterm($contract);
							  $modelcsv->setPractice($practice);
							  $modelcsv->setServicePackage($servicepackage);
							  $modelcsv->setServicelevel($servicelevel);
							  $modelcsv->setServiceType($line[8]);
							  $modelcsv->save();
							  
					
							  
					$bulkcategorydata=array("serviceId"=>$modelcsv->getId(),
					"partNumber"=>$line[0],
					"serviceName"=>$line[1],
					"storeId"=>$storeid,
					"categoryId"=>$catid,
					"subCategoryId"=>$subcatid,
					"packageId"=>$servicepackage,
					"serviceLevelId"=>$servicelevel,
					"serviceTypeId"=>$line[8],
					"orgId"=>$orgid,
					"action" => "insert"); 
						
                $bulkcontent = json_encode($bulkcategorydata, JSON_NUMERIC_CHECK);
				$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		        $imageFile = $imageHelper->Servicesapis($bulkcontent);		
                 if($imageFile['status'] === "success")
					 {
						$bulknumbers[] =$line[0];
					 }	
                     else
					 {
						$syncmissed[] = $line[0];
					 }
							
						      }
                               else 
						      {
							   $missedpartnumbers[]=$line[0];
							   $string=implode(",",$missedpartnumbers);
						      }	
							 
					   }
					   /* end of bulk import sync here */
					  }
					 $sucessstring=implode(",",$bulknumbers);
					 $errorstring=implode(",",$syncmissed);
					  
					  if(isset($sucessstring))
					 {						 
					    $this->messageManager->addSuccess(__('Following Part Numbers '.$sucessstring.'  Sync with Web Hook.'));
					 }
					 if(isset($errorstring) && $errorstring!="")
					 {						 
					    $this->messageManager->addError(__('Following Part Numbers are Not Sync with web hook '.$errorstring.' Please Edit to Sync.'));
					 }
					  
					  if(isset($string))
					 {						 
					    $this->messageManager->addError(__('Following Part Numbers '.$string.'  already Exits in Database.'));
					 } 					
				}
				else
				{ 
			       if(isset($id))
				   {
							if($changedpart=="chnaged")
							{
								
								  if($imageHelper->getcounts($data['partnumber'])==0)
								  { 
									  $model->save();
								  }
								  else 
								  {
									 $this->messageManager->addError(__('Already Part Number Exits in Data.'));
									 $this->_redirect('*/*/');
									 return;
								  }							  
							}
						   else
						   {					   
							$model->save();
						   }					
				   }
				   else 
				   {
					  if($imageHelper->getcounts($data['partnumber'])==0)
					  { 
						  $model->save();
					  }
					  else 
					  {
						 $this->messageManager->addError(__('Already Part Number Exits in Data.'));
						 $this->_redirect('*/*/');
						 return;
					  }
                   }					
				  
				  }
				
				 
				
                
                if($data['partnumber']!="")
		         {
					 
			     
				$categorydata=array("serviceId"=>$model->getId(),
				"partNumber"=>$data['partnumber'],
				"serviceName"=>$data['servicename'],
				"storeId"=>$data['store'],
				"categoryId"=>$data['categoryid'],
				"subCategoryId"=>$data['subcategoryid'],
				"packageId"=>$data['service_package'],
				"serviceLevelId"=>$data['servicelevel'],
				"serviceTypeId"=>$data['service_type'],
				"contractTermId"=>$data['contractterm'],
				"orgId"=>$orgid,
				"action" => "insert"); 
		
				$content = json_encode($categorydata, JSON_NUMERIC_CHECK);
				$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		        $imageFile = $imageHelper->Servicesapis($content); 
                     if($imageFile['status'] === "success")
					 {
			/* create a object for the data webapi to insert data */			 
			$webhook=$this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('services');
			$webhook->setEntityId($data['servicename']);
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction($action);
			$webhook->save();
						$this->messageManager->addSuccess(__($data['partnumber'].'Sucessfully  Sync With Web Hook'));
					 }	
                      else
					 {
						$this->messageManager->addError(__($data['partnumber'].'  UnSucessfully  Sync With Web Hook Please edit once again')); 
					 }					 
				 }
                $this->messageManager->addSuccess(__('The Data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['services_id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['services_id' => $this->getRequest()->getParam('services_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
