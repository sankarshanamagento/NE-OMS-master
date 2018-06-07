<?php



namespace Netenrich\Apievents\Api;



interface CustomerModifyInterface
{
    /**
    * Adds new customer
     *@param string $firstname
     *@param string $lastname
     *@param string $email
     *@param string $phone
     *@param string $zip
     *@param string $country
     *@param string $city
     *@param string $street
     *@param string $state
     *@param string $timezone
     *@param string $notifyemail
     *@param string $organizationtype
     *@param string $organizationname
     *@param string $divauserid
     *@param string $orgparentid
     *@param string $orgcustomerstatus
     *@param string $userimage
     *@return array
     */
    public function modifyCustomer($firstname=null,$lastname=null,$email=null,$phone=null,$zip=null,$country=null,$city=null,$street=null,$state=null,$timezone=null,$notifyemail=null,$organizationtype=null,$organizationname=null,$divauserid=null,$orgparentid=null,$orgcustomerstatus=null,$userimage=null);
}
