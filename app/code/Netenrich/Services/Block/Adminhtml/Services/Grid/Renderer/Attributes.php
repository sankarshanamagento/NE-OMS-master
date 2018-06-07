<?php


namespace Netenrich\Services\Block\Adminhtml\Services\Grid\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Registry;

class Attributes extends AbstractRenderer
{
    /**
     * @var Registry
     */
    protected $registry;
    
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
        Context $context,
        array $data = array()
    )
    {
        $this->attributeFactory = $attributeFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function _getValue(\Magento\Framework\DataObject $row)
    {
        // Get default value:
        $value = parent::_getValue($row);
		
        
        $options = $this->registry->registry('payable_options');

        if (!$options) {
            $options = $this->attributeFactory->create()->loadByCode('catalog_product', 'payable')->getOptions();
            $this->registry->register('payable_options', $options);
        }
        
        foreach ($options as $option) {
            if ($option->getValue() == $value) {
                return $option->getLabel();
            }
        }
        
        return $value;
    }
}
