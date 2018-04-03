<?php

class Bytes_Ustheme_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function displayBussinessForms(){
        // Gets the Personalform websitecode
        $websiteCode = Mage::getStoreConfig('bytescategorytab/personalforms/code');
        $website = Mage::getModel( "core/website" )->load($websiteCode);
        /* get smallbusiness default store id */
        $storeId = $website->getDefaultGroup()->getDefaultStore()->getId();
        // Gets the current store's code
        $currentStore = Mage::app()->getStore()->getId();
        if($currentStore == $storeId){
            return false;
        }
        return true;
    }

    public function displayPersonalForms(){
        // Gets the smallbusiness code
        $websiteCode = Mage::getStoreConfig('bytescategorytab/smallbusiness/code');
        $website = Mage::getModel( "core/website" )->load($websiteCode);
        /* get smallbusiness default store id */
        $storeId = $website->getDefaultGroup()->getDefaultStore()->getId();
        // Gets the current store's code
        $currentStore = Mage::app()->getStore()->getId();
        if($currentStore == $storeId){
            return false;
        }
        return true;
    }    
} 