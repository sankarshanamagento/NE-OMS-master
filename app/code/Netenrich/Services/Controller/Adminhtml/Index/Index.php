<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

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
        return $this->_authorization->isAllowed('Netenrich_Services::Services_manage');
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
            'Netenrich_Services::services_manage'
        )->addBreadcrumb(
            __('Services'),
            __('Services')
        )->addBreadcrumb(
            __('Manage Services'),
            __('Manage Services')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Services'));
		
        return $resultPage;
    }
}
