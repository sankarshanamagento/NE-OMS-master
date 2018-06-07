<?php
/**
 * Adminhtml Services list block
 *
 */
namespace Netenrich\Services\Block\Adminhtml;

class Services extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_services';
        $this->_blockGroup = 'Netenrich_Services';
        $this->_headerText = __('Services');
        $this->_addButtonLabel = __('Add New Services');
		
		$duplicateUrl = $this->_urlBuilder->getUrl('services/*/syncategories', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
			
            $this->buttonList->add(
                'duplicate',
                [
                    'class' => 'save',
                    'label' => __('Categories Sync'),
                    'onclick' => 'setLocation("' . $duplicateUrl . '")'
                ],
                12 // sort order
            );
			
			
			$duplicateUrl1 = $this->_urlBuilder->getUrl('services/*/synsubcategories', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
			
            $this->buttonList->add(
                'duplicate1',
                [
                    'class' => 'save',
                    'label' => __('Sub Categories Sync'),
                    'onclick' => 'setLocation("' . $duplicateUrl1 . '")'
                ],
                12 // sort order
            );
			
		

		
        parent::_construct();
        if ($this->_isAllowedAction('Netenrich_Services::save')) {
            $this->buttonList->update('add', 'label', __('Add New Services'));
			
        } else {
            $this->buttonList->remove('add');
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
	
	
}
