<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Services\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface {
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'servicename', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                //'nullable' => false,
                'default' => null,
                'comment' => 'servicename'
                    ]
            );
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'shortdecription', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                 'nullable' => true,
                'default' => null,
                'comment' => 'Short Decription'
                    ]
            );
        
		
		
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'serviceuniqueid', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 55,
				'unsigned' => true,
                'nullable' => true,
                'default' => null,
                'comment' => 'serviceuniqueid'
                    ]
            );
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'decription', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'unsigned' => true,
                'nullable' => true,
                'comment' => 'decription'
                    ]
            );
    }
	
	if (version_compare($context->getVersion(), '1.0.5', '<')) { 
	
		$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'store', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				'unsigned' => true,
                'nullable' => true,
                'comment' => 'storeid'
                    ]
            );
			
		$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'categoryid', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				'unsigned' => true,
                'nullable' => true,
                'comment' => 'categoryid'
                    ]
            );
			
	    $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_partnumbermap'), 'subcategoryid', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				'unsigned' => true,
                'nullable' => true,
                'comment' => 'subcategoryid'
                    ]
            );
	}
}

}