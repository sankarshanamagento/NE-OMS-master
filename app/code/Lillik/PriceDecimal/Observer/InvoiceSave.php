<?php namespace Lillik\PriceDecimal\Observer;
 
use Magento\Framework\Event\ObserverInterface;

 
class InvoiceSave implements ObserverInterface
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
		$reqeustParams = $this->_request->getParams();
		$order = $observer->getOrder();
		print_r($order);
		print_r($reqeustParams);
		exit;
	}
}
