<?php
 
namespace Netn\Distibutorproducts\Block\Widget\Grid\Column\Renderer;
 
use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Registry;
 
class Distibutorname extends AbstractRenderer
{
    /**
     * @var Registry
     */
    protected $registry;
	protected $_companyCollectionFactory;
    
    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;
    
    /**
     * Manufacturer constructor.
     * @param AttributeFactory $attributeFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        AttributeFactory $attributeFactory,
		\Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $companyCollectionFactory,
        Context $context,
        array $data = array()
    )
    {
        $this->attributeFactory = $attributeFactory;
		$this->_companyCollectionFactory = $companyCollectionFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }
 
    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function render(\Magento\Framework\DataObject $row){
   $customerId = $row->getData($this->getColumn()->getIndex());
    
	$parentId = $row->getData('parent_id');
        // $value = parent::_getValue($row);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        echo $orgName = $connection->fetchOne("SELECT name from netenrich_organisation_type where org_id='" . $customerId . "'");
 }
	
	
}