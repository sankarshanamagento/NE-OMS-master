<?php namespace Netenrich\Company\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class productSavebefore implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_distibutorFactory;
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Netn\Distibutorproducts\Model\DistibutorproductsFactory $distibutorFactory
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_distibutorFactory = $distibutorFactory;
    }
 
    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
          $product = $observer->getProduct();
		  
	      $productid =$product->getId();
		  $productnumber =$product->getPartnumber();
		  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		$serivcescount = $objectManager->create('Netenrich\Services\Model\Services')->getCollection()->addFieldToFilter('partnumber',$productnumber);
		if($serivcescount->count()==1)
		{
			foreach($serivcescount as $services)
			{
				$categories=array($services->getCategoryid(),$services->getSubcategoryid());
				$product->setName($services->getServicename());
				$product->setPractice($services->getPractice());
				$product->setTowers($services->getTowers());
				$product->setServicelevel($services->getServicelevel());
				$product->setServicePackage($services->getServicePackage());
				$product->setContractterm($services->getContractterm());
				$product->setPayable($services->getPayable());
				$product->setAssets($services->getAssets());
				$product->setCategoryIds($categories);
				$product->setWebsiteIds(array($services->getStore()));
			}
		}
		  
		  //$this->saveProductdata($productid,$productnumber);
	      //$productname =$product->setName('sampleone');
		  $post=$reqeustParams = $this->_request->getParams();
		
		  
		  
		  
		  
    }
	
	
	    
		
      
	
}