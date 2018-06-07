<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

use \Magento\Store\Model\StoreRepository;

class Getcategories extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $_storeRepository;
    protected $storeCollectionFactory;
	protected $category;
	protected $categoryRepository;
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
	
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->resultRawFactory = $resultRawFactory;
		$this->_storeRepository = $storeRepository;
		$this->storeCollectionFactory = $storeCollectionFactory;
		$this->category = $category;
        $this->categoryRepository = $categoryRepository;
	
        parent::__construct($context,$data);
    }

    public function execute()
    {
	
       $post=$reqeustParams = $this->_request->getParams();
	  
	   if(isset($post['storeid']))
	   {
	   $storeCollection = $this->storeCollectionFactory->create()->getCollection()->addFieldToFilter('website_id',$post['storeid']);
	   foreach($storeCollection as $str)
	   {
		   $rootid=$str->getRootCategoryId();
	   }
	   
	   $categories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("level",2)->addAttributeToFilter('parent_id',$rootid);
        $childrenData = [];
        foreach ($categories as $category) {
            $childrenData[$category->getId()] = $category->getName();
        }
       
	    echo json_encode($childrenData);
	   }
	   else if(isset($post['categoryid']))
	   {
		
        $categories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("level",3)->addAttributeToFilter('parent_id',$post['categoryid']);
        $childrenData = [];
        foreach ($categories as $category) {
            $childrenData[$category->getId()] = $category->getName();
        }
       echo json_encode($childrenData);		
	   }
        
		
       
        
       	   
	}
}
