<?php
/**
 * Copyright © 2015 Netn. All rights reserved.
 */

namespace Netn\Distibutorproducts\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'distibutorproducts_distibutorproducts'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('distibutorproducts_distibutorproducts')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'distibutorproducts_distibutorproducts'
        )
		->addColumn(
            'assign_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'assign_id'
        )
		->addColumn(
            'productid',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Productid'
        )
		->addColumn(
            'productname',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'Productname'
        )
		->addColumn(
            'distibutor_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'distibutor_id'
        )
		->addColumn(
            'productprice',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'productprice'
        )
		->addColumn(
            'discountprice',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'discountprice'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Netn Distibutorproducts distibutorproducts_distibutorproducts'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/

        $installer->endSetup();

    }
}
