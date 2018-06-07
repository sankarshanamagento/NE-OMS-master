<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Netenrich\Company\Setup;
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
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'payment_method', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                //'nullable' => false,
                'default' => null,
                'comment' => 'Payment Method'
                    ]
            );
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'notes', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                //'nullable' => false,
                'default' => null,
                'comment' => 'Company Notes'
                    ]
            );
        }
		if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'image', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'unsigned' => true,
                'nullable' => true,
                'default' => null,
                'comment' => 'image Path1'
                    ]
            );
        }
		if (version_compare($context->getVersion(), '1.0.7', '<')) {
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'divauniqueid', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 55,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'divauniqueid'
                    ]
            );
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'status', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
				'unsigned' => true,
                'nullable' => false,
                'default' => 0,
                'comment' => 'status'
                    ]
            );
    }


	if (version_compare($context->getVersion(), '1.0.8', '<')) {
        $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_path'), 'divaid', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 55,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'divaid'
                    ]
            );
    }
	
	
	if (version_compare($context->getVersion(), '1.1.1', '<')) {
       
	   
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'vendortype', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 155,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'vendortype'
                    ]
            );
			
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accounttype', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 155,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accounttype'
                    ]
            );
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'businesstype', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 155,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'businesstype'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'businesstype', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 155,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'businesstype'
                    ]
            );	

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'businessphone', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'businessphone'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'businessfax', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'businessfax'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accountstreet', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accountstreet'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accountcity', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accountcity'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accountcountry', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accountcountry'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accountstate', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accountcountry'
                    ]
            );
  
            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'accountpin', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'accountpin'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'corporatename', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'corporatename'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'corporatetitle', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'corporatetitle'
                    ]
            );

             $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'corporateemail', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'corporateemail'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'corporatephone', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'corporatephone'
                    ]
            );	


            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'apname', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'apname'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'aptitle', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'aptitle'
                    ]
            );

             $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'apemail', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'apemail'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'apphone', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'aptphone'
                    ]
            );


            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'purchasingname', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'purchasingname'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'purchasingtitle', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'purchasingtitle'
                    ]
            );

             $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'purchasingtitleemail', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'purchasingtitleemail'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'purchasingtitlephone', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'purchasingtitlephone'
                    ]
            );	

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'purchasingtitlephone', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'purchasingtitlephone'
                    ]
            );

            $setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'msadocument', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'msa'
                    ]
            );
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'msadocument', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'msa'
                    ]
            );
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'ndadocument', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 255,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'ndadocument'
                    ]
            );
			
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'selrep', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 55,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'selrep'
                    ]
            );
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'vendor', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 55,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'vendor'
                    ]
            );
			
			$setup->getConnection()->addColumn(
                    $setup->getTable('netenrich_organisation_type'), 'customer', [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 55,
				'unsigned' => true,
                'nullable' => false,
                'default' => null,
                'comment' => 'customer'
                    ]
            );
    }
	
	
	if (version_compare($context->getVersion(), '1.1.2', '<')) {
		
		
		$table = $setup->getConnection()->newTable(
                        $setup->getTable('netenrich_organisation_doc')
                )->addColumn(
                        'docid', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true], 'Entity Id'
                )->addColumn(
                        'org_id', \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, ['nullable' => true, 'default' => null], 'Organisation Id'
                )->addColumn(
                        'title', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false], 'Organisaion title'
                )->addColumn(
                        'comments', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 2000, ['nullable' => false], 'Organisaion Comments'
                )->addColumn(
                        'description', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 2000, ['nullable' => false], 'Organisaion Description'
                )->addColumn(
                        'filedata', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 65535,['nullable' => false], 'Organisaion data'
                )->setComment(
                'Organisation document Information');
        $setup->getConnection()->createTable($table);
		$setup->endSetup();
		
	}
    
}
}
