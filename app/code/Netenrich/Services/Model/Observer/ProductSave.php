<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class productSave implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_webhookFactory;
	protected $_storeManager;
	protected $_messageManager;
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Netenrich\Webhook\Model\WebhookFactory $webhookFactory,
		\Netenrich\Services\Helper\Data $dataHelper,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_webhookFactory = $webhookFactory;
		$this->_dataHelper = $dataHelper;
		$this->_storeManager = $storeManager;
		$this->_messageManager = $messageManager;
    }
 
    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$store = $observer->getEvent()->getStore();
		
		$post=$reqeustParams = $this->_request->getParams();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
        $orgid= $customerObj->getDivauserid();
		$categorydata=array();
		
		
		$websitename=$this->_storeManager->getWebsite($store->getWebsiteId())->getName();
		if($post['store_action']=='add')
		{
			$action="insert";
		}
		else
		{$action="edit";}
	    $categorydata['entity']="store";
		$categorydata['elements']=array( array("entityId"=>$store->getWebsiteId(),"entityName"=>$websitename,"action"=>'insert',"parent"=>'0',"parentId"=>0,"active"=>0,"orgId"=>$orgid));
		
		$content =  json_encode($categorydata,JSON_NUMERIC_CHECK);
		
		 
		 $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		 $imageFile = $imageHelper->callapis($content);
		 
		 if($imageFile['status'] === "success")
		 {
			 
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('store');
			$webhook->setEntityId($websitename);
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction($action);
			$webhook->save();
			
			$this->_messageManager->addSuccess(__($websitename.' Sucessfully  Sync With Web Hook'));
		}
		 else
		 { 
	        $this->_messageManager->addError(__($websitename.'  UnSucessfully  Sync With Web Hook Please edit once again')); 
	     } 
	}
}