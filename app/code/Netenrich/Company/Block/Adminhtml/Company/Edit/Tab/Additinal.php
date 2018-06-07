<?php

namespace Netenrich\Company\Block\Adminhtml\Company\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Additinal extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
    \Magento\Backend\Block\Template\Context $context, 
	\Magento\Framework\Registry $registry, 
	\Magento\Framework\Data\FormFactory $formFactory, 
	\Magento\Store\Model\System\Store $systemStore, 
	\Magento\Directory\Model\Config\Source\Country $countryFactory,
    \Magento\Config\Model\Config\Source\Locale\Timezone $timezone,	
	\Netenrich\Company\Model\CompanyFactory $modelFactory, 
	\Netenrich\Company\Helper\Data $dataHelper
    , array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_countryFactory = $countryFactory;
		$this->_timezone = $timezone;
        $this->_modelFactory = $modelFactory;
        $this->_dataHelper = $dataHelper;

        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     * $url =
     * @return $this
     */
    protected function _prepareForm() {
        /* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('company');
        $orgId = $this->getRequest()->getParam('org_id');
        $companyTypeId = $model->getOrgTypeId();
		$parentId = $model->getParentId();
		$companypeId = $model->getOrgTypeId()-1;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $baseUrl = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore(1)->getBaseUrl();
        //echo $url;
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $result1 = $connection->fetchAll("SELECT * FROM netenrich_organisation");
        $data_array = array();
        $data_array[] = array("value" => "", "label" => "PLease Select");
        foreach ($result1 as $k => $v) {
            $data_array[] = array('value' => $v['org_type_id'], 'label' => $v['org_name']);
        }
		
		$result12 = $connection->fetchRow("SELECT org_name FROM netenrich_organisation where org_type_id=".$companypeId);
        if($result12['org_name']!="")
		{
		  $labelcontent=$result12['org_name'];
		} else { $labelcontent="" ;}
		
		
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Netenrich_Company::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('company_main_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Business Details')]);

        if ($model->getId()) {
            $fieldset->addField('org_id', 'hidden', ['name' => 'org_id']);
        }
		
		
		
		$fieldset->addField('businesstype', 'select', array(
            'name' => 'businesstype',
            'label' => __('Industry Type'),
            'title' => __('Business Type'),
            'required' => false,
            'values'    =>$this->tobusinessOptionArray(),
            'tabindex' => 1 
        ));
		
		
		$fieldset->addField('vendortype', 'select', array(
            'name' => 'vendortype',
            'label' => __('Vendor Type'),
            'title' => __('Vendor Type'),
            'required' => false,
            'values'    =>$this->tovendorOptionArray(),
            'tabindex' => 2 
        ));
		
		$fieldset->addField('vendor', 'text', array(
            'name' => 'vendor',
            'label' => __('NetEnrich Vendor #'),
            'title' => __('NetEnrich Vendor'),
            'required' => false,
            'tabindex' => 3   
        ));
		
		
		$fieldset->addField('customer', 'text', array(
            'name' => 'customer',
            'label' => __('Customer #'),
            'title' => __('Customer #'),
            'required' => false,
            
            'tabindex' => 4 
        ));
		
		
         $fieldset->addField('address2', 'text', array(
            'name' => 'address2',
            'label' => __('Parent Company'),
            'title' => __('Parent Company'),
            'required' => false,
            
            'tabindex' => 5  
        ));
		
        
		
		//$fieldset = $form->addFieldset('base_fieldset2', ['legend' => __('')]);
		
		
		
		/*$fieldset->addField('businessphone', 'text', array(
            'name' => 'businessphone',
            'label' => __('Phone'),
            'title' => __('Phone'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 5   
        ));
		
		$fieldset->addField('businessfax', 'text', array(
            'name' => 'businessfax',
            'label' => __('Fax'),
            'title' => __('Fax'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 6  
        ));*/
		 
		
		
        $this->_eventManager->dispatch('adminhtml_company_edit_tab_additinal_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);
        //$form = $this->_formFactory->create();
        if (!empty($orgId)) {
            $Lastfield = $form->getElement('businessfax');

            
        }
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
	 
	 public function tobusinessOptionArray()
    {
		 $options = array();
		 $options[] = array('label' => 'Please Select', 'value' => '');
         $options[] = array('label' => 'Finance', 'value' => 'Finance');
         $options[] = array('label' => 'IT', 'value' => 'IT');
		 $options[] = array('label' => 'Auto', 'value' => 'Auto');
		 $options[] = array('label' => 'Banking', 'value' => 'Banking');
		 $options[] = array('label' => 'Telecommunications', 'value' => 'Telecommunications');
		 $options[] = array('label' => 'Web Services', 'value' => 'Web Services');
		 $options[] = array('label' => 'Health', 'value' => 'Health');
		 $options[] = array('label' => 'Gaming', 'value' => 'Gaming');
                  
 
        return $options;
    }
	
	
	public function tovendorOptionArray()
    {
		 $voptions = array();
		 $voptions[] = array('label' => 'Please Select', 'value' => '');
         $voptions[] = array('label' => 'Individual', 'value' => 'Individual');
         $voptions[] = array('label' => 'Copration', 'value' => 'Copration');
		 $voptions[] = array('label' => 'Sole Proprietor', 'value' => 'Sole Proprietor');
		 $voptions[] = array('label' => 'Partnership', 'value' => 'Partnership');
         $voptions[] = array('label' => 'Other', 'value' => 'Other');         
 
        return $voptions;
    }
	
    public function getTabLabel() {
        return __('Business Details');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Business Details');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId) {
        return $this->_authorization->isAllowed($resourceId);
    }

}
