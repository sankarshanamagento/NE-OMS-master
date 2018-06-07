<?php

namespace Netenrich\Company\Setup;

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
         * Creating table Netenrich_Company
         */
        $table = $installer->getConnection()->newTable(
                                $installer->getTable('netenrich_organisation_type')
                        )->addColumn(
                                'org_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                        )->addColumn(
                                'name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true], 'Company Name'
                        )->addColumn(
                                'address', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Address'
                        )->addColumn(
                                'address2', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, '2M', ['nullable' => true, 'default' => null], 'Address2'
                        )->addColumn(
                                'country', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true, 'default' => null], 'Country'
                        )->addColumn(
                                'state', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true, 'default' => null], 'State'
                        )
                        ->addColumn(
                                'city', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true, 'default' => null], 'City'
                        )
                        ->addColumn(
                                'zip', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Postal Code'
                        )
						->addColumn(
                                'website', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true, 'default' => null], 'Website'
                        )->addColumn(
                                'phone', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Phone'
                        )->addColumn(
                                'fax', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Fax'
                        )->addColumn(
                                'org_type_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Organisation Type'
                        )->addColumn(
                                'timzone', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => true, 'default' => null], 'Timezone'
                        )
                        ->addColumn(
                                'parent_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Orgnisation Parent Type '
                        )->addColumn(
                                'activated', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Activated'
                        )
                        ->addColumn(
                                'created_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, ['nullable' => false], 'Created At'
                        )->addColumn(
                                'modified_at', \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, ['nullable' => false], 'Modified At'
                        )->addColumn(
                                'email', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => false], 'Created Email'
                        )->addColumn(
                                'created_by', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => false], 'Created By'
                        )->addColumn(
                                'updated_by', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, ['nullable' => false], 'Updated By'
                        )
                        ->addIndex(
                                $installer->getIdxName(
                                        'netenrich_organisation_type', ['org_type_id'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                                ), ['org_type_id'], ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
                        )->setComment(
                'Organisation Type information'
        );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
                        $installer->getTable('netenrich_organisation')
                )->addColumn(
                        'org_type_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                )->addColumn(
                        'org_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => true], 'Organisaion Type'
                )->setComment(
                'Organisation information'
        );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
                        $installer->getTable('netenrich_organisation_path')
                )->addColumn(
                        'path_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                )->addColumn(
                        'org_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Organisation Id'
                )->addColumn(
                        'path', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false], 'Organisaion Path'
                )->addColumn(
                        'level', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, 255, ['nullable' => false], 'Organisaion Level'
                )->addIndex(
                        $installer->getIdxName(
                                'netenrich_organisation_type', ['org_id'], \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX
                        ), ['org_id'], ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_INDEX]
                )->setComment(
                'Organisation Type Information'
        );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
