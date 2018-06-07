<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Checkstore implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_distibutorFactory;
	protected $_storeManager;
	protected $_messageManager;
    protected $redirect;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		
		\Magento\Store\Model\GroupFactory  $distibutorFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Framework\App\Response\RedirectInterface $redirect
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_distibutorFactory = $distibutorFactory;
		$this->_storeManager = $storeManager;
		$this->_messageManager = $messageManager;
		$this->redirect = $redirect;
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
		$reqeustParams = $this->_request->getParams();
		//print_r($reqeustParams);
		if($reqeustParams['store_action']=="edit")
		{	
	    $storeid=$this->_distibutorFactory->create()->getCollection()->addFieldToFilter('root_category_id',$reqeustParams['group']['root_category_id']);
				
				if($storeid->getSize()==2)
				 {
					 
					 $this->_messageManager->addError(__($reqeustParams['group']['name']." Root category already mapped with Another store  "));
			         $redirectUrl = $this->redirect->getRedirectUrl();
			          header("Location:".$redirectUrl);
			        exit;
				 }
				
		}
		else
		{
			$storeid=$this->_distibutorFactory->create()->getCollection()->addFieldToFilter('root_category_id',$reqeustParams['group']['root_category_id']);
				
				if($storeid->getSize() != 1)
				 {
					 $this->_messageManager->addError(__($reqeustParams['group']['name']." can't be create Root category already mapped with Another store"));
			         $redirectUrl = $this->redirect->getRedirectUrl();
			         header("Location:".$redirectUrl);
			         exit;
				 }
		}
		
		
		
		
		
	}
}