<?php

/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api;


interface OpenOrdersInterface
{
    /**
     * Modifies the Organisation Details.
     *
     *@api
     *@param string $divauserids 
     *@throws \Magento\Framework\Exception\InputException If bad input is provided
     *@return \Netenrich\Apievents\Api\Data\TestdataInterface .
     */
    public function openlistofids($divauserids);
}
