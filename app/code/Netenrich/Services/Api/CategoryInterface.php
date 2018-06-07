<?php
/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Services\Api;


interface CategoryInterface
{
    /**
     * Modifies the Organisation Details.
     *
     *@api
     *@param string $catid 
     *@throws \Magento\Framework\Exception\InputException If bad input is provided
     *@return \Netenrich\Apievents\Api\Data\TestdataInterface .
     */
    public function categoryinfo($catid);
}