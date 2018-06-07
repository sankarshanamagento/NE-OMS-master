<?php
namespace Netenrich\Company\Block\Adminhtml\Company\Edit\Tab;
/**
 * Cms page edit form main tab
 */
class Legal extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
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
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Company Legal Information')]);
        if ($model->getId()) {
            $fieldset->addField('org_id', 'hidden', ['name' => 'org_id']);
        }


		





        $this->_eventManager->dispatch('adminhtml_company_edit_tab_legal_prepare_form', ['form' => $form]);
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

     public function getFormHtml()
    {
       // get the current form as html content.
        $html = parent::getFormHtml();
        //Append the phtml file after the form content.
        $html .= $this->setTemplate('Netenrich_Company::company/doc.phtml')->toHtml();
        return $html;
    }

    public function getTabLabel() {
        return __('Legal Information');
    }
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Legal Information');
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
