<?php
namespace Netn\Distibutor\Block\Adminhtml\Distibutor\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_distibutor_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Distibutor Information'));
    }
}