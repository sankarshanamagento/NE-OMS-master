<?php



namespace Netenrich\Apievents\Api;



interface CompanyDeleteInterface
{
    /**
    * Deletes new customer
    *@api
    *@param string $divaid
    *@return \Netenrich\Apievents\Api\Data\StatusDataInterface
     */
    public function deleteCompany($divaid);
}
