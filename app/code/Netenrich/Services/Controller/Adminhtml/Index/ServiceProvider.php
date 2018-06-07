<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;



class ServiceProvider extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $_collectionFactory;
    protected $_productCollectionFactory;
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
	\Netenrich\Services\Model\ServicesFactory $collectionFactory,
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->resultRawFactory = $resultRawFactory;
		$this->_productCollectionFactory = $productCollectionFactory; 
		$this->_collectionFactory=$collectionFactory;
	
        parent::__construct($context,$data);
    }

    public function execute()
    {
		$collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
		$collection->distinct(true);
		
		foreach($collection as $product)
		{
			
			$categories=$product->getCategoryIds();
			
			
			/* categories list find out the subcategory or categories from list of products */
			if(count($categories)>=2)
			   {
				   foreach($categories as $data=>$value)
				{
					
					$category = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($value);
						if($category->getLevel()==2)
					 {
						 $categoryid=$value;
					 }
					 else if($category->getLevel()==3)
					 {
						 $subcategoryid=$value;
					 }
				}
			   }
			   else if(count($categories)==1)
			   {
				   $category = $this->_objectManager->create('Magento\Catalog\Model\Category')->load($categories[0]);
						if($category->getLevel()==2)
					 {
						 $categoryid=$categories[0];
						 $subcategoryid="";
					 }
					 else if($category->getLevel()==3)
					 {
						 $subcategoryid=$categories[0];
						 $categoryid=$category->getParentCategory()->getId();
					 }
			   }
			/* categories list find out the subcategory or categories from list of products */
			$storeid=$product->getStoreIds();
			$customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load(1);
            $orgid= $customerObj->getDivauserid();
			
			$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
			
			 if($imageHelper->getcounts($product->getPartnumber())==0)
						 {
							 
							 
							 
			                  $modelcsv = $this->_collectionFactory->create();
                              $modelcsv->setPartnumber($product->getPartnumber());
							  $modelcsv->setServicename($product->getName());
							  $modelcsv->setTowers($product->getTowers());
							  $modelcsv->setAssets($product->getAssets());
							  $modelcsv->setPayable($product->getPayable());
							  $modelcsv->setContractterm($product->getContractterm());
							  $modelcsv->setServicePackage($product->getServicePackage());
							  $modelcsv->setServicelevel($product->getServicelevel());
							  $modelcsv->setServiceType($product->getServiceType());
							  $modelcsv->setPractice($product->getPractice());
							  $modelcsv->setCategoryid($categoryid);
							  $modelcsv->setSubcategoryid($subcategoryid);
							  $modelcsv->setStore($storeid[0]);
							  $modelcsv->save();
							  
							  $categorydata=array("serviceId"=>$modelcsv->getId(),
								"partNumber"=>$product->getPartnumber(),
								"serviceName"=>$product->getName(),
								"storeId"=>$storeid[0],
								"categoryId"=>$categoryid,
								"subCategoryId"=>$subcategoryid,
								"packageId"=>$product->getServicePackage(),
								"serviceLevelId"=>$product->getServicelevel(),
								"serviceTypeId"=>$product->getServiceType(),
								"contractTermId"=>$product->getContractterm(),
								"orgId"=>$orgid,
								"action" => "insert");
							  
							$content = json_encode($categorydata, JSON_NUMERIC_CHECK);
					         $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
					         $imageFile = $imageHelper->Servicesapis($content);
							  if($imageFile['status'] === "success")
					           {
								   
								   $webhook=$this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
										$webhook->setJobid($imageFile['jobid']);
										$webhook->setEntityName('services');
										$webhook->setEntityId($product->getName());
										$webhook->setPayload($content);
										$webhook->setStatus('inprogress');
										$webhook->setCreateaction('insert');
										$webhook->save();
						          
					           }
							   else
							   {
								  $this->messageManager->addError(__($data['partnumber'].'  UnSucessfully  Sync With Web Hook Please edit once again'));  
							   } 
							   
						 }
						 else
						 {
							 
							  $missedpartnumbers[]=$product->getPartnumber();
							  $string=implode(",",$missedpartnumbers);
						 } 
						 
			
			
			
		} 
		 if(isset($string))
		{
		$this->messageManager->addError(__('Following Part Numbers '.$string.'  already Exits in Database.'));
		}
		$this->messageManager->addSuccess(__('The Catalog Sny Data has been saved .')); 
		$this->_redirect('*/*/');
        return;
		//print_r($selectlist);
     	
    }
}
