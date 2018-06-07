<?php

namespace Netenrich\Services\Block\Adminhtml\Services\Edit\Tab;

/**
 * Cms page edit form main tab
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
	protected $eavAttributeRepository;

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
	\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
	\Netenrich\Services\Model\ServicesFactory $modelFactory, 
	\Netenrich\Services\Helper\Data $dataHelper
    , array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->eavAttributeRepository = $eavAttributeRepository;
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
        $partid = $this->getRequest()->getParam('part_id');
        $ServicesId = $model->getPartId();
		
        if ($this->_isAllowedAction('Netenrich_Services::save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('services_main_');
		$towerattribute="towers";
		$tremattribute="contractterm";
		$packageattribute="service_package";
		$payattribute="payable";
		$levelattribute="servicelevel";
		$assetsattribute="assets";
		$partice="practice";
		
		/* $attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$assetsattribute);
        $options = $attributes->getSource()->getAllOptions(false);
		echo "<pre>"; 
		print_r($options);*/

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Services Information')]);

        if ($model->getId()) {
            $fieldset->addField('part_id', 'hidden', ['name' => 'part_id']);
        }
		
		$field = $fieldset->addField(
                'partnumber', 'text', [
            'name' => 'partnumber',
            'label' => __('Part Number'),
            'title' => __('Part Number'),
            'required' => true,
            'disabled' => $isElementDisabled
                ]
        );
		
		$field = $fieldset->addField(
                'service_type', 'text', [
            'name' => 'service_type',
            'label' => __('Service Type'),
            'title' => __('Service Type'),
            'required' => true,
            'disabled' => $isElementDisabled
                ]
        );
		
		
		$field = $fieldset->addField(
                'practice', 'select', [
            'name' => 'practice',
            'label' => __('Practice'),
            'title' => __('Practice'),
            'required' => true,
            'disabled' => $isElementDisabled,
		    'values' => $this->getservicesOptionlist($partice)
                ]
        );

        $fieldset->addField(
                'service_package', 'select', [
            'name' => 'service_package',
            'label' => __('Service Package'),
            'title' => __('Service Package'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getservicesOptionlist($packageattribute)
                ]
        );

        $fieldset->addField(
                'towers', 'select', [
            'name' => 'towers',
            'label' => __('Towers'),
            'title' => __('Towers'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getOptionlist($towerattribute)
                ]
        );
		
		$fieldset->addField(
                'servicelevel', 'select', [
            'name' => 'servicelevel',
            'label' => __('Service Level'),
            'title' => __('Service Level'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getOptionlist($levelattribute)
                ]
        );
		
		$fieldset->addField(
                'assets', 'select', [
            'name' => 'assets',
            'label' => __('Assets'),
            'title' => __('Assets'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getOptionlist($assetsattribute)
                ]
        );
		
		$fieldset->addField(
                'payable', 'select', [
            'name' => 'payable',
            'label' => __('Payable'),
            'title' => __('Payable'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getOptionlist($payattribute)
                ]
        );
		
		$fieldset->addField(
                'contractterm', 'select', [
            'name' => 'contractterm',
            'label' => __('Contract Term'),
            'title' => __('Contract Term'),
            'required' => true,
            'disabled' => $isElementDisabled,
			'values' => $this->getOptionlist($tremattribute)
                ]
        );

        
       
			
			
			
        
        
		 
		
        $this->_eventManager->dispatch('adminhtml_services_edit_tab_main_prepare_form', ['form' => $form]);

        $form->setValues($model->getData());
        $this->setForm($form);
        //$form = $this->_formFactory->create();
        
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel() {
        return __('Services Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle() {
        return __('Services Information');
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
	
	
	public function getOptionlist($attributeCode){
        $attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attributeCode);
        $options = $attributes->getSource()->getAllOptions(true);
		
        return $options;                
    }
	
	
	public function getservicesOptionlist($attributeCode){
		$arrayoptions=array("value"=>"","label"=>"---Please Select---");
        $attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attributeCode);
		$options = $attributes->getSource()->getAllOptions(true);
		
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
