<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Attributebeforesave implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_eavAttributeRepository;
	protected $_storeManager;
	private $productRepository;
    protected $_messageManager;
    protected $redirect;	
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
		\Magento\Store\Model\GroupFactory $storeManager,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Framework\App\Response\RedirectInterface $redirect
		
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_eavAttributeRepository = $eavAttributeRepository;
		$this->_storeManager = $storeManager;
		$this->productRepository = $productRepository;
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
		$post=$reqeustParams = $this->_request->getParams();
		if(isset($post['attribute_id']))
		{
		if($post['attribute_id']!=135 && $post['attribute_id']!=141 && $post['attribute_id']!=151 && $post['attribute_id']!=136)
		{
			return false;
		}
		else{
		if($post['attribute_id']==135)
			{
				$attribute='service_package';
				$fieldcode='service_package';
			}
			else if($post['attribute_id']==141)
			{
				$attribute="servicelevel";
				$fieldcode='servicelevel';
			}
			else if($post['attribute_id']==151)
			{
				$attribute="service_type";
				$fieldcode='service_type';
			}
			
			else if($post['attribute_id']==136)
			{
				$attribute="contractterm";
				$fieldcode='contractterm';
			}
		//print_r($post['option']['delete']);
        foreach($post['option']['delete'] as $ids=>$value)
		{
			if($value!="")
			{
			 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			 $serivcescount = $objectManager->create('Netenrich\Services\Model\Services')->getCollection()->addFieldToFilter($fieldcode,$ids)->count();
			 $option=$this->getoptionlabelhelper($attribute,$ids);
			 
		
			if($serivcescount!=0)
		   {
			   
			$this->_messageManager->addError(__($option." Can't be deleted as it is Mapped to one of the services"));
			$redirectUrl = $this->redirect->getRedirectUrl();
			header("Location:".$redirectUrl);
			exit; 
			/* don't remove this code reason Place here because it is continuing to the processes*/
            			
		   }
			}
		}			
		
		$attributes = $this->_eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attribute);
		$options = $attributes->getSource()->getAllOptions(true);
		$optionarray=array();
		foreach($options as $id=>$values)
		{
			$optionarray[$values['value']]=$values['label'];
			
		}
		
		$content =  json_encode($optionarray);
		$path = $_SERVER['DOCUMENT_ROOT'] . "var/attributebefore.txt";
		$fp = fopen($path,"wb");
			fwrite($fp,$content);
            fclose($fp);
		
		}	

		}		
		
	}
	
	public function getoptionlabelhelper($attribute,$ids)
	{
		
		$attributes = $this->_eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attribute);
		$options = $attributes->getSource()->getAllOptions(true);
		
		foreach($options as $id=>$values)
		{
			if($values['value']==$ids)
				{
					return $values['label'];
				}
			
		}
	}
}
