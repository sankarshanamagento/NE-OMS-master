<?php

namespace Netenrich\Webhook\Block\Adminhtml\Webhook;

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
    \Magento\Backend\Block\Template\Context $context, 
	\Magento\Backend\Helper\Data $backendHelper, 
	\Netenrich\Webhook\Model\Webhook $Company, 
	\Netenrich\Webhook\Model\ResourceModel\Webhook\CollectionFactory $collectionFactory, 
	array $data = []
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
		
        $this->setId('webhookGrid');
        $this->setDefaultSort('api_id');
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
        $this->addColumn('api_id', [
            'header' => __('ID'),
            'index' => 'api_id',
        ]);


        $this->addColumn('jobid', ['header' => __('Job ID'), 'index' => 'jobid']);
        $this->addColumn('entity_id', ['header' => __('Name'), 'index' => 'entity_id']);
        $this->addColumn('entity_name', ['header' => __('Entity'), 'index' => 'entity_name']);
		$this->addColumn('createaction', ['header' => __('Action'), 'index' => 'createaction']);
        $this->addColumn('status', ['header' => __('Status'), 'index' => 'status']);
		$this->addColumn('response', ['header' => __('response'), 'index' => 'response']);
        $this->addColumn(
                'Payload', [
            'header' => __('Payload'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => [
                [
                    'caption' => __('Payload'),
                    'url' => [
                        'base' => 'webhook/*/Payload',
                        'params' => ['jobid' => $this->getRequest()->getParam('jobid')]
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
		



        $this->addColumn(
                'action', [
            'header' => __('Run Job'),
            'type' => 'action',
            'getter' => 'getId',
            'actions' => [
                [
                    'caption' => __('Run Job'),
                    'url' => [
                        'base' => 'webhook/*/Runjob',
                        'params' => ['jobid' => $this->getRequest()->getParam('jobid')]
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
		
        return false;
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

   

}
