<?php

namespace Netenrich\Services\Block\Adminhtml\Services;

/**
 * Adminhtml Services grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Netenrich\Services\Model\ResourceModel\Services\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Netenrich\Services\Model\Services
     */
    protected $_Services;
	
	protected $eavAttributeRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Netenrich\Services\Model\Services $ServicesPage
     * @param \Netenrich\Services\Model\ResourceModel\Services\CollectionFactory $collectionFactory
     * @param \Magento\Core\Model\PageLayout\Config\Builder $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Netenrich\Services\Model\Services $Services, 
	\Netenrich\Services\Model\ResourceModel\Services\CollectionFactory $collectionFactory,
	\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
	array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
		 $this->eavAttributeRepository = $eavAttributeRepository;
        $this->_Services = $Services;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('servicesGrid');
        $this->setDefaultSort('part_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection
     *
     * @return \Magento\Backend\Block\Widget\Grid
     */
    protected function _prepareCollection() {
        $collection = $this->_collectionFactory->create();
        /* @var $collection \Netenrich\Services\Model\ResourceModel\Services\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
	

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {
        $this->addColumn('part_id', [
            'header' => __('ID'),
            'index' => 'part_id',
        ]);

		
		
         $towerattribute="towers";
		$tremattribute="contractterm";
		$packageattribute="service_package";
		$payattribute="payable";
		$levelattribute="servicelevel";
		$assetsattribute="assets";
        $this->addColumn('partnumber', ['header' => __('Part Number'), 'index' => 'partnumber']);
		 $this->addColumn('servicename', ['header' => __('Service Name'), 'index' => 'servicename']);
        $this->addColumn('service_package', ['header' => __('Service Package'), 'index' => 'service_package','type' =>'options','options' => $this->getOptionlist($packageattribute),'align' => 'center']);
        $this->addColumn('servicelevel', ['header' => __('Service Level'), 'index' => 'servicelevel','type' =>'options',
                'options' => $this->getOptionlist($levelattribute),'align' => 'center']);
       /*  $this->addColumn('towers', ['header' => __('Towers'), 'index' => 'towers','type' => 'options',
                'options' => $this->getOptionlist($towerattribute),'align' => 'center']); */
       /*  $this->addColumn('contractterm', ['header' => __('Contract Term'), 'index' => 'contractterm','type' => 'options',
                'options' => $this->getOptionlist($tremattribute),'align' => 'center']);
        //$this->addColumn('var', ['header' => __('VAR'), 'index' => 'var']);
        $this->addColumn('payable', ['header' => __('Payable'), 'index' => 'payable', 'type' => 'options',
                'options' => $this->getOptionlist($payattribute),'align' => 'center'] ); */



        $this->addColumn(
                'action', [
            'header' => __('Edit'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => [
                [
                    'caption' => __('Edit'),
                    'url' => [
                        'base' => '*/*/edit',
                        'params' => ['store' => $this->getRequest()->getParam('store')]
                    ],
                    'field' => 'part_id'
                ]
            ],
            'sortable' => false,
            'filter' => false,
            'header_css_class' => 'col-action',
            'column_css_class' => 'col-action'
                ]
        );
		
		

        return parent::_prepareColumns();
    }

    /**
     * Row click url
     *
     * @param \Magento\Framework\Object $product
     * @return string
     */
    public function getRowUrl($product) {
		//print_r($product);
        //return $this->getUrl('*/*/edit', ['org_id' => $product->getId(),'parentid'=> $product->getParent_id()]);
		return $this->getUrl('*/*/edit', array('part_id'=>$product->getId()));
    }

	
	protected function _prepareMassaction()
    {
        $this->setMassactionIdField('part_id');
        $this->getMassactionBlock()->setFormFieldName('part_id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('services/*/massDelete'),
                'confirm' => __('Are you sure?')
            )
        );
        return $this;
    }
	
	public function getOptionlist($attrubute){
        $attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attrubute);
        $options = $attributes->getSource()->getAllOptions(true);
		$optionsvalues=array();
        foreach($options as $id=>$values)
		{
			$optionsvalues[$values['value']] = $values['label'];
			
		}
		return $optionsvalues;		
    }
    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
	


}
