<?php
namespace Netenrich\Company\Block\Adminhtml\Catalog\Product\Edit\Tab;
 
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
 
class Distibutor extends \Magento\Backend\Block\Widget\Grid\Extended {

    /**
     * @var \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory
     */
    protected $_collectionFactory;
	
    //protected $_companySpecificFactory;

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
	\Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $collectionFactory,  
	array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
       // $this->_companySpecificFactory = $specificCollectionFactory;
        //$this->_Company = $Company;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct() {
        parent::_construct();
        $this->setId('productgridGrid');
        $this->setUseAjax(true);
        $this->setDefaultSort('org_id');
        $this->setDefaultDir('DESC');
        $this->setDefaultFilter(array('in_customers' => 1));
        $this->setFilterVisibility(true);
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
		$collection=$collection->addFieldToFilter('org_type_id',2);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns() {

       

        $this->addColumn('in_customers',
		[
            'header_css_class' => 'a-center',
			'header'=>__('select'),
			'id'=> 'find-table',
            'type' => 'checkbox',
            'index' => 'org_id',
            'align' => 'center',
            'field_name' => 'in_customers[]',
			'data-form-part' => 'product_form'
           // 'values' => $this->_getSelectedCompanies()
		   ]
        );

        $this->addColumn('org_id', [
            'header' => __('ID'),
            'index' => 'org_id',
        ]);


        $this->addColumn('name', ['header' => __('Orginisation Name'), 'index' => 'name']);
        $this->addColumn('address', ['header' => __('Address'), 'index' => 'address']);
        $this->addColumn('address2', ['header' => __('Address2'), 'index' => 'address2']);
        $this->addColumn('org_type_id', ['header' => __('Orginisation Type'), 'index' => 'org_type_id', 'renderer' => 'Netenrich\Company\Block\Adminhtml\Company\Grid\Renderer\Organisation', 'filter_condition_callback' => array($this, '_addressFilter')]);
        $this->addColumn('affiliation', ['header' => __('Affiliation'), 'index' => 'name', 'renderer' => 'Netenrich\Company\Block\Adminhtml\Company\Grid\Renderer\Affiliation']);
        //$this->addColumn('var', ['header' => __('VAR'), 'index' => 'var']);
        $this->addColumn('payment_method', ['header' => __('Payment Method'), 'index' => 'payment_method']);


        return parent::_prepareColumns();
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

    /* protected function _addColumnFilterToCollection($column) {

        if ($column->getId() == 'in_customers') {
            $customerIds = $this->_getSelectedCompanies();


            // echo $column->getFilter()->getValue()."getvalue";exit;
            if ($column->getFilter()->getValue()) {
                //$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$customerIds));
                if (is_array($customerIds)) {
                    $this->getCollection()->addFieldToFilter('org_id', array('in' => $customerIds));
                }
            } else {
                if ($customerIds) {
                    //$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$customerIds));
                    $this->getCollection()->addFieldToFilter('org_id', array('nin' => $customerIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
 */
    /**
     * Get grid url
     *
     * @return string
     */
    /* public function getGridUrl() {
        return $this->getUrl('productgrid/index/grid');
    }

   */

   /*  protected function _getSelectedCompanies() {   //to return selected customers values.
        $customers = $this->getSelectedCompanies();
        return $customers;
    }
 */
    /* public function getSelectedCompanies() {
        // Customer Data

        $product_id = $this->getRequest()->getParam('id');
        $csCollection = $this->_companySpecificFactory->create();
        $csCollection->addFieldToFilter('product_id', $product_id);
//echo "<pre>compab";print_r($csCollection->getData());exit;
        $customerIds = array();
        foreach ($csCollection as $obj) {
            $customerIds[] = $obj->getOrgId();
        }

        return $customerIds;
    } */

}