<?php

namespace Netenrich\Services\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface {

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Creating table Netenrich_Services
         */
        $table = $installer->getConnection()->newTable(
                                $installer->getTable('netenrich_partnumbermap')
                        )->addColumn(
                                'part_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'auto_increment' => true,'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                        )->addColumn(
                                'partnumber', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Part Number'
                        )->addColumn(
                                'service_package', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true], 'Service Package'
                        )->addColumn(
                                'practice', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true, 'default' => null], 'Practice'
                        )->addColumn(
                                'towers', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true, 'default' => null], 'Towers'
                        )
                        ->addColumn(
                                'assets', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true, 'default' => null], 'Assets'
                        )
                        ->addColumn(
                                'payable', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 55, ['nullable' => true, 'default' => null], 'Payable'
                        )
						->addColumn(
                                'service_type', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 355, ['nullable' => true, 'default' => null], 'Service Type'
                        )->addColumn(
                                'contractterm', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 55, ['nullable' => true, 'default' => null], 'Contract Term'
                        )->addColumn(
                                'servicelevel', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 55, ['nullable' => true, 'default' => null], 'Service Level'
                        )->addIndex(
                                $installer->getIdxName(
                                        'netenrich_partnumbermap', ['part_id'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                                ), ['part_id'], ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
                        )->setComment(
                'Product mapping information'
        );
        $installer->getConnection()->createTable($table);

      

         $table = $installer->getConnection()->newTable(
                        $installer->getTable('netenrich_customer_path')
                )->addColumn(
                        'path_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['auto_increment' => true,'identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                )->addColumn(
                        'customer_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Organisation Id'
                )->addColumn(
                        'partnerdivaid', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false], 'partner ID'
                )->addColumn(
                        'distibutorid', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false], 'Distibutor Id'
                )->addColumn(
                        'servriceprodiverid', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false], 'Service Provider'
                )->addIndex(
                        $installer->getIdxName(
                                'netenrich_customer_path', ['path_id'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                        ), ['path_id'], ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
                )->setComment(
                'Organisation path Information'
        );
        $installer->getConnection()->createTable($table); 

        $installer->endSetup();
    }

}
