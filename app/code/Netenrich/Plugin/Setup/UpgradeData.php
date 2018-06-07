<?php
namespace Netenrich\Plugin\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
/**
 * @codeCoverageIgnore
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * Vendor setup factory
     *
     * @var VendorSetupFactory
     */
    private $customerSetupFactory;
    /**
     * Init
     *
     * @param VendorSetupFactory $taxSetupFactory
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            /** @var VendorSetup $vendorSetup */
            $setup->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
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
		  $setup->endSetup();
        }
		if (version_compare($context->getVersion(), '1.0.2') < 0) {
            /** @var VendorSetup $vendorSetup */
            $setup->startSetup();
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
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



///////////////////////////////

if (version_compare($context->getVersion(), '1.0.3', '<')) {
        /** @var VendorSetup $vendorSetup */
        $setup->startSetup();
    /** @var CustomerSetup $customerSetup */
    $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
$customerSetup->addAttribute(Customer::ENTITY, 'usertype', [
        'label' => 'User Type',
        'input' => 'text',
        'type'  => 'varchar',
        'required' => false,
        'sort_order' => 70,
        'visible' => false,
        'system' =>false,
        'is_used_in_grid' => true,
        'is_visible_in_grid' => true,
        'is_filterable_in_grid' => true,
        'is_searchable_in_grid' => true]
    );
    // add attribute to form
    /** @var  $attribute */
    $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'orgparentid');
    //$attribute->setData('used_in_forms', []);
    $attribute->save();
    $setup->endSetup();
    }






    }
}
