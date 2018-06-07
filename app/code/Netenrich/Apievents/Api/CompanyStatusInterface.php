<?php

/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api;


interface CompanyStatusInterface
{
    /**
     * Modifies the Organisation Status.
     *
     *@api
     *@param string $statusid
     *@param string[] $divaids The array of organisations list to change the status
     *@throws \Magento\Framework\Exception\InputException If bad input is provided
     *@return array
     */
    public function statusCompany($statusid=null,$divaids);
}
