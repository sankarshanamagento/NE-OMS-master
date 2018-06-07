<?php

namespace Netenrich\Services\Block\Adminhtml\Services\Grid\Renderer;

//namespace Your\Module\Block\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
//use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Registry;

class Organisation extends AbstractRenderer {

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var AttributeFactory
     */
    //  protected $attributeFactory;

    /**
     * Manufacturer constructor.
     * @param AttributeFactory $attributeFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
    Registry $registry,
    //  AttributeFactory $attributeFactory,
            Context $context, array $data = array()
    ) {
        //$this->attributeFactory = $attributeFactory;
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Renders grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return mixed
     */
    public function _getValue(\Magento\Framework\DataObject $row) {
        // Get default value:
        $value = parent::_getValue($row);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $orgName = $connection->fetchOne("SELECT org_name from netenrich_organisation where org_type_id='" . $value . "'");
        return $orgName;
    }

}
