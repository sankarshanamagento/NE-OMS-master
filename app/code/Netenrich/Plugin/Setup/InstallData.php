<?php
namespace Netenrich\Plugin\Setup;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
class InstallData implements InstallDataInterface {
    /**
     * Customer setup factory
     *
     * @var \Magento\Customer\Setup\CustomerSetupFactory
     */
    private $customerSetupFactory;
    public function __construct(CustomerSetupFactory $customerSetupFactory) {
        $this->customerSetupFactory = $customerSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
        $customerSetup->addAttribute(Customer::ENTITY, 'organizationtype', [
            'label' => 'Organization Type',
            'input' => 'select',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 240,
            'visible' => true,
			'source' => 'Netenrich\Company\Model\Entity\CompanyBoolean',
            'system' => false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'organizationtype');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'organizationname', [
            'label' => 'Organization Name',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 50,
            'visible' => true,
			'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'organizationname');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'divauserid', [
            'label' => 'User Id',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 60,
            'visible' => false,
            'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
		  $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'divauserid');
        //$attribute->setData('used_in_forms', []);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'orgparentid', [
            'label' => 'parent Id',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 70,
            'visible' => false,
            'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'orgparentid');
        //$attribute->setData('used_in_forms', []);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'orgcustomerstatus', [
            'label' => ' Status',
            'input' => 'select',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 250,
            'visible' => true,
			'source'  => 'Netenrich\Company\Model\Entity\Boolean',
            'system' =>false,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'is_searchable_in_grid' => true]
        );
        // add attribute to form
        /** @var  $attribute*/
		$attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'orgcustomerstatus');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'orgtimezone', [
            'label' => 'Time Zone',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 260,
            'visible' => true,
            'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'orgtimezone');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'notificationemail', [
            'label' => 'Notification Email',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 260,
            'visible' => true,
            'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'notificationemail');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
		$customerSetup->addAttribute(Customer::ENTITY, 'userimage', [
            'label' => 'User Image',
            'input' => 'text',
			'type'  => 'varchar',
            'required' => false,
            'sort_order' => 270,
            'visible' => false,
            'system' =>false,
            'is_used_in_grid' => false,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => false,
            'is_searchable_in_grid' => false]
        );
        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'userimage');
        $attribute->setData('used_in_forms', ['adminhtml_customer', 'customer_account_create']);
        $attribute->save();
        $setup->endSetup();
    }
}
