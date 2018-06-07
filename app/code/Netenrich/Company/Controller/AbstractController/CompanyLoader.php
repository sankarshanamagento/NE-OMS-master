<?php

namespace Netenrich\Company\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;

class CompanyLoader implements CompanyLoaderInterface
{
    /**
     * @var \Netenrich\Company\Model\CompanyFactory
     */
    protected $CompanyFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @param \Netenrich\Company\Model\CompanyFactory $CompanyFactory
     * @param OrderViewAuthorizationInterface $orderAuthorization
     * @param Registry $registry
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \Netenrich\Company\Model\CompanyFactory $CompanyFactory,
        Registry $registry,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->companyFactory = $CompanyFactory;
        $this->registry = $registry;
        $this->url = $url;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return bool
     */
    public function load(RequestInterface $request, ResponseInterface $response)
    {
        $id = (int)$request->getParam('id');
        if (!$id) {
            $request->initForward();
            $request->setActionName('noroute');
            $request->setDispatched(false);
            return false;
        }

        $Company = $this->companyFactory->create()->load($id);
        $this->registry->register('current_company', $Company);
        return true;
    }
}
