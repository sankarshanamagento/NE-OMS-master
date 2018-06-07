<?php namespace Netenrich\Services\Model\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class Servicessave implements ObserverInterface
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
	
 
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\App\RequestInterface $request,
		\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
		\Magento\Store\Model\GroupFactory $storeManager,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Customer\Model\Customer $customerRepository
    ) {
        $this->_objectManager = $objectManager;
		$this->_request = $request;
		$this->_eavAttributeRepository = $eavAttributeRepository;
		$this->_storeManager = $storeManager;
		$this->productRepository = $productRepository;
		$this->_customerRepository = $customerRepository;
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
			
		print_r($post);
exit 
		
	}
}