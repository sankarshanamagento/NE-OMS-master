<?php

namespace Netenrich\Company\Block\Adminhtml\Company\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Account extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

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

      $fieldset = $form->addFieldset('base_fieldset3', ['legend' => __('Accounting Correspondence Address')]);

        if ($model->getId()) {
            $fieldset->addField('org_id', 'hidden', ['name' => 'org_id']);
        }
		
		$fieldset->addField('accountstreet', 'text', array(
            'name' => 'accountstreet',
            'label' => __('Street'),
            'title' => __('Street'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 1   
        ));
		
		$fieldset->addField('accountcity', 'text', array(
            'name' => 'accountcity',
            'label' => __('City'),
            'title' => __('City'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 2   
        ));
		
		$fieldset->addField('accountstate', 'text', array(
            'name' => 'accountstate',
            'label' => __('State'),
            'title' => __('State'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 3 
        ));
		
		$optionsc = $this->_countryFactory->toOptionArray();
        $fieldset->addField(
                'accountcountry', 'select', [
            'name' => 'accountcountry',
            'label' => __('Country'),
            'title' => __('Country'),
			'tabindex' => 4,
            // 'onchange' => 'getstate(this)',
            'required' => false,
			
            'values' => $optionsc,
                ]
        );
		
		$fieldset->addField('accountpin', 'text', array(
            'name' => 'accountpin',
            'label' => __('Pin'),
            'title' => __('Pin'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 5 
        ));
		
		$fieldset = $form->addFieldset('base_fieldset4', ['legend' => __('Corporate Officer / Principal Owner')]);
		
		$fieldset->addField('corporatename', 'text', array(
            'name' => 'corporatename',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 6
        ));
		
		$fieldset->addField('corporatetitle', 'text', array(
            'name' => 'corporatetitle',
            'label' => __('Title'),
            'title' => __('Title'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 7
        ));
		
		$fieldset->addField('corporateemail', 'text', array(
            'name' => 'corporateemail',
            'label' => __('Email'),
            'title' => __('Email'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 8
        ));
		
		$fieldset->addField('corporatephone', 'text', array(
            'name' => 'corporatephone',
            'label' => __('Phone'),
            'title' => __('Phone'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 9
        ));
		
		
		$fieldset = $form->addFieldset('base_fieldset5', ['legend' => __('AP Department Details')]);
		
		$fieldset->addField('apname', 'text', array(
            'name' => 'apname',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 10
        ));
		
		$fieldset->addField('aptitle', 'text', array(
            'name' => 'aptitle',
            'label' => __('Title'),
            'title' => __('Title'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 11
        ));
		
		$fieldset->addField('apemail', 'text', array(
            'name' => 'apemail',
            'label' => __('Email'),
            'title' => __('Email'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 12
        ));
		
		$fieldset->addField('apphone', 'text', array(
            'name' => 'corporatephone',
            'label' => __('Phone'),
            'title' => __('Phone'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 13
        ));
		
		$fieldset = $form->addFieldset('base_fieldset6', ['legend' => __('Purchasing Department Details')]);
		
		$fieldset->addField('purchasingname', 'text', array(
            'name' => 'purchasingname',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 14
        ));
		
		$fieldset->addField('purchasingtitle', 'text', array(
            'name' => 'purchasingtitle',
            'label' => __('Title'),
            'title' => __('Title'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 15
        ));
		
		$fieldset->addField('purchasingemail', 'text', array(
            'name' => 'purchasingemail',
            'label' => __('Email'),
            'title' => __('Email'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 16
        ));
		
		$fieldset->addField('purchasingphone', 'text', array(
            'name' => 'purchasingphone',
            'label' => __('Phone'),
            'title' => __('Phone'),
            'required' => false,
			'disabled' => $isElementDisabled,
            'tabindex' => 17
        ));
		
		
		
		
        $this->_eventManager->dispatch('adminhtml_company_edit_tab_account_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);
        //$form = $this->_formFactory->create();
        if (!empty($orgId)) {
            $Lastfield = $form->getElement('notes');

            
        }
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
	 
	
	
    public function getTabLabel() {
        return __('Account Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Account Information');
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
