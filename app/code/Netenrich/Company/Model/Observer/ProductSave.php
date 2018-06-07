<?php namespace Netenrich\Company\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class productSave implements ObserverInterface
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
		  //$this->saveProductdata($productid,$productnumber);
	      $productname =$product->getName();
		  $post=$reqeustParams = $this->_request->getParams();

		 if(isset($post['distibutors']))

		 {
		 if($post['distibutors']=="")
		  {
			  $post['distibutors']=$post['sdistibutors'];
		  }
		 }
		 
		  
		  if(isset($post['distibutors']))
		  {
			  $distibutorsList=explode(",",$post['distibutors']);
			  
			  $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()
                  ->get('Magento\Framework\App\ResourceConnection');
                   $connection= $this->_resources->getConnection();

	         $themeTable = $this->_resources->getTableName('distibutorproducts_distibutorproducts');
				$sql = "Delete from " . $themeTable . " where productid =".$productid;
				$connection->query($sql);
			  
			  foreach($distibutorsList as $k)
			  {
				  
				  $distibutorModel=$this->_distibutorFactory->create();
				  $distibutorModel->setProductid($productid);
				  $distibutorModel->setProductname($productname);
				  $distibutorModel->setDistibutorId($k);
				  $distibutorModel->save();
				  
			  }
		  }
		  
		  
		  
		  
    }
	
	public function  saveProductdata($productid,$partnumber)
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$_product = $objectManager->create('Magento\Catalog\Model\Product');
		$serivcescount = $objectManager->create('Netenrich\Services\Model\Services')->getCollection()->addFieldToFilter('partnumber',$partnumber);
		if($serivcescount->count()==1)
		{
			
		}

		}
	    
		
      
	
}