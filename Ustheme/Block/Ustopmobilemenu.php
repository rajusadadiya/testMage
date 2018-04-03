<?php
class Bytes_Ustheme_Block_Ustopmobilemenu extends Mage_Core_Block_Template
{
    public $_menus;

    public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function addMenu($title,$url,$cssClass){
        if($this->_menus == null){
            $this->_menus = [];
        }
        $this->_menus[] = ["label"=> $title,"url"=>$this->getUrl($url),"css_class"=>$cssClass];
    }

    public function getMenus(){
        return $this->_menus;
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
