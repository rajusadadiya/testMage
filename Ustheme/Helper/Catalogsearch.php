<?php
/* Developer By US Legal */
class Bytes_Ustheme_Helper_Catalogsearch extends Mage_CatalogSearch_Helper_Data{

	/*public function getStoreCategories(){
		
		$helper = Mage::helper('catalog/category');
		return $helper->getStoreCategories();
	}

	public function getSelectedCategory()
	{
		$catid = (int)addslashes($_REQUEST['category']); 
		$cat="";
		if($catid>1)
			$cat = Mage::getModel('catalog/category')->load($catid); 
		return $cat; 
	}*/

	public function getStoreStates(){
		return Mage::helper("md_membership")->getState();
	}

	public function getSelectedState(){
		$state = stripslashes($_REQUEST["state"]);
		return $state;
	}
}