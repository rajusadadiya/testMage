<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('bytescustomer/customer'), 'store_id',array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'default' => null,
        'comment' => 'Store ID'
    ));
$installer->getConnection()->addColumn($installer->getTable('bytescustomer/customer'), 'plan_id',array(
        'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable' => true,
        'default' => null,
        'comment' => 'Active Plan ID'
    ));
$installer->endSetup();