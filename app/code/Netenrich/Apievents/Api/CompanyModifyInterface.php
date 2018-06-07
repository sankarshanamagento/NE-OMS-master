<?php

/**
 * Copyright 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Netenrich\Apievents\Api;


interface CompanyModifyInterface
{
  /**
   * Return the sum of the two numbers.
   *
   * @api
   *@param string $companyname
   *@param string $firstname
   *@param string $lastname
   *@param string $addres
   *@param string $country
   *@param string $state
   *@param string $city
   *@param string $zip
   *@param string $website
   *@param string $phone
   *@param string $fax
   *@param string $organisationtype
   *@param string $parent_id
   *@param string $companyemail
   *@param string $useremail
   *@param string $divaid
   *@param string $status
   *@param string $timezone
   *@param string $usertype
   *@param string $webstoreid
   *@throws \Magento\Framework\Exception\InputException If bad input is provided
   *@return \Netenrich\Apievents\Api\Data\StatusDataInterface
   */
    public function modifyCompany($companyname=null,$firstname=null,$lastname=null,$address=null,$country=null,$state=null,$city=null,$zip=null,$website=null,$phone=null,$fax=null,$organisationtype=null,$parent_id=null,$companyemail=null,$status=null,$divaid=null,$timezone=null,$usertype=null,$useremail=null,$webstoreid=null);
}
