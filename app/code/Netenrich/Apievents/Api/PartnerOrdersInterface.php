<?php

/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api;


interface PartnerOrdersInterface
{
    /**
     * Modifies the Organisation Details.
     *
     *@api
     *@param string $divauserids 
     *@throws \Magento\Framework\Exception\InputException If bad input is provided
     *@return string .
     */
    public function orderslists($divauserids);
}
