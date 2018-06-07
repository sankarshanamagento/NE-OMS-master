<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Editstore implements ObserverInterface
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
		//$storeManager =  $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
		$post=$reqeustParams = $this->_request->getParams();
		
		if($post['store_action'] === "add")
		{
			
		}
		else
		{
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
			$orgid= $customerObj->getDivauserid();
		
			$categorydata=array();
			$categorydata['entity']="store";
			$categorydata['elements']=array( array("entityId"=>$website->getWebsiteId(),"entityName"=>$website->getName(),"action"=>"insert","parent"=>0,"parentId"=>0,"active"=>0,
				"orgId"=>$orgid));
		
			$content =  json_encode($categorydata,JSON_NUMERIC_CHECK);
		    $imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		    $imageFile = $imageHelper->callapis($content);
			
			
			 if($imageFile['status'] === "success")
		 {
			
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('store');
			$webhook->setEntityId($website->getName());
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('edit');
			$webhook->save();
			$this->_messageManager->addSuccess(__($website->getName().' Sucessfully  Sync With Web Hook'));
		 }
		 else
		 { 
	        $this->_messageManager->addError(__($website->getName().'  UnSucessfully  Sync With Web Hook Please edit once again')); 
	     } 
			
		}
		
		
		
	}
}