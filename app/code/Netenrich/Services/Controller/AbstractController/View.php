<?php

namespace Netenrich\Services\Controller\AbstractController;

use Magento\Framework\App\Action;
use Magento\Framework\View\Result\PageFactory;

abstract class View extends Action\Action
{
    /**
     * @var \Netenrich\Services\Controller\AbstractController\ServicesLoaderInterface
     */
    protected $ServicesLoader;
	
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param OrderLoaderInterface $orderLoader
	 * @param PageFactory $resultPageFactory
     */
    public function __construct(Action\Context $context, ServicesLoaderInterface $ServicesLoader, PageFactory $resultPageFactory)
    {
        $this->servicesLoader = $ServicesLoader;
		$this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Company view page
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->servicesLoader->load($this->_request, $this->_response)) {
            return;
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
		return $resultPage;
    }
}
