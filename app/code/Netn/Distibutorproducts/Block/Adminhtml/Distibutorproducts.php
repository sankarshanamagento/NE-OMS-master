<?php
namespace Netn\Distibutorproducts\Block\Adminhtml;
class Distibutorproducts extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_distibutorproducts';/*block grid.php directory*/
        $this->_blockGroup = 'Netn_Distibutorproducts';
        $this->_headerText = __('Distibutorproducts');
        //$this->_addButtonLabel = __('Add New Entry');
        //$this->_removeButton('add');
		
        parent::_construct();
		
    }
}
