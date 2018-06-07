<?php

namespace Netenrich\Services\Observer\Sales;



use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;


class OrderLoadAfter implements ObserverInterface

{
  public function __construct(
       \Magento\Sales\Api\Data\OrderItemExtensionFactory $orderExtensionFactory,
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

    
}





}
