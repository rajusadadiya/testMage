<?php

class Bytes_Customer_Model_Observer extends Varien_Event_Observer
{
   /*public function checkUserSubscriber($observer)
   {
   		$Action = $observer->getEvent()->getControllerAction()->getFullActionName();
   		$actions = [
   			"catalog_product_index",
   			"catalog_category_view",
   			"catalog_product_view",
   		];
   		$websiteCode = Mage::app()->getWebsite()->getCode();
   		$webcodes = [
   			Mage::getStoreConfig('bytescategorytab/smallbusiness/code'),
   			Mage::getStoreConfig('bytescategorytab/personalforms/code')
   		];
   		
   		if(in_array($Action, $actions) && in_array($websiteCode, $webcodes) &&Mage::getSingleton('customer/session')->isLoggedIn()){
 			
 			$customer = Mage::getSingleton('customer/session')->getCustomer();
 			$customerWebsiteId = $customer->getWebsiteId();
 			$customerId = $customer->getId();
 			$currentWebsiteId = Mage::app()->getWebsite()->getId();
 			$storeId = Mage::app()->getStore()->getStoreId();

 			
 			$plans = Mage::getModel("md_membership/subscribers")->getCollection();
 			$plans->addFieldToFilter("customer_id",$customerId);
 			$plans->addFieldToFilter("store_id",$storeId);
 			$plans->addFieldToFilter("status",1);
 			
 			if($plans->count() == 0){
 				
 				Mage::app()->getResponse()->setRedirect(Mage::getUrl("bytescustomer/subscribe"));
 			}
 			else{
 				
 				$plan = $plans->getFirstItem();
 				$customerStateCollection = Mage::getModel("bytescustomer/customer")->getCollection();
 				$customerStateCollection->addFieldToFilter('customer_id',$customerId);
 				$customerStateCollection->addFieldToFilter('store_id',$storeId);

 				
 				if($customerStateCollection->count() == 0){
 					Mage::app()->getResponse()->setRedirect(Mage::getUrl("bytescustomer/subscribe/addstate"));
 				}
 			}
   	}
   }*/

   public function checkUserWebsite($observer){
      if(Mage::getSingleton('customer/session')->isLoggedIn()){
         $action = $observer->getEvent()->getControllerAction()->getFullActionName();
         //echo $action;
         $actions = ['md_membership_index_list','md_membership_index_payment'];
         $actions2 = ['catalog_product_index','catalog_category_view','catalog_product_view'];
         $customer = Mage::getSingleton('customer/session')->getCustomer();

         $plans = Mage::getModel("md_membership/subscribers")->getCollection();
         $plans->addFieldToFilter("customer_id",$customerId);
         $plans->addFieldToFilter("store_id",$storeId);
         $plans->addFieldToFilter("status",1);

         if(in_array($action, $actions) && $customer->getWebsiteId() != Mage::app()->getWebsite()->getId()){
            Mage::app()->getResponse()->setRedirect(Mage::getUrl("customer/account"));
         }
         elseif(in_array($action, $actions2) && $plans->count() > 0 && $customer->getWebsiteId() == Mage::app()->getWebsite()->getId()){
            $customerStateCollection = Mage::getModel("bytescustomer/customer")->getCollection()->addFieldToFilter('customer_id',$customer->getId())->addFieldToFilter('store_id',Mage::app()->getStore()->getId());
            if($customerStateCollection->count() == 0){
               Mage::app()->getResponse()->setRedirect(Mage::getUrl("bytescustomer/subscribe/addstate"));
            }
         }
      }
   }
}