<?php
namespace Netenrich\Oms\Block\Adminhtml\Order\View;
class View extends \Magento\Sales\Block\Adminhtml\Order\View\Info
{




  /**
    * Return name of the customer group.
    *
    * @return string
    */
   public function getCustomerGroupNameF()
   {

  //    $customerGroupId = $this->getOrder()->getCustomerId();

  //  return $customerGroupId;

    $customerId = $this->getOrder()->getCustomerId();
              $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
               $customerObj = $objectManager->create('Magento\Customer\Model\Customer')->load($customerId);
               $customerEmail = $customerObj->getEmail();
               $customerParent= $customerObj->getOrgparentid();


               $customerObjCollection=$objectManager->create('Magento\Customer\Model\Customer')->getCollection()->addAttributeToSelect('*');
               foreach ($customerObjCollection as $customerOC) {
                 # code...
                 if($customerOC->getDivauserid()==$customerParent)
                    {
                      $distributerId=$customerOC->getOrgparentid();
                    }
                  }
 //return $distributerId;
                $customerObjDistributer = $objectManager->create('Magento\Customer\Model\Customer')->getCollection()->addAttributeToSelect('*');

                foreach ($customerObjDistributer as $customerOD) {
                  # code...
                  if($customerOD->getDivauserid()==$distributerId)
                     {
                       $distributerEntityId=$customerOD->getEntityId();
                     }

               }

               $customerObjD = $objectManager->create('Magento\Customer\Model\Customer')->load($distributerEntityId);
               $distributerName=$customerObjD->getDefaultShippingAddress()->getCompany();



               return $distributerName;



             }


}
