<?php

namespace Netenrich\Services\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;

class ServicesLoader implements ServicesLoaderInterface
{
    /**
     * @var \Netenrich\Company\Model\CompanyFactory
     */
    protected $ServicesFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    /**
     * @param \Netenrich\Services\Model\CompanyFactory $CompanyFactory
     * @param OrderViewAuthorizationInterface $orderAuthorization
     * @param Registry $registry
     * @param \Magento\Framework\UrlInterface $url
     */
    public function __construct(
        \Netenrich\Services\Model\ServicesFactory $ServicesFactory,
        Registry $registry,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->servicesFactory = $ServicesFactory;
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

        $Services = $this->servicesFactory->create()->load($id);
        $this->registry->register('current_services', $Services);
        return true;
    }
}
