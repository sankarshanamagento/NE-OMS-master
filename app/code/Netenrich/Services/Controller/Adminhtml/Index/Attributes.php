<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;



class Attributes extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $attributeFactory;
    protected $_productCollectionFactory;
	protected $_objectManager;
    /**
     * @param \Magento\Framework\App\Action\Context           $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, 
	\Magento\Framework\View\Result\PageFactory $resultPageFactory, 
	\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
	\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
	\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
	\Magento\Framework\ObjectManagerInterface $objectManager,
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_objectManager = $objectManager;
        $this->resultRawFactory = $resultRawFactory;
		$this->_productCollectionFactory = $productCollectionFactory; 
		$this->eavAttributeRepository = $eavAttributeRepository;
	
        parent::__construct($context,$data);
    }

    public function execute()
    {
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
        $orgid= $customerObj->getDivauserid();
		$attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,'service_package');
		$options = $attributes->getSource()->getAllOptions(true);
		
		$createarray=array();
		$createarray['entity']='package';
		
		foreach($options as $id=>$values)
		{
			if($values['value']!="")
			{
			 $createarray['elements'][]=array("entityId"=>$values['value'],"entityName"=>$values['label'],"action"=>"insert","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);				
			}
		}
		//print_r($createarray);
		$content =  json_encode($createarray,JSON_NUMERIC_CHECK);
		$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		$imageFile = $imageHelper->callapis($content);
		if($imageFile['status'] === "success")
			{
				$webhook = $objectManager->create('Netenrich\Webhook\Model\Webhook');
					   $webhook->setJobid($imageFile['jobid']);
			           $webhook->setEntityName($createarray['entity']);
			           $webhook->setEntityId('All Attribute');
			           $webhook->setPayload($content);
			           $webhook->setStatus('inprogress');
			           $webhook->save();
					   $deletearray[]=$id;
					   
					   $this->messageManager->addSuccess(__($createarray['entity']."All Options are sync"));
			}
			else
			{
				$this->messageManager->addError(__($createarray['entity']."Please try again."));		
			}
		
		
		$attributes1 = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,'servicelevel');
		$options1 = $attributes1->getSource()->getAllOptions(true);
		
		$createarray1=array();
		$createarray1['entity']='servicelevel';
		
		foreach($options1 as $id=>$values)
		{
			if($values['value']!="")
			{
			 $createarray1['elements'][]=array("entityId"=>$values['value'],"entityName"=>$values['label'],"action"=>"insert","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);				
			}
		}
		//print_r($createarray);
		$content1 =  json_encode($createarray1,JSON_NUMERIC_CHECK);
		$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		$imageFile = $imageHelper->callapis($content1);
		
		if($imageFile['status'] === "success")
			{
				$webhook = $objectManager->create('Netenrich\Webhook\Model\Webhook');
					   $webhook->setJobid($imageFile['jobid']);
			           $webhook->setEntityName($createarray1['entity']);
			           $webhook->setEntityId("All Services");
			           $webhook->setPayload($content1);
			           $webhook->setStatus('inprogress');
			           $webhook->save();
					   $deletearray[]=$id;
					 
					   $this->messageManager->addSuccess(__($createarray1['entity']."All Options are sync"));
			}
			else
			{
				$this->messageManager->addError(__($createarray1['entity']."Please try again."));		
			}
			
			
			
			
		 /* $attributes2 = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,'service_type');
		$options2 = $attributes2->getSource()->getAllOptions(true);
		
		$createarray2=array();
		$createarray2['entity']='servicetype';
		
		foreach($options2 as $id=>$values)
		{
			if($values['value']!="")
			{
			 $createarray2['elements'][]=array("entityId"=>$values['value'],"entityName"=>$values['label'],"action"=>"insert","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);				
			}
		}
		//print_r($createarray);
		$content2 =  json_encode($createarray2,JSON_NUMERIC_CHECK);
		$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		$imageFile = $imageHelper->callapis($content2);
		
		if($imageFile['status'] === "success")
			{
				$webhook = $objectManager->create('Netenrich\Webhook\Model\Webhook');
					   $webhook->setJobid($imageFile['jobid']);
			           $webhook->setEntityName($createarray2['entity']);
			           $webhook->setEntityId("All service type");
			           $webhook->setPayload($content1);
			           $webhook->setStatus('inprogress');
			           $webhook->save();
					   $deletearray[]=$id;
					 
					   $this->messageManager->addSuccess(__($createarray2['entity']."All Options are sync"));
			}
			else
			{
				$this->messageManager->addError(__($createarray2['entity']."Please try again."));		
			}	
			
			
			$attributes3 = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,'contractterm');
		$options3 = $attributes3->getSource()->getAllOptions(true);
		
		$createarray3=array();
		$createarray3['entity']='contractterm';
		
		foreach($options3 as $id=>$values)
		{
			if($values['value']!="")
			{
			 $createarray3['elements'][]=array("entityId"=>$values['value'],"entityName"=>$values['label'],"action"=>"insert","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);				
			}
		}
		//print_r($createarray);
		$content3 =  json_encode($createarray3,JSON_NUMERIC_CHECK);
		$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		$imageFile = $imageHelper->callapis($content3);
		
		if($imageFile['status'] === "success")
			{
				$webhook = $objectManager->create('Netenrich\Webhook\Model\Webhook');
					   $webhook->setJobid($imageFile['jobid']);
			           $webhook->setEntityName($createarray3['entity']);
			           $webhook->setEntityId("All contractterm");
			           $webhook->setPayload($content3);
			           $webhook->setStatus('inprogress');
			           $webhook->save();
					   $deletearray[]=$id;
					 
					   $this->messageManager->addSuccess(__($createarray3['entity']." All Options are sync"));
			}
			else
			{
				$this->messageManager->addError(__($createarray3['entity']." Please try again."));		
			}	
			*/
		 
		
		$this->_redirect('*/*/');
        return;
		
		
    }
}
