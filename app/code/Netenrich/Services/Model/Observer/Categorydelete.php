<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Categorydelete implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_distibutorFactory;
	protected $_storeManager;
	protected $_messageManager;
	protected $_webhookFactory;
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Netn\Distibutorproducts\Model\DistibutorproductsFactory $distibutorFactory,
		\Magento\Store\Model\GroupFactory $storeManager,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Netenrich\Webhook\Model\WebhookFactory $webhookFactory
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_distibutorFactory = $distibutorFactory;
		$this->_storeManager = $storeManager;
		$this->_messageManager = $messageManager;
		$this->_webhookFactory = $webhookFactory;
    }
 
    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$category = $observer->getEvent()->getCategory();
		 $reqeustParams = $this->_request->getParams();
		 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
         $orgid= $customerObj->getDivauserid();
		 $rootid=explode("/",$category->getPath());
		 $categorydata=array();
		 $storeid=$this->_storeManager->create()->getCollection()->addFieldToFilter('root_category_id',$rootid[1]);
		 foreach($storeid as $storegroup)
		 {
			 $store=$storegroup->getWebsiteId();
		 }
		 
		 
		
		 
         if($category->getLevel()==2)
		{
			$categorydata['entity']="category";
			$parent=0;
			
			 if(count($category->getChildrenCategories())!=0)
		   {
			  
			$this->_messageManager->addError(__($category->getName()." can't be Deleted as It has SubCategories "));
			return $resultRedirect->setUrl('*/*/');
			
		   } 
		  
		}
		else if($category->getLevel()==3)
		{
			$categorydata['entity']="subcategory";
			$parent=$category->getParentCategory()->getId();
			$serivcescount = $objectManager->create('Netenrich\Services\Model\Services')->getCollection()->addFieldToFilter('subcategoryid',$category->getId())->count();
		
			if($serivcescount!=0)
		   {
			   
			$this->_messageManager->addError(__($category->getName()." can't be deleted as it is Mapped to one of the services"));
			return $resultRedirect->setUrl('*/*/');
            			
		   }
		}	

		 $categorydata['elements']=array( array("entityId"=>$category->getId(),"entityName"=>$category->getName(),"action"=>"delete","parent"=>$store,"parentId"=>0,"active"=>$category->getIsActive(),
				"orgId"=>$orgid));
		
        

        		
		
		
		 $content = json_encode($categorydata, JSON_NUMERIC_CHECK);
		 $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		 $imageFile = $imageHelper->callapis($content);
		 if($imageFile['status'] === "success")
		 {
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName($categorydata['entity']);
			$webhook->setEntityId($category->getName());
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('Delete');
			$webhook->save();
			$this->_messageManager->addSuccess(__($category->getName().' Delete api Sucessfully  Sync With Web Hook'));
		 }
		 else
		 { 
	        $this->_messageManager->addError(__($category->getName().' Please Try Again'));
	        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
	     } 
		
		  
		  
	}
}