<?php

namespace Netenrich\Webhook\Setup;

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
         * Creating table Netenrich_Webhook
         */
        $table = $installer->getConnection()->newTable(
                                $installer->getTable('netenrich_webhook')
                        )->addColumn(
                                'api_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'auto_increment' => true,'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                        )->addColumn(
                                'jobid', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Part Number'
                        )->addColumn(
                                'entity_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true], 'Service Package'
                        )->addColumn(
                                'entity_id', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true, 'default' => null], 'Practice'
                        )->addColumn(
                                'payload', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 500000, ['nullable' => true, 'default' => null], 'payload'
                        )->addColumn(
                                'status', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 55, ['nullable' => true, 'default' => null], 'status'
                        )
                        ->addColumn(
                                'response', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 500, ['nullable' => true, 'default' => null], 'Response'
                        )
						->addColumn(
                                'createaction', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 200, ['nullable' => true, 'default' => null], 'Createaction'
                        )
                        ->addIndex(
                                $installer->getIdxName(
                                        'netenrich_Webhook', ['api_id'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                                ), ['api_id'], ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
                        )->setComment(
                'api information'
        );
        $installer->getConnection()->createTable($table);

      $installer->endSetup();
    }

}
