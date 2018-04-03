<?php
  
class Bytes_Category_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	const CATEGORY_ENTITY_CODE = 'catalog_category';

    const ENTITY_TYPE_ID = 3;

    const EAV_ENTITY = 'eav_attribute';

    public $_connection; 

	public function getCategoryEntityId(){
        $eavSetupModel = Mage::getModel('eav/entity_type')->loadByCode(self::CATEGORY_ENTITY_CODE);
        return $eavSetupModel->getId();        
	}

	public function getCategoryNameAttributeId(){
		return Mage::getResourceModel('eav/entity_attribute')->getIdByCode(self::CATEGORY_ENTITY_CODE, 'name');
	}

	public function getStoreCollection(){
		$storeData = array();
        foreach(Mage::app()->getWebsites() as $website){
            foreach ($website->getGroups() as $group) {               
                $stores = $group->getStores();
                foreach ($stores as $store) {
                    $storeData[$store->getId()]["website_id"] = $website->getId();
                    $storeData[$store->getId()]["website_code"] = $website->getCode();
                    $storeData[$store->getId()]["website_name"] = $website->getName();
                    $storeData[$store->getId()]["group_id"] = $group->getId();
                    $storeData[$store->getId()]["group_name"] = $group->getName();
                    $storeData[$store->getId()]["group_category_id"] = $group->getRootCategoryId();
                    $storeData[$store->getId()]["store_id"] = $store->getId();
                    $storeData[$store->getId()]["store_code"] = $store->getCode();
                    $storeData[$store->getId()]["store_name"] = $store->getName();
                    
                }
            }
        }
        return $storeData;
	}

    public function rootCategoryExist(){
        //$configValue = Mage::getStoreConfig('sectionName/groupName/fieldName');
        $exist = false;
        $websitecode = Mage::app()->getWebsite()->getCode();
        if(Mage::getStoreConfig('bytescategorytab/smallbusiness/code') == $websitecode){
            $exist = true;
        }
        elseif(Mage::getStoreConfig('bytescategorytab/personalforms/code') == $websitecode){
            $exist = true;
        }
        return $exist;
    }

    public function getRootCategoryId(){
        //$configValue = Mage::getStoreConfig('sectionName/groupName/fieldName');
        $websitecode = Mage::app()->getWebsite()->getCode();
        if(Mage::getStoreConfig('bytescategorytab/smallbusiness/code') == $websitecode){
            return Mage::getStoreConfig('bytescategorytab/smallbusiness/rootcategory');
        }
        elseif(Mage::getStoreConfig('bytescategorytab/personalforms/code') == $websitecode){
            return Mage::getStoreConfig('bytescategorytab/personalforms/rootcategory');
        }
        return false;
    }

    public function getCoreConnection(){
        if($this->_connection){
            return $this->_connection;
        }
        else{
            $this->_connection = Mage::getSingleton('core/resource')->getConnection('core_read');   
            return $this->_connection;   
        }
        return $this->_connection;
    }

    public function getCategoryAttributeIdByCode($code){
        $sql = "SELECT attribute_id FROM ".self::EAV_ENTITY." WHERE attribute_code = '$code' AND entity_type_id =".self::ENTITY_TYPE_ID;
        return $this->getCoreConnection()->fetchOne($sql);        
    }

        
    public function getAllCategoryIsState(){
        $nameAttrId = $this->getCategoryAttributeIdByCode("name");
        $stateAttrId = $this->getCategoryAttributeIdByCode("is_state");
        $statusAttrId = $this->getCategoryAttributeIdByCode("is_active");
        $rootCategoryId = $this->getRootCategoryId();

        $mainSql = "SELECT category.entity_id AS category_id, name.value as category_name
                    FROM catalog_category_entity AS category 
                    INNER JOIN catalog_category_entity_varchar AS name 
                    ON category.entity_id = name.entity_id AND  category.entity_type_id = name.entity_type_id
                    INNER JOIN catalog_category_entity_int AS state 
                    ON category.entity_id = state.entity_id AND category.entity_type_id = state.entity_type_id
                    INNER JOIN catalog_category_entity_int AS active 
                    ON category.entity_id = active.entity_id AND  category.entity_type_id = active.entity_type_id  
                    WHERE category.parent_id = ".$rootCategoryId." AND state.attribute_id = ".$stateAttrId." AND state.value = 1 AND name.attribute_id = ".$nameAttrId." AND active.value = 1 AND active.attribute_id = ".$statusAttrId;
        return $this->getCoreConnection()->fetchAll($mainSql);
    }

    public function getCategoryById($categoryId){
        $nameAttrId = $this->getCategoryAttributeIdByCode("name");
        $stateAttrId = $this->getCategoryAttributeIdByCode("is_state");
        $statusAttrId = $this->getCategoryAttributeIdByCode("is_active");        

        $mainSql = "SELECT category.entity_id AS category_id, name.value as category_name
                    FROM catalog_category_entity AS category 
                    INNER JOIN catalog_category_entity_varchar AS name 
                    ON category.entity_id = name.entity_id AND  category.entity_type_id = name.entity_type_id
                    INNER JOIN catalog_category_entity_int AS state 
                    ON category.entity_id = state.entity_id AND category.entity_type_id = state.entity_type_id
                    INNER JOIN catalog_category_entity_int AS active 
                    ON category.entity_id = active.entity_id AND  category.entity_type_id = active.entity_type_id  
                    WHERE category.entity_id = ".$categoryId." AND state.attribute_id = ".$stateAttrId." AND state.value = 1 AND name.attribute_id = ".$nameAttrId." AND active.value = 1 AND active.attribute_id = ".$statusAttrId;

        return $this->getCoreConnection()->fetchRow($mainSql);
    }

    public function getCategoryProductCollectionCount($categoryId){

        $_productCollection = null;
        $categoryId = (int) $categoryId;
        if(Mage::getSingleton("customer/session")->isLoggedIn()){
            $_productCollection = Mage::getModel('catalog/category')->load($categoryId)->getProductCollection();
            $pids = $this->getProductIds();            
            
            if(is_array($pids)){
                $_productCollection->addAttributeToFilter('entity_id', array('in' => $pids)); 
            }
        }
        else{
            $_productCollection = Mage::getModel('catalog/category')->load($categoryId)->getProductCollection();
        }
        return $_productCollection->count();
    }

    public function getLoginCategoryId(){
        $storeId = Mage::app()->getStore()->getStoreId();
        if(Mage::getSingleton("customer/session")->isLoggedIn()){
            $customer = Mage::getSingleton("customer/session")->getCustomer();
            //$categoryState = Mage::getModel("bytescustomer/customer")->load($customer->getId(),"customer_id");                
            $categoryStateCollection = Mage::getModel('bytescustomer/customer')->getCollection();
            $categoryStateCollection->addFieldToFilter("customer_id",$customer->getId());
            $categoryStateCollection->addFieldToFilter("store_id",$storeId);

            if($categoryStateCollection->count() > 0){
                return $categoryStateCollection->getFirstItem()->getCategoryId();
            }
            return 0;
        }
        return 0;
    }

    public function getProductIds(){
        $this->checkCustomerIsInStore();
        $_productCollection = null;
        $categoryId = (int) $this->getLoginCategoryId();
        if($this->getLoginCategoryId() != 0 && $this->getLoginCategoryId() != ''){

            $sql = "SELECT product_id, category_id FROM catalog_category_product where category_id = ".$categoryId;
            $all = $this->getCoreConnection()->fetchAll($sql);
            $ids = [];
            if(count($all)){
                foreach ($all as $citem) {
                    $ids[] = $citem["product_id"];
                }    
            }
            return $ids;
        }
        return '';
    }

    public function getSmallbusinessCategory(){
        //get Smallbusiness category id
        $smallbusinessCategoryId = Mage::getStoreConfig('bytescategorytab/smallbusiness/category');
        $category = Mage::getModel("catalog/category")->load($smallbusinessCategoryId);
        $categorys = $category->getChildrenCategories();
        return $categorys;
    }

    public function getPersonalformsCategory(){
        //get Smallbusiness category id
        $persoanlformsCategoryId = Mage::getStoreConfig('bytescategorytab/personalforms/category');
        $category = Mage::getModel("catalog/category")->load($persoanlformsCategoryId);
        $categorys = $category->getChildrenCategories();
        return $categorys;
    }


    public function getSmallbusinessStoreId(){
        $websiteCode = Mage::getStoreConfig('bytescategorytab/smallbusiness/code');
        $website = Mage::getModel( "core/website" )->load($websiteCode);
        $storeId = $website->getDefaultGroup()->getDefaultStore()->getId();
        //$storeId =  Mage::getModel('core/store')->load($storeCode, 'code')->getId();
        return $storeId;
    }

    public function getPersonalformsStoreId(){
        $websiteCode = Mage::getStoreConfig('bytescategorytab/personalforms/code');
        $website = Mage::getModel("core/website")->load($websiteCode);
        $storeId = $website->getDefaultGroup()->getDefaultStore()->getId();
        //$storeId =  Mage::getModel('core/store')->load($storeCode, 'code')->getId();
        return $storeId;
    }


	
    //--------------------------------

    public function isCategoryFilter(){
        if(Mage::getStoreConfig('bytescategorytab/smallbusiness/code') == Mage::app()->getWebsite()->getCode()){
            return true;
        }
        elseif(Mage::getStoreConfig('bytescategorytab/personalforms/code') == Mage::app()->getWebsite()->getCode()){
            return true;
        }
        return false;
    }


    public function isLogin(){
        if(Mage::getSingleton("customer/session")->isLoggedIn()){
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
            $customerState = '';
            if($customerData["default_billing"] != ''){
                $address = Mage::getModel('customer/address')->load($customerData["default_billing"]);
                $customerState = $address->getData("region");
            }
            return true;
        }
        return false;
    }

    public function getCustomer(){
        return Mage::getSingleton("customer/session")->getCustomer();
    }

    public function isInStore($customer){
        $customer = $this->getCustomer();
        if($customer->getWebsiteId() == Mage::app()->getStore()->getWebsiteId()){
            return true;
        }
        return false;
    }

    public function getCustomerRegion($customer){
        $customerData = Mage::getModel('customer/customer')->load($customer->getId())->getData();
        $customerState = '';
        if($customerData["default_billing"] != ''){
            $address = Mage::getModel('customer/address')->load($customer->getData("default_billing"));
            $customerState = $address->getData("region");
        }
        return $customerState;
    }

    public function getRootCategory(){
        $websitecode = Mage::app()->getWebsite()->getCode();
        $categoryId = 0;
        if(Mage::getStoreConfig('bytescategorytab/smallbusiness/code') == $websitecode){
            $categoryId = Mage::getStoreConfig('bytescategorytab/smallbusiness/category');
        }
        elseif(Mage::getStoreConfig('bytescategorytab/personalforms/code') == $websitecode){
            $categoryId = Mage::getStoreConfig('bytescategorytab/personalforms/category');
        }
        return $categoryId;
    }

    public function getProductIdsByStore(){
        $productIds = [];
        $categoryIds = [];
        $category = Mage::getModel('catalog/category')->load($this->getRootCategory());

        $sk = "SELECT * from catalog_category_entity where path like '%".$this->getRootCategory()."%'";
        $cate = $this->getCoreConnection()->fetchAll($sk);
        foreach ($cate as $c) {
            $categoryIds[] = $c["entity_id"];
        }
        
        $ids = implode(",", $categoryIds);
        $sql = "SELECT product_id FROM catalog_category_product WHERE category_id IN(".$ids.")";
        $pro = $this->getCoreConnection()->fetchAll($sql);
    
        foreach ($pro as $p) {
            if(!in_array($p["product_id"], $productIds)){
                $productIds[] = $p["product_id"];
            }
        }
        return $productIds;
    }

    public function checkCustomerWebsite($customer){
        if($customer->getWebsiteId() == Mage::app()->getWebsite()->getId()){
            return true;
        }
        return false;
    }
} 