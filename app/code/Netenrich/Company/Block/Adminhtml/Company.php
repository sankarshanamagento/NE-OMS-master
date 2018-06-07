<?php
/**
 * Adminhtml Company list block
 *
 */
namespace Netenrich\Company\Block\Adminhtml;

class Company extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_company';
        $this->_blockGroup = 'Netenrich_Company';
        $this->_headerText = __('Company');
        //$this->_addButtonLabel = __('Add New Company');
		 
        parent::_construct();
        if ($this->_isAllowedAction('Netenrich_Company::save')) {
            //$this->buttonList->update('add', 'label', __('Add New Company'));
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
