<?php

namespace Netenrich\Company\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface {

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {

// Set up data rows
        $dataRows = [
            [

                'org_name' => 'Service Provider',
            ],
            [

                'org_name' => 'Distributor',
            ],
            [

                'org_name' => 'Partner',
            ],
            [

                'org_name' => 'Client',
            ]
        ];

        // Generate news items
        foreach ($dataRows as $data) {
            //$this->createNews()->setData($data)->save();
            $setup->getConnection()->insert($setup->getTable('netenrich_organisation'), $data);
        }
    }

}
