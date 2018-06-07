<?php
namespace Netenrich\Services\Block\Adminhtml\Services\Edit\Tab;
/**
 * Cms page edit form main tab
 */
class Import extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
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
        $model = $this->_coreRegistry->registry('services');
        $partId = $this->getRequest()->getParam('part_id');
        
		
        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('Netenrich_services::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('services_main_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Assign Products')]);

        if ($model->getId()) {
            $fieldset->addField('part_id', 'hidden', ['name' => 'part_id']);
        }
		
		
		$fieldset->addField(
         'uploadservices',
         'file',
        [
        'name' => 'uploadservices',
        'label' => __('Upload Services'),
        'required' => true,
		'after_element_html' => '<small>Please Upload Csv file format  import Services <br>
		                         Please Download the sample format to import Services</small>'
        ]
        );
		
		
		 
		
		
        $this->_eventManager->dispatch('adminhtml_services_edit_tab_additinal_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);
        //$form = $this->_formFactory->create();
        if (!empty($partId)) {
            $Lastfield = $form->getElement('uploadservices');

            
        }
        return parent::_prepareForm();
    }
    /**
     * Prepare label for tab
     *
     * @return string
     */

    

    public function getTabLabel() {
        return __('Import Services');
    }
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Import Services');
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
