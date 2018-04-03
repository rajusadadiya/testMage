<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup(‘core_setup’);
$setup->addAttribute('catalog_category', 'is_state', array(
    'input'         => 'select', // you can change here 
    'type'          => 'int',
    'group'         => 'General Information',/// Change here whare you want to show this
    'label'         => 'Is State',
    'visible'       => 1,
    'source'        => 'eav/entity_attribute_source_boolean',
    'required'      => 0,
    'user_defined' => 1,
    'frontend_input' =>'',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible_on_front'  => 1,
));
$installer->endSetup();

?>