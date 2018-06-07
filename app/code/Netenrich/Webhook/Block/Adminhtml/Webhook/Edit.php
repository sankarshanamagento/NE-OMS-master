<?php
namespace Netenrich\Services\Block\Adminhtml\Services;

/**
 * Admin Services page
 *
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize cms page edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'api_id';
        $this->_blockGroup = 'Netenrich_Services';
        $this->_controller = 'adminhtml_services';

        parent::_construct();

        if ($this->_isAllowedAction('Netenrich_Services::save')) {
            $this->buttonList->update('save', 'label', __('Save Services'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ]
                ],
                -100
            );
			
			
			$duplicateUrl = $this->_urlBuilder->getUrl('services/*/serviceprovider', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
			
            $this->buttonList->add(
                'duplicate',
                [
                    'class' => 'save',
                    'label' => __('SNYC Services'),
                    'onclick' => 'setLocation("' . $duplicateUrl . '")'
                ],
                12 // sort order
            );
			
			
			$duplicateUrl1 = $this->_urlBuilder->getUrl('services/*/attributes', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
			
            $this->buttonList->add(
                'duplicate1',
                [
                    'class' => 'save',
                    'label' => __('Snyc Attributes'),
                    'onclick' => 'setLocation("' . $duplicateUrl1 . '")'
                ],
                13 // sort order
            );
			
			
        } else {
            $this->buttonList->remove('save');
        }
		
		if ($this->_isAllowedAction('Netenrich_Services::services_import')) {
            $this->buttonList->update('delete', 'label', __('Delete Services'));
        } else {
            $this->buttonList->remove('delete');
        }

        if ($this->_isAllowedAction('Netenrich_Services::services_delete')) {
            $this->buttonList->update('import', 'label', __('import Services'));
        } else {
            $this->buttonList->remove('import');
        }
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('services')->getId()) {
            return __("Edit Services '%1'", $this->escapeHtml($this->_coreRegistry->registry('services')->getTitle()));
        } else {
            return __('New Services');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('services/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }

    /**
     * Prepare layout
     *
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
