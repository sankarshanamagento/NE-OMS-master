<?php
namespace Netn\Distibutor\Block\Adminhtml;
class Distibutor extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_distibutor';/*block grid.php directory*/
        $this->_blockGroup = 'Netn_Distibutor';
        $this->_headerText = __('Distibutor');
        $this->_addButtonLabel = __('Add New Entry'); 
        parent::_construct();
		
    }
}
