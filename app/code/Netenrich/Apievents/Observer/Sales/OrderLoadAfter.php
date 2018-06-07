<?php

namespace Netenrich\Apievents\Observer\Sales;



use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;


class OrderLoadAfter implements ObserverInterface

{
  public function __construct(
       \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory,
       \Netenrich\Company\Model\CompanyFactory $companyFactory,
       \Netenrich\Company\Model\CompanypathFactory $companypathFactory,
       \Magento\Customer\Model\CustomerFactory $customerFactory,
       CustomerRepositoryInterface $customerRepository,
       \Magento\Directory\Model\CountryFactory $countryFactory,
       \Magento\Customer\Model\AddressFactory $addressFactory

   )
   {
       $this->orderExtensionFactory = $orderExtensionFactory;
       $this->customerFactory = $customerFactory;
       $this->companyFactory = $companyFactory;
       $this->companypathFactory=$companypathFactory;
       $this->customerRepository = $customerRepository;
       $this->_countryFactory = $countryFactory;
       $this->addressFactory = $addressFactory;

   }

public function execute(\Magento\Framework\Event\Observer $observer)

{

    $order = $observer->getOrder();
    $extensionAttributes = $order->getExtensionAttributes();



    if ($extensionAttributes === null) {
        $extensionAttributes = $this->orderExtensionFactory->create();
    }


    $attr = $order->getData('distributor_name');
    $customerId = $order->getData('customer_id');
	$customergroupId = $order->getData('customer_group_id');
    $orderId = $order->getData('entity_id');



///////---updating custom column
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
 $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
 $connection = $resource->getConnection();
 $tableName = $resource->getTableName('sales_order_grid'); //gives table name with prefix


$billing_company=$order->getBillingAddress()->getCompany();
$shipping_company=$order->getShippingAddress()->getCompany();
if($customergroupId!=7)
{
$customer_addressid=$order->getShippingAddress()->getCustomerAddressId();
$getcustomerid = $this->addressFactory->create()->load($customer_addressid);
$getcid=$getcustomerid->getParentId();
 $sqlcustomer = "UPDATE `sales_order_grid` SET `customer_id` = '".$getcid."' WHERE `sales_order_grid`.`entity_id` =".$orderId;
   $connection->query($sqlcustomer);
   
  $sqlorders = "UPDATE `sales_order` SET `customer_id` = '".$getcid."' WHERE `sales_order`.`entity_id` =".$orderId;
   $connection->query($sqlorders);
}
 //Select Data from table

   $sql = "UPDATE `sales_order_grid` SET `billing_name` = '".$billing_company."' WHERE `sales_order_grid`.`entity_id` =".$orderId;
   $connection->query($sql);
   $sql1 = "UPDATE `sales_order_grid` SET `shipping_name` = '".$shipping_company."' WHERE `sales_order_grid`.`entity_id` =".$orderId;
   $connection->query($sql1);



$biilingcountryid=$order->getBillingAddress()->getCountryId();
$shippingcountryid=$order->getShippingAddress()->getCountryId();

    $country = $this->_countryFactory->create()->loadByCode($biilingcountryid);
    $billing_country_name=$country->getName();

    $country = $this->_countryFactory->create()->loadByCode($shippingcountryid);
    $shipping_country_name=$country->getName();


    $groupid=array(4=>"Service Provider",5=>"Distributer",6=>"Partner",7=>"Client");
    $group_id=$order->getData('customer_group_id');
    $group_name=$groupid[$group_id];

    //-------------------------------------------------------------------------------------------//
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

//-------------------------------------------------------------------------------------------//

    $extensionAttributes->setBillingCountryName($billing_country_name);
    $extensionAttributes->setShippingCountryName($shipping_country_name);
    $extensionAttributes->setCustomerGroupName($group_name);
    $extensionAttributes->setDistributorName($distributerName);
    $order->setExtensionAttributes($extensionAttributes);

    return $order;
}





}
