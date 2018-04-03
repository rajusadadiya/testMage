<?php
class Bytes_Customer_Block_Subscribe extends Mage_Core_Block_Template
{
	const PLAN_STATUS = 1;
	public $_customerId;
	public $_storeId;
	public $_planId;

    public function __construct(){

    	$this->_storeId = Mage::app()->getStore()->getId();
    	if(Mage::getSingleton('customer/session')->isLoggedIn()){
    		$this->_customerId = $this->getCustomer()->getId();
    	}
    	if($this->_customerId != ''){
    		$this->_planId = $this->getPlanId();
    	}
    	//echo "hello"; exit;
    }

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function getCustomer(){
    	return Mage::getSingleton('customer/session')->getCustomer();
    }

    public function getPlanId(){
    	/* get subscribe plan */
		$plans = Mage::getModel("md_membership/subscribers")->getCollection();
		$plans->addFieldToFilter("customer_id",$this->_customerId);
		$plans->addFieldToFilter("store_id",$this->_storeId);
		$plans->addFieldToFilter("status",self::PLAN_STATUS);
		//echo $plans->getSelect();
		if($plans->count() > 0){
			return $plans->getFirstItem()->getPlanId();
		}
		return false;
    }

}