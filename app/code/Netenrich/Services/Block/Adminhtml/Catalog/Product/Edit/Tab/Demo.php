<?php
namespace Netenrich\Company\Block\Adminhtml\Catalog\Product\Edit\Tab;
 
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
 
class Demo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'product/edit/demo.phtml';
 
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;
	protected $_servicesCollectionFactory;
 
    public function __construct(
        Context $context,
        Registry $registry,
		\Netn\Distibutorproducts\Model\ResourceModel\Distibutorproducts\Collection $companyCollectionFactory,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
		$this->_servicesCollectionFactory = $companyCollectionFactory;
        parent::__construct($context, $data);
    }
 
    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }
	
	public function getCompanyCollection()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product
    
        $collection =$this->_servicesCollectionFactory
		->addFieldToFilter('productid', array('eq' => $product->getId()))
		->load();
		
		  $this->setCollection($collection);

			//parent::_prepareCollection();
			
			$productids= array();
				foreach($collection as $product)
				{
					$productids[]=$product->getDistibutor_id();
				}
					 $productvalues=implode(",",$productids);
							  
					 return $productvalues;
    }
 
}