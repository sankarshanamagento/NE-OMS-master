<?php

namespace Netenrich\Webhook\Controller\Adminhtml\Index;

class Grid extends \Magento\Customer\Controller\Adminhtml\Index
{
    /**
     * Services grid action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout(false);
        $this->_view->renderLayout();
    }
}
