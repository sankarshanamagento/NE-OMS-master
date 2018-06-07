<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Attributesave implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
	protected $_request;
	protected $_eavAttributeRepository;
	protected $_storeManager;
	private $productRepository; 
	protected $_customerRepository;
	protected $_messageManager;
	protected $_webhookFactory;
	
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
		\Magento\Store\Model\GroupFactory $storeManager,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Customer\Model\Customer $customerRepository,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Netenrich\Webhook\Model\WebhookFactory $webhookFactory
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_eavAttributeRepository = $eavAttributeRepository;
		$this->_storeManager = $storeManager;
		$this->productRepository = $productRepository;
		$this->_customerRepository = $customerRepository;
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
				$name="package";
			}
			else if($post['attribute_id']==141)
			{
				$attribute="servicelevel";
				$name="servicelevel";
			}
			
			else if($post['attribute_id']==151)
			{
				$attribute="service_type";
				$name="servicetype";
			}
			
			else if($post['attribute_id']==136)
			{
				$attribute="contractterm";
				$name='contractterm';
			}
	    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
        $orgid= $customerObj->getDivauserid();
		$attributes = $this->_eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attribute);
		$options = $attributes->getSource()->getAllOptions(true);
		$optionarray=array();
		
		foreach($options as $id=>$values)
		{
			$optionarray[$values['value']]=$values['label'];
		}
		
		$createarray=array();
		$createarray['entity']=$name;
		
		////////////////////////////////////////////////////
		/* for find out of the edited labels in Attribute */
		///////////////////////////////////////////////////
		$fh = fopen($_SERVER['DOCUMENT_ROOT'] . "var/attributebefore.txt",'r');
         while ($line = fgets($fh)) {
			$lineoption=str_replace("{","",$line);
			 $options=str_replace("}","",$lineoption);
             $labels=explode(",",$options);
			 }
       fclose($fh);
	   
	   $beforeoption=array();
	   foreach($labels as $id=>$data)
	   {
		  $options=str_replace('"',"",$data);
		  $splitoption=explode(':',$options);
		  $beforeoption[$splitoption[0]]=$splitoption[1];
	   }
	   
	   

  	   
      
	     $newarray=array_diff_key($optionarray,$beforeoption);
		 
		 foreach($newarray as $value=>$text)
		 {
			$i[]= $value;
			$createarray['elements'][]=array("entityId"=>$value,"entityName"=>$text,"action"=>"insert","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);
		  }
	     
          $result=array_diff($optionarray,$beforeoption);
           $j=0;
		   
		  foreach($result as $ids=>$labels)
			 { 
			    if(isset($newarray[$ids])) {
                  
                 }
				 else
				 {
			     $createarray['elements'][]=array("entityId"=>$ids,"entityName"=>$labels,"action"=>"update","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);
				 }     
			}
	   
		/* End of the coding for the attributes changed  Attribute */
		
		
		
		
		
		foreach($post['option']['delete'] as $ids=>$value)
		{
			if($value!="")
			{
				
				
				$createarray['elements'][]=array("entityId"=>$ids,"entityName"=>"","action"=>"delete","parent"=>0,"parentId"=>0,"status"=>0,"orgId"=>$orgid);

			}
		}
		
		if(count($createarray)==1)
		{
			return false;
		}		
	
		
		$content =  json_encode($createarray,JSON_NUMERIC_CHECK);
		
		
		$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
		$imageFile = $imageHelper->callapis($content);
		
		if($imageFile['status'] === "success")
		 {
			 
			$webhook=$this->_webhookFactory->create();
			$webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName($name);
			$webhook->setEntityId('Attribute');
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('Attribute Action');
			$webhook->save();
			$this->_messageManager->addSuccess(__($attribute.' Sucessfully  Sync With Web Hook'));
		 }
		 else
		 { 
	        $this->_messageManager->addError(__($attribute.'  UnSucessfully  Sync With Web Hook Please edit once again')); 
	    }
				
		}	
		
		}
	}
}
