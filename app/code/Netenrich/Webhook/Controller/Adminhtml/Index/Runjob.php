<?php

namespace Netenrich\Webhook\Controller\Adminhtml\Index;



class Runjob extends \Magento\Backend\App\Action
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
	\Netenrich\Webhook\Model\WebhookFactory $collectionFactory,
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
		$apiid=$this->getRequest()->getParam('org_id');
		$collection = $this->_collectionFactory->create()->load($apiid);
        
		 
		
		
		
	    if($data_string=$collection->getEntityName()=='services')
		{
			
			$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		    $imageFile = $imageHelper->Servicesapis($collection->getPayload());
			if($imageFile['status'] === "success")
					           {
								   
								   $webhook=$this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
										$webhook->setJobid($imageFile['jobid']);
										$webhook->setEntityName($collection->getEntityName());
										$webhook->setEntityId($collection->getEntityId());
										$webhook->setPayload($collection->getPayload());
										$webhook->setStatus('inprogress');
										$webhook->setCreateaction($collection->getCreateaction());
										$webhook->save();
						          
					           }
							   else
							   {
								  $this->messageManager->addError(__('  UnSucessfully  Sync With Web Hook Please Try again'));  
							   } 
		}
		else
		{
			;
			
			$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		    $imageFile = $imageHelper->callapis($collection->getPayload());
			if($imageFile['status'] === "success")
					           {
								   
								   $webhook=$this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
										$webhook->setJobid($imageFile['jobid']);
										$webhook->setEntityName($collection->getEntityName());
										$webhook->setEntityId($collection->getEntityId());
										$webhook->setPayload($collection->getPayload());
										$webhook->setStatus('inprogress');
										$webhook->setCreateaction($collection->getCreateaction());
										$webhook->setEcomid();
										$webhook->save();
						          
					           }
							   else
							   {
								  $this->messageManager->addError(__('  UnSucessfully  Sync With Web Hook Please Try once again'));  
							   } 
		}
		
		
		

	
		
		
		$this->messageManager->addSuccess(__('The Job Re run Sny Data has been saved .')); 
		$this->_redirect('*/*/');
        return;
		//print_r($selectlist);
     	
    }
}
