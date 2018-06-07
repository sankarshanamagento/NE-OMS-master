<?php
/**
 * Adminhtml Company list block
 *
 */
namespace Netenrich\Webhook\Block\Adminhtml;

class Webhook extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_webhook';
        $this->_blockGroup = 'Netenrich_Webhook';
        $this->_headerText = __('Webhook');
        //$this->_addButtonLabel = __('Add New Webhook');
		
		$duplicateUrl1 = $this->_urlBuilder->getUrl('webhook/*/attributeoptions', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
			
            $this->buttonList->add(
                'duplicate1',
                [
                    'class' => 'save',
                    'label' => __('Create new option'),
                    'onclick' => 'setLocation("' . $duplicateUrl1 . '")'
                ],
                12 // sort order
            );
		 
        parent::_construct();
        if ($this->_isAllowedAction('Netenrich_Webhook::save')) {
            //$this->buttonList->update('add', 'label', __('Add New Webhook'));
			$this->buttonList->remove('add');
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
