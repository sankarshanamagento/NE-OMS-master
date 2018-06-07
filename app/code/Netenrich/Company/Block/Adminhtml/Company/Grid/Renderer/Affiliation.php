<?php

namespace Netenrich\Company\Block\Adminhtml\Company\Grid\Renderer;

//namespace Your\Module\Block\Widget\Grid\Column\Renderer;

use Magento\Backend\Block\Context;
use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
//use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Registry;

class Affiliation extends AbstractRenderer {

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

      //  $orgId = $row->getData('org_id');
        $parentId = $row->getData('parent_id');
        // $value = parent::_getValue($row);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        echo $orgName = $connection->fetchOne("SELECT name from netenrich_organisation_type where divauniqueid='" . $parentId . "'");
        // return $orgName;
    }

}
