<?php
  
class Bytes_Customer_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function checkCustomer($customerId,$planId,$storeCategoryId){

        $currentCategoryId = (int)$storeCategoryId;
        $storeId = Mage::app()->getStore()->getStoreId();
        if(is_numeric($currentCategoryId)){
            $customerStateModule = Mage::getModel('bytescustomer/customer')->getCollection();
            $customerStateModule->addFieldToFilter("customer_id",$customerId);
            $customerStateModule->addFieldToFilter("store_id",$storeId);           

            if($customerStateModule->count() > 0){
                $customerStateItem = $customerStateModule->getFirstItem();
                if($customerStateItem->getCategoryId() != $currentCategoryId){

                    $category = Mage::helper("bytescategory")->getCategoryById($currentCategoryId);
                    $customerStateItem->setCategoryName($category["category_name"]);
                    $customerStateItem->setPlanId($planId);
                    $customerStateItem->setCategoryId($currentCategoryId);
                    $customerStateItem->setCustomerId($customerId);
                    try{
                        $customerStateItem->save();
                    }
                    catch(Exception $e){
                        Mage::getSingleton('core/session')->addError(Mage::helper('bytescustomer')->__('Customer saving error %s',$e->getMessage()));
                    }
                }
            }
            else{
                $model = Mage::getModel('bytescustomer/customer');
                $category = Mage::helper("bytescategory")->getCategoryById($currentCategoryId);
                try{
                    $model->setData("category_id",$currentCategoryId);
                    $model->setData("store_id",$storeId);
                    $model->setData("plan_id",$planId);
                    $model->setData("customer_id",$customerId);
                    $model->setData("category_name",$category["category_name"]);
                    $model->save();
                }
                catch(Exception $e){
                    Mage::getSingleton('core/session')->addError(Mage::helper('bytescustomer')->__('Customer saving error %s',$e->getMessage()));
                }
            }
        }
    }    
} 