<?php

class Bytes_Ustheme_Model_Bussinesslinks extends Mage_Core_Model_Abstract
{
	public $_links;

    public function _construct()
    {
        parent::_construct();
        $this->_init('ustheme/bussinesslinks');
    }

    public function getLinks(){
    	if(!is_array($this->_links)){
    		$this->_links = array();
    	}

    	$this->_links = [
    		"Starting My Business" => [
    			"Pre-Incorporation"=>'pre-incorporation',
    			"Stock Certficates"=>'stock_certficates',
    			"Start an LLC"=>'start_an_llc',
    			"Incorporate (S or C-Corps)"=>'incorporate_s_or_c-corps',
    			"Articles of Incorporation"=>'articles_of_incorporation',
    			"Shareholders Agreements"=>'shareholders_agreements',
    			"LLC Operating Agreements"=>'llc_operating_agreements'
    		],
    		"Managing My Business" => [
    			"Employment Agreements" => 'home_sales',
    			"Independent Contractors" => 'landlord_tenant',
    			"Confidentiality Agreements" => 'leases',
    			"Corporate Records" => 'agreements',
    			"Annual Minutes" => 'agreements',
    			"Corporate Voting" => 'agreements',
    			"Board of Directors" => 'agreements',
    			"Bylaws & Resolutions" => 'agreements'
    		],
    		"Running My Business" => [
    			"Corporate Amendments"=>'chapter7',
    			"Dissolution"=>'chapter13',
    			"Corporate Name Change"=>'chapter13',
    			"DBA Registration"=>'chapter13',
    			"Contracts"=>'chapter13',
    			"Buy/Sell Agreements"=>'chapter13',
    			"Sale of Business"=>'chapter13'
    		],
    		"More Business Forms" => [
    			"Contractors"=>'no-fault_Divorce',
    			"Construction Liens"=>'separation_agreements',
    			"Real Estate"=>'community_property',
    			"Landlord Tenant"=>'community_property',
    			"Contract for Deed"=>'community_property',
    			"Public Corporations"=>'community_property',
    			"View More!"=>'community_property'
    		]    		
    	];
    	return $this->_links;
    }
}