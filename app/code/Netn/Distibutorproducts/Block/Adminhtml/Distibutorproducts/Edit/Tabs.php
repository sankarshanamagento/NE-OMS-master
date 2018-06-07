<?php
namespace Netn\Distibutorproducts\Block\Adminhtml\Distibutorproducts\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_distibutorproducts_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Distibutorproducts Information'));
    }
}