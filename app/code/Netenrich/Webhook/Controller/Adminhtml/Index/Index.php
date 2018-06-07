<?php

namespace Netenrich\Webhook\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
	
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Netenrich_Webhook::Webhook_manage');
    }

    /**
     * Company List action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(
            'Netenrich_Webhook::webhook_manage'
        )->addBreadcrumb(
            __('Webhook'),
            __('Webhook')
        )->addBreadcrumb(
            __('Manage Webhook'),
            __('Manage Webhook')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Webhook'));
		
        return $resultPage;
    }
}
