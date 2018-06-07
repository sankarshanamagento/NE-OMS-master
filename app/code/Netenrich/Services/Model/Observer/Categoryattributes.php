<?php
namespace Netenrich\Services\Model\Observer;

class Categoryattributes implements \Magento\Framework\Event\ObserverInterface
{
    private $category = null;
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
    
	
	public function execute(\Magento\Framework\Event\Observer $observer)
    {
         $category = $observer->getEvent()->getCategory();
		 $reqeustParams = $this->_request->getParams();
		 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
         $orgid= $customerObj->getDivauserid();
		 
		 if($category->getLevel()!=1)
		 {
		  $rootid=explode("/",$category->getPath());
		 $categorydata=array();
		 $storeid=$this->_storeManager->create()->getCollection()->addFieldToFilter('root_category_id',$rootid[1]);
		 
		 if($storeid->getSize()!=0)
		 {
		 foreach($storeid as $storegroup)
		 {
			 
			 $store=$storegroup->getWebsiteId();
		 }
		 } else{ 
		 $this->_messageManager->addError(__($category->getName()."  please assign Root category to  Store "));
			return $resultRedirect->setUrl('*/*/'); 
			}
		 
		  if(isset($reqeustParams['entity_id']))
		 {
		    $action="update";
		 }
		 else
		 {
		   $action="insert"; 
		 }
         if($category->getLevel()==2)
		{
			$categorydata['entity']="category";
			$parent=0;
			$categorydata['elements']=array( array("entityId"=>$category->getId(),"entityName"=>$category->getName(),"action"=>$action,"parent"=>$parent,"parentId"=>$store,"active"=>$category->getIsActive(),
				"orgId"=>$orgid));
		}
		else
		{
			$categorydata['entity']="subcategory";
			$parent=$category->getParentCategory()->getId();
			$categorydata['elements']=array( array("entityId"=>$category->getId(),"entityName"=>$category->getName(),"action"=>$action,"parent"=>$store,"parentId"=>$parent,"active"=>$category->getIsActive(),
				"orgId"=>$orgid));
		}	

		
       
		
		
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
			$webhook->setCreateaction($action);
			$webhook->save();
			$this->_messageManager->addSuccess(__($category->getName().' Sucessfully  Sync With Web Hook'));
		 }
		 else
		 { 
	        $this->_messageManager->addError(__($category->getName().'  UnSucessfully  Sync With Web Hook Please edit once again')); 
	     }
		  
		 
		 }
    }
}