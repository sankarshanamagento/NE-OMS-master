<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

use \Magento\Store\Model\StoreRepository;

class Synsubcategories extends \Magento\Backend\App\Action
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
		 
	    	
		

		
		$subcategories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("level",3);
        $subcategorydata=array();
		
        $subcategorydata['entity']="subcategory";
		foreach ($subcategories as $subcategory) 
		{
		
			$subrootid=explode("/",$subcategory->getPath());
			$substoreid=$this->storeCollectionFactory->create()->getCollection()->addFieldToFilter('root_category_id',$subrootid[1]);
			 foreach($substoreid as $substoregroup)
			 {
				 $substore=$substoregroup->getWebsiteId();
			 }
			 
			 
			  $subcategorydata['elements'][]=array("entityId"=>$subcategory->getId(),"entityName"=>$subcategory->getName(),"action"=>"insert","parent"=>$substore,"parentId"=>$subcategory->getParentCategory()->getId(),"active"=>$subcategory->getIsActive(),
					"orgId"=>$orgid);
			
            
		
        }
		
	    $subcontent = json_encode($subcategorydata, JSON_NUMERIC_CHECK); 
	   			
	       $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
	        $imageFile = $imageHelper->callapis($subcontent); 
			if($imageFile['status'] === "success")
					{
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('All SubCategories');
			$webhook->setEntityId('SubCategories');
			$webhook->setPayload($subcontent);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('insert');
			$webhook->save();
					   $this->messageManager->addSuccess(__('The Subcatgories  Data with webhook is in progress.'.$subcategories->getSize()));
					}
					else{
						 $this->messageManager->addError(__('The subCatgories  Not Sync with webhook Please try again.'));
					}
       
	  $this->_redirect('*/*/');
       return;
		
        
		
       
        
       	   
	}
}
