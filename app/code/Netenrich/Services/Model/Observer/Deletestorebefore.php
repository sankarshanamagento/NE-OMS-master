<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Deletestorebefore implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_distibutorFactory;
	protected $_storeManager;
	protected $_messageManager;
	protected $category;
	protected $categoryRepository;
	protected $_webhookFactory;
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $category,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
		\Magento\Store\Model\GroupFactory  $distibutorFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Netenrich\Webhook\Model\WebhookFactory $webhookFactory
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_distibutorFactory = $distibutorFactory;
		$this->_storeManager = $storeManager;
		$this->_messageManager = $messageManager;
		$this->category = $category;
        $this->categoryRepository = $categoryRepository;
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
		$website = $observer->getEvent()->getWebsite();
		$post=$reqeustParams = $this->_request->getParams();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
        $orgid= $customerObj->getDivauserid();
		$website->getWebsiteId();
		$resultpage=$this->_distibutorFactory->create()->getCollection()->addFieldToFilter('website_id',$website->getWebsiteId());
		foreach($resultpage as $categorydata)
		{
			$rootid=$categorydata->getRootCategoryId();
		}
		$category = $objectManager->create('Magento\Catalog\Model\Category')->load($rootid);
         $subcategories=$category->getChildrenCategories();
		 if(count($subcategories)!=0)
		 {
			$this->_messageManager->addError(__($website->getName()." Store Can't be Deleted as It has Categories; "));
			//$resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect->setUrl('*/*/');
			
		 }
		 else
		{
			$categorydata=array();
			$categorydata['entity']="store";
			$categorydata['elements']=array( array("entityId"=>$website->getWebsiteId(),"entityName"=>$website->getName(),"action"=>"delete","parent"=>0,"parentId"=>0,"active"=>0,
					"orgId"=>$orgid));
			
			$content =  json_encode($categorydata,JSON_NUMERIC_CHECK);
			$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
			 $imageFile = $imageHelper->callapis($content);
			 if($imageFile['status'] === "success")
			 {
				$webhook=$this->_webhookFactory->create();
				$webhook->setJobid($imageFile['jobid']);
				$webhook->setEntityName('Store');
				$webhook->setEntityId($website->getName());
				$webhook->setPayload($content);
				$webhook->setStatus('inprogress');
				$webhook->setCreateaction('Delete');
				$webhook->save();
				$this->_messageManager->addSuccess(__($website->getName().' Delete api Sucessfully  Sync With Web Hook'));
			 }
			 else
			 { 
				 //$this->_messageManager->addError(__($website->getName()." Unable To Delete Store Please Try Again"));
				 return $resultRedirect->setUrl('*/*/');
			 }
		 }
		
		
	     
		
		
		
		
	}
}