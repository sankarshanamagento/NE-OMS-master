<?php

namespace Netenrich\Company\Controller\AbstractController;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;

interface CompanyLoaderInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Netenrich\Company\Model\Company
     */
    public function load(RequestInterface $request, ResponseInterface $response);
}
