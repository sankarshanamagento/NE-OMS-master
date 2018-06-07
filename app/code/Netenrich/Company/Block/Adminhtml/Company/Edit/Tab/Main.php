<?php

namespace Netenrich\Company\Block\Adminhtml\Company\Edit\Tab;

//use  Netenrich\Company\Model\System\Config\Status;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
	//protected $_newsStatus;

	const ENABLED  = 1;
    const DISABLED = 0;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
	//Status $newsStatus,
    \Magento\Backend\Block\Template\Context $context,
	\Magento\Framework\Registry $registry,
	\Magento\Framework\Data\FormFactory $formFactory,
	\Magento\Store\Model\System\Store $systemStore,
	\Magento\Directory\Model\Config\Source\Country $countryFactory,
    \Magento\Config\Model\Config\Source\Locale\Timezone $timezone,
	\Netenrich\Company\Model\CompanyFactory $modelFactory,
	\Netenrich\Company\Helper\Data $dataHelper,
	array $data = []
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Company Information')]);

        if ($model->getId()) {
            $fieldset->addField('org_id', 'hidden', ['name' => 'org_id']);
        }

		$field = $fieldset->addField(
                'org_type_id', 'select', [
            'name' => 'org_type_id',
            'label' => __('Company Type'),
			'readonly' => true,
            'title' => $this->getUrl("company/index/serviceprovider/"),
            // 'onchange' => 'getstate(this)',
            'required' => false,
            'values' => $data_array,
                ]
        );

            $fieldset->addField(
                'parent_id', 'select', [
                'name' => 'parent_id',
                'label' =>$labelcontent,
                'title' => __('Service Provider'),
				'container_class' => 'customer-label',
				'readonly' => true,
                // 'onchange' => 'getstate(this)',
                'disabled' => false,
                'required' => false,
                'values' => $this->_dataHelper->getServiceProvider($orgId, $parentId),
//array("" => "Please Select"),
                    ]
            );

        $fieldset->addField(
                'name', 'text', [
            'name' => 'name',
            'label' => __('Company Name'),
            'title' => __('Company Name'),
			'readonly' => true,
            'required' => false,
            'disabled' => $isElementDisabled
                ]
        );

        $fieldset->addField(
                'address', 'text', [
            'name' => 'address',
            'label' => __('Address'),
            'title' => __('Address'),
			'readonly' => true,
            'required' => false,
            'disabled' => $isElementDisabled
                ]
        );

        /* $fieldset->addField(
                'address2', 'text', [
            'name' => 'address2',
            'label' => __('Address2'),
            'title' => __('Address2'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        ); */

		 $fieldset->addField(
                'email', 'text', [
            'name' => 'email',
            'label' => __('Email'),
            'title' => __('Email'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

        $optionsc = $this->_countryFactory->toOptionArray();
        $fieldset->addField(
                'country', 'select', [
            'name' => 'country',
            'label' => __('Country'),
            'title' => __('Country'),
            // 'onchange' => 'getstate(this)',
            'required' => false,
			'readonly' => true,
            'values' => $optionsc,
                ]
        );

        $fieldset->addField(
                'timzone', 'text', [
            'name' => 'timzone',
            'label' => __('Time zone'),
            'title' => __('State'),
            'required' => false,
      'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

        $fieldset->addField(
                'state', 'text', [
            'name' => 'state',
            'label' => __('State'),
            'title' => __('State'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );
        $fieldset->addField(
                'city', 'text', [
            'name' => 'city',
            'label' => __('City'),
            'title' => __('City'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

        $fieldset->addField(
                'zip', 'text', [
            'name' => 'zip',
            'label' => __('Postal Code'),
            'title' => __('Postal Code'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );


        $fieldset->addField(
                'website', 'text', [
            'name' => 'website',
            'label' => __('Website'),
            'title' => __('Website'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

        $fieldset->addField(
                'phone', 'text', [
            'name' => 'phone',
            'label' => __('Phone Number'),
            'title' => __('Phone Number'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

        $fieldset->addField(
                'fax', 'text', [
            'name' => 'fax',
            'label' => __('Fax'),
            'title' => __('Fax'),
            'required' => false,
			'readonly' => true,
            'disabled' => $isElementDisabled
                ]
        );

		        $fieldset->addField(
            'status',
            'select',
            [
                'name'      => 'status',
                'label'     => __('Status'),
                'options'   => $this->toOptionArray(),
				'readonly'  => true
            ]
        );
        //  $data_array = array('' => 'Please Select..', '1' => 'VAR', '4' => 'Distributor', '5' => 'Client', '6' => 'Service Provider');





        /* if ($companyTypeId == 3 || $companyTypeId == 4) {
            $fieldset->addField(
                    'distributor', 'select', [
                'name' => 'distributor',
                'label' => __('Distributor'),
                'title' => __('Distributor'),
                // 'onchange' => 'getstate(this)',
                'required' => false,
                'disabled' => true,
                'values' => $this->_dataHelper->getServiceProvider($orgId, 2),
                    ]
            );
        }
        if ($companyTypeId == 4) {
            $fieldset->addField(
                    'var', 'select', [
                'name' => 'var',
                'label' => __('Partner'),
                'title' => __('Partner'),
                // 'onchange' => 'getstate(this)',
                'required' => false,
                'disabled' => true,
                'values' => $this->_dataHelper->getServiceProvider($orgId, 3),
                    ]
            );
        } */
        /*$fieldset->addField(
                'payment_method', 'text', [
            'name' => 'payment_method',
            'label' => __('Payment Method'),
            'title' => __('Payment Method'),
            'required' => true,
            'disabled' => $isElementDisabled
                ]
        );


        $fieldset->addField(
                'notes', 'text', [
            'name' => 'notes',
            'label' => __('Notes'),
            'title' => __('Notes'),
            'required' => true,

                ]
        ); */

		 /* $fieldset->addField(
            'image',
            'image',
            array(
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
            )
        ); */
        /* $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
          $fieldset->addField('published_at', 'date', [
          'name'     => 'published_at',
          'date_format' => $dateFormat,
          'image'    => $this->getViewFileUrl('images/grid-cal.gif'),
          'value' => $model->getPublishedAt(),
          'label'    => __('Publishing Date'),
          'title'    => __('Publishing Date'),
          'required' => true
          ]);
         */


        $this->_eventManager->dispatch('adminhtml_company_edit_tab_main_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);
        //$form = $this->_formFactory->create();
        if (!empty($orgId)) {
            $Lastfield = $form->getElement('notes');

            /*   $Lastfield->setAfterElementHtml("<script type='text/javascript'>

              var e = document.getElementById('company_main_org_type_id');
              var orgTypeId = e.options[e.selectedIndex].value;

              var orgId = $orgId
              alert(orgId)
              if(orgTypeId == 4){
              //3 ajax calls
              var reloadurl =  '".$baseUrl."'+ 'admin_20/company/index/serviceprovider/orgId/' + orgId + '/orgTypeId/'+ orgTypeId+'?ajax=1';

              //    var param = 'ajax=1';
              new Ajax.Request(reloadurl, {
              method: 'get',
              onComplete: function(transport){
              var response = transport.responseText;
              $('client').replace(response);
              }
              });
              }else if(orgTypeId  == 3){
              //2 ajax call
              }else if(orgTypeId == 2){
              // 1 ajax call
              }else if(orgId ==1 ){
              // 1 ajax call
              }


              </script>"); */
        }
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return __('Company Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Company Information');
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


	 public function toOptionArray()
    {
         $options = [
            self::ENABLED => __('Active'),
            self::DISABLED => __('Inactive')
        ];

        return $options;
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
