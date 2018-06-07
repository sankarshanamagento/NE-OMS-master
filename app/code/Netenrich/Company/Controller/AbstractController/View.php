<?php

namespace Netenrich\Company\Controller\AbstractController;

use Magento\Framework\App\Action;
use Magento\Framework\View\Result\PageFactory;

abstract class View extends Action\Action
{
    /**
     * @var \Netenrich\Company\Controller\AbstractController\CompanyLoaderInterface
     */
    protected $CompanyLoader;
	
	/**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param OrderLoaderInterface $orderLoader
	 * @param PageFactory $resultPageFactory
     */
    public function __construct(Action\Context $context, CompanyLoaderInterface $CompanyLoader, PageFactory $resultPageFactory)
    {
        $this->companyLoader = $CompanyLoader;
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
        if (!$this->companyLoader->load($this->_request, $this->_response)) {
            return;
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
		return $resultPage;
    }
}
