<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

use \Magento\Store\Model\StoreRepository;

class Syncategories extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $_storeRepository;
    protected $storeCollectionFactory;
	protected $category;
	protected $categoryRepository;
	protected $_objectManager;
	protected $_webhookFactory;
    /**
     * @param \Magento\Framework\App\Action\Context           $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, 
	\Magento\Framework\View\Result\PageFactory $resultPageFactory, 
	\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
	\Magento\Store\Model\GroupFactory $storeCollectionFactory,
	StoreRepository $storeRepository,
	\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $category,
    \Magento\Catalog\Model\CategoryRepository $categoryRepository,
	\Magento\Framework\ObjectManagerInterface $objectManager,
	\Netenrich\Webhook\Model\WebhookFactory $webhookFactory,
	
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->resultRawFactory = $resultRawFactory;
		$this->_storeRepository = $storeRepository;
		$this->storeCollectionFactory = $storeCollectionFactory;
		$this->category = $category;
        $this->categoryRepository = $categoryRepository;
		$this->_objectManager = $objectManager;
		$this->_webhookFactory = $webhookFactory;
	
        parent::__construct($context,$data);
    }

    public function execute()
    {
	
	     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
         $orgid= $customerObj->getDivauserid();
		 
	    $categories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("level",2);
        $categorydata=array();
		
        $categorydata['entity']="category";
		foreach ($categories as $category) 
		{
		
			$rootid=explode("/",$category->getPath());
			$storeid=$this->storeCollectionFactory->create()->getCollection()->addFieldToFilter('root_category_id',$rootid[1]);
			 foreach($storeid as $storegroup)
			 {
				 $store=$storegroup->getWebsiteId();
			 }
			 
			 
			  $categorydata['elements'][]=array("entityId"=>$category->getId(),"entityName"=>$category->getName(),"action"=>"insert","parent"=>0,"parentId"=>$store,"active"=>$category->getIsActive(),
					"orgId"=>$orgid);
			
            
				
			 
        }
		
		 $content = json_encode($categorydata, JSON_NUMERIC_CHECK); 
	   $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
	    $imageFile = $imageHelper->callapis($content); 	
		

		
		if($imageFile['status'] === "success")
		{
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('All Categories');
			$webhook->setEntityId('Categories');
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('insert');
			$webhook->save();
			$this->messageManager->addSuccess(__('The Catgories Data with webhook is in progress.'.$categories->getSize()));
		}
		else
		{
			$this->messageManager->addError(__('The Catgories  Not Sync with webhook Please try again.'));
		}
       
	  $this->_redirect('*/*/');
       return;
		
        
		
       
        
       	   
	}
}
