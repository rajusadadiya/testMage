<?php

class Bytes_Customer_Model_MDObserver extends MD_Membership_Model_Observer{
	
	public function addLinkToNavigation(Varien_Event_Observer $observer)
    {
        $menu = $observer->getEvent()->getMenu();
        $tree = $menu->getTree();
        $helper = Mage::helper('md_membership');
        if($helper->displayInTopNavigation()){

        	if((Mage::getSingleton('customer/session')->isLoggedIn() && Mage::getSingleton('customer/session')->getCustomer()->getWebsiteId() == Mage::app()->getWebsite()->getId()) ||	 !Mage::getSingleton('customer/session')->isLoggedIn()){
	            $node = new Varien_Data_Tree_Node([
	            	'name'   => $helper->getMembershipLinkTitle(),
	                'id'     => 'membership-nav',
	                'url'    => $helper->getMembershipUrl(), // point somewhere
	        		],'id', $tree, $menu);
	            $menu->addChild($node);
	        }
        }
    }
}