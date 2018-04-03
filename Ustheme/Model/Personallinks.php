<?php

class Bytes_Ustheme_Model_Personallinks extends Mage_Core_Model_Abstract
{
	public $_links;

    public function _construct()
    {
        parent::_construct();
        $this->_init('ustheme/personallinks');
    }

    public function getLinks(){
    	if(!is_array($this->_links)){
    		$this->_links = array();
    	}

    	$this->_links = [
    		"Estate Planning" => [
    			"Dave Ramsey Specials"=>'dave_ramsey_specials',
    			"Wills"=>'wills',
    			"Living Trust"=>'living_trust',
    			"Living Will"=>'living_will',
    			"Advanced Directive"=>'advanced_directive',
    			"Power of Attorney"=>'power_of_attorney'
    		],
    		"Real Estate" => [
    			"Home Sales" => 'home_sales',
    			"Landlord Tenant" => 'landlord_tenant',
    			"Leases" => 'leases',
    			"Agreements" => 'agreements'
    		],
    		"Bankruptcy" => [
    			"Chapter 7"=>'chapter7',
    			"Chapter 13"=>'chapter13'
    		],
    		"Divorce" => [
    			"No-Fault Divorce"=>'no-fault_Divorce',
    			"Separation Agreements"=>'separation_agreements',
    			"Community Property"=>'community_property'
    		],
    		"More Areas" => [
    			"Affidavits"=>'affidavits',
    			"Bill of Sale"=>'bill_of_sale',
    			"Cohabitation"=>'cohabitation',
    			"Contractors"=>'contractors',
    			"Contract for Deed"=>'contract_for_deed',
    			"Letter Templates"=>'letter_templates',
    			"Marriage"=>'marriage',
    			"Name Change"=>'name_change',
    			"Promissory Notes"=>'promissory_notes',
    			"Premarital Agreements"=>'premarital_agreements',
    			"Waiver Forms"=>'waiver_forms',
    			"Forms A-Z"=>'forms_a-z'
    		]
    	];
    	return $this->_links;
    }
}