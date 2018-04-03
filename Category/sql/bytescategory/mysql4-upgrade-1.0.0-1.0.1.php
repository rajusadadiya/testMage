<?php
$installer = $this;
$installer->startSetup();
$states = Mage::helper('md_membership')->getState();
$stateArr = [];
foreach ($states as $region){
	$stateArr[$region->getName()] = [$region->getName()];
}
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 
	'product_state', [
		'attribute_set'	=> '*',
		'group'	=> 'General',
		'label'	=> 'States', 
		'type' => 'varchar', 
		'input' => 'multiselect', 
		'backend' => 'eav/entity_attribute_backend_array', 
		'frontend' => '', 
		'source' => '', 
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE, 
		'visible' => true, 
		'required' => false, 
		'is_user_defined' => true, 
		'searchable' => false, 
		'filterable' => false, 
		'comparable' => false, 
		'option' => [
		 		'value' => $stateArr
		 	], 
		'visible_on_front' => false, 
		'visible_in_advanced_search' => false, 
		'unique' => false
	]
);
$installer->endSetup();

?>