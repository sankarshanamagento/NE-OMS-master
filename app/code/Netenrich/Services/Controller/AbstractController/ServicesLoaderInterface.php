<?php

namespace Netenrich\Services\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

interface ServicesLoaderInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Netenrich\Services\Model\Services
     */
    public function load(RequestInterface $request, ResponseInterface $response);
}
