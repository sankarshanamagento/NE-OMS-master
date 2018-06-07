<?php

/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api;


interface CustomerStatusInterface
{
    /**
     * Modifies the Organisation Details.
     *
     *@api
     *@param string $statusid
     *@param string[] $divauserids The array of organisations list to change the status
     *@throws \Magento\Framework\Exception\InputException If bad input is provided
     *@return string returns 1 if modified successfully.
     */
    public function statusCustomer($statusid=null,$divauserids);
}
