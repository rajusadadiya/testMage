<?php
class Bytes_Ustheme_Block_Uscategory extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function getAllCategory(){        
        $_helper = Mage::helper('catalog/category');
        $_categories = $_helper->getStoreCategories();
        return $_categories;
    }
    
    public function getSmallbusinessCategory(){
        $categoryCollection = Mage::helper("bytescategory")->getSmallbusinessCategory();
        return $categoryCollection;
    }

    public function getPersonalformsCategory(){
        $categoryCollection = Mage::helper("bytescategory")->getPersonalformsCategory();
        return $categoryCollection;
    }

    public function getSmallbusinessUrl($urlPath){
        $smallbusinessStoreId = Mage::helper("bytescategory")->getSmallbusinessStoreId();
        return Mage::app()->getStore($smallbusinessStoreId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK).$urlPath; 
    }

    public function getPersonalformsUrl($urlPath){
        $personalStoreId = Mage::helper("bytescategory")->getPersonalformsStoreId();
        return Mage::app()->getStore($personalStoreId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK).$urlPath;
    }    
}
