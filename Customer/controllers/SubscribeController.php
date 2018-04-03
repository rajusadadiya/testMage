<?php
class Bytes_Customer_SubscribeController extends Mage_Core_Controller_Front_Action {

	public function indexAction() {
		$websiteCode = Mage::app()->getWebsite()->getCode();

   		$webcodes = [
   			Mage::getStoreConfig('bytescategorytab/smallbusiness/code'),
   			Mage::getStoreConfig('bytescategorytab/personalforms/code')
   		];
   		if(in_array($websiteCode, $webcodes)){
   			$this->loadLayout();
	        $this->renderLayout();
   		}
    }

    public function addstateAction(){

    	$websiteCode = Mage::app()->getWebsite()->getCode();

   		$webcodes = [
   			Mage::getStoreConfig('bytescategorytab/smallbusiness/code'),
   			Mage::getStoreConfig('bytescategorytab/personalforms/code')
   		];

    	if(in_array($websiteCode, $webcodes) && Mage::getSingleton('customer/session')->isLoggedIn()){
    		$this->loadLayout();
    		$_storeId = Mage::app()->getStore()->getStoreId();
    		$_customerId = 0;
    		$_customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();

    		$plans = Mage::getModel("md_membership/subscribers")->getCollection();
			$plans->addFieldToFilter("customer_id",$_customerId);
			$plans->addFieldToFilter("store_id",$_storeId);
			$plans->addFieldToFilter("status",1);

			$states = Mage::getModel("bytescustomer/customer")->getCollection();
			$states->addFieldToFilter("customer_id",$_customerId);
			$states->addFieldToFilter("store_id",$_storeId);

			$_planId = 0;
			if($plans->count() > 0 && $states->count() > 0){
				$state = 
				$this->_redirect("customer/account");	
			}
			$this->renderLayout();
    	}

    }

    public function poststateAction(){
    	$_posts = $this->getRequest()->getParams();
    	$_storeId = Mage::app()->getStore()->getStoreId();
    	$_customerId = 0;
        if(isset($_posts["category"]) && $_posts["category"] != ''){
    		if(Mage::getSingleton('customer/session')->isLoggedIn()){
    		$_customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
	    	}
	    	/* get category details by Id */
	    	$categoryState = Mage::helper("bytescategory")->getCategoryById($_posts["category"]);
	    	$plans = Mage::getModel("md_membership/subscribers")->getCollection();
			$plans->addFieldToFilter("customer_id",$_customerId);
			$plans->addFieldToFilter("store_id",$_storeId);
			$plans->addFieldToFilter("status",1);
			$_planId = 0;
			if($plans->count() > 0){
				$_planId = $plans->getFirstItem()->getPlanId();
			}
            if($_customerId != 0 && $_planId != 0){
				$stateData = [
					"category_id" => $_posts["category"],
					"customer_id" => $_customerId,
					"category_name" => $categoryState["category_name"],
					"store_id" => $_storeId,
					"plan_id" => $_planId
				];
				try{
					$state = Mage::getModel("bytescustomer/customer");
                    $state->setData($stateData);
                    $state->save();
                    Mage::getSingleton('core/session')->addSuccess(Mage::helper('bytescustomer')->__('Customer saving state'));
                    $this->_redirect('customer/account');
                }
                catch(Exception $e){
                    Mage::getSingleton('core/session')->addError(Mage::helper('bytescustomer')->__('Customer saving error %s',$e->getMessage()));
                    $this->_redirect('bytescustomer/subscribe/addstate');
                }
			}	
    	}
        
        Mage::getSingleton('core/session')->addError(Mage::helper('bytescustomer')->__('Please select proper state.'));
        $this->_redirect('bytescustomer/subscribe/addstate');   
    }

    public function checkAction(){
    	$products = Mage::getModel("catalog/product")->getCollection()->addFieldToFilter("type_id","downloadable");
    	foreach ($products as $key => $product) {

    		$_myprodlinks = Mage::getModel('downloadable/link');
        	$_myLinksCollections = $_myprodlinks->getCollection()->addTitleToResult()
                                ->addProductToFilter($product->getId());
            if($_myLinksCollections->count() > 0){
    			echo $product->getId()." - ".$product->getTypeId()."<br>";
            	echo "Links - ".$_myLinksCollections->count()."<br>";
            }
    	}
		exit;
    }	
}