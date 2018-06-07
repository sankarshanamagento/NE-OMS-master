<?php

namespace Netenrich\Company\Block\Adminhtml\Company;

/**
 * Adminhtml Company grid
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory
     */
	 
	const ENABLED  = 1;
    const DISABLED = 0;
	
    protected $_collectionFactory;

    /**
     * @var \Netenrich\Company\Model\Company
     */
    protected $_Company;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Netenrich\Company\Model\Company $CompanyPage
     * @param \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $collectionFactory
     * @param \Magento\Core\Model\PageLayout\Config\Builder $pageLayoutBuilder
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, \Magento\Backend\Helper\Data $backendHelper, \Netenrich\Company\Model\Company $Company, \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $collectionFactory, array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_Company = $Company;
        parent::__construct($context, $backendHelper, $data);
		
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();
		
        $this->setId('companyGrid');
        $this->setDefaultSort('org_id');
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
        /* @var $collection \Netenrich\Company\Model\ResourceModel\Company\Collection */
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }
	

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {
        $this->addColumn('org_id', [
            'header' => __('ID'),
            'index' => 'org_id',
        ]);


        $this->addColumn('name', ['header' => __('Company Name'), 'index' => 'name']);
        $this->addColumn('address', ['header' => __('Address'), 'index' => 'address']);
        $this->addColumn('address2', ['header' => __('City'), 'index' => 'city']);
        $this->addColumn('org_type_id', ['header' => __('Company Type'), 'index' => 'org_type_id', 'renderer' => 'Netenrich\Company\Block\Adminhtml\Company\Grid\Renderer\Organisation','filter_condition_callback' => array($this, '_addressFilter')]);
        $this->addColumn('parent_id', ['header' => __('Affiliation'), 'index' => 'parent_id', 'renderer' => 'Netenrich\Company\Block\Adminhtml\Company\Grid\Renderer\Affiliation']);
        //$this->addColumn('var', ['header' => __('VAR'), 'index' => 'var']);
        //$this->addColumn('payment_method', ['header' => __('Payment Method'), 'index' => 'payment_method']);
		$this->addColumn('status', ['header' => __('Status'), 'index' => 'status','type' => 'options','options'=> $this->toOptionArray()]);



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
                    'field' => 'org_id'
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
     * @param \Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row) {
		//print_r($row);
        //return $this->getUrl('*/*/edit', ['org_id' => $row->getId(),'parentid'=> $row->getParent_id()]);
		return $this->getUrl('*/*/edit', array('org_id'=>$row->getId(), '_query'=>array('parentid'=>'dgvgsvg')));
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl() {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }
	
	 public function toOptionArray()
    {
         $options = [
            self::ENABLED => __('Active'),
            self::DISABLED => __('Inactive')
        ]; 
 
        return $options;
    }

    protected function _addressFilter($collection, $column) {
        //Put your logic here..!!
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }
      
       
        $this->getCollection()->getSelect()
                
                ->joinInner(array('my_table' => 'netenrich_organisation'), 'main_table.org_type_id = my_table.org_type_id')

                ->where("my_table.org_name like ?", "%$value%");

        return $this;
    }

}
