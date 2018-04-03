<?php
class Bytes_Ustheme_Block_Ustopmenu extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function getPersonalLinks(){
        if($this->getLinkCode() == "personal"){
            $linkModel = Mage::getModel("ustheme/personallinks");            
            $personalLinks = $linkModel->getLinks();
            return $personalLinks;
        }
    }

    public function getBussinessLinks(){
        if($this->getLinkCode() == "bussiness"){
            $linkModel = Mage::getModel("ustheme/bussinesslinks");            
            $bussinesLinks = $linkModel->getLinks();
            return $bussinesLinks;
        }
    }

    public function getHelpLinks(){
        if($this->getLinkCode() == "help"){
            $linkModel = Mage::getModel("ustheme/helplinks");            
            $helpLinks = $linkModel->getLinks();
            return $helpLinks;
        }
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

    public function addCartLink()
    {
        $parentBlock = $this->getParentBlock();
        if ($parentBlock && Mage::helper('core')->isModuleOutputEnabled('Mage_Checkout')) {
            $count = $this->getSummaryQty() ? $this->getSummaryQty()
                : $this->helper('checkout/cart')->getSummaryCount();
            if ($count == 1) {
                $text = $this->__('My Cart (%s item)', $count);
            } elseif ($count > 0) {
                $text = $this->__('My Cart (%s items)', $count);
            } else {
                $text = $this->__('My Cart');
            }

            $parentBlock->removeLinkByUrl($this->getUrl('checkout/cart'));
            $parentBlock->addLink($text, 'checkout/cart', $text, true, array(), 50, null, 'class="top-link-cart"');
        }
        return $this;
    }
}
