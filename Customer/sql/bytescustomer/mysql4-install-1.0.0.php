<?php
$installer = $this;
$installer->startSetup();

$installer->run("  
-- DROP TABLE IF EXISTS {$this->getTable('bytes_state_customer')};
CREATE TABLE {$this->getTable('bytes_state_customer')} (
    `id` int(11) unsigned NOT NULL auto_increment,
    `category_id` int(11) NOT NULL default '0',
    `customer_id` int(11) NOT NULL default '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;  
");

$installer->endSetup();

?>