<?php

class Bytes_Ustheme_Model_Helplinks extends Mage_Core_Model_Abstract
{
	public $_links;

    public function _construct()
    {
        parent::_construct();
        $this->_init('ustheme/helplinks');
    }

    public function getLinks(){
    	if(!is_array($this->_links)){
    		$this->_links = array();
    	}

    	$this->_links = [
    		"Customer Service" => [
                ["label"=>"Customer Service Home","description"=>"Example link description","url"=>"customer_service_home"],
                ["label"=>"Frequently Asked","description"=>"Example link description","url"=>"frequently_asked"],
                ["label"=>"Help Before you Order","description"=>"Example link description","url"=>"help_before_you_order"],
                ["label"=>"Help After you Order","description"=>"Example link description","url"=>"help_after_you_order"],
                ["label"=>"Call Back Request","description"=>"Example link description","url"=>"call_back_request"],
                ["label"=>"Suggestions","description"=>"Example link description","url"=>"suggestions"],
                ["label"=>"Join Mailing List","description"=>"Example link description","url"=>"join_mailing_list"]
    		] 		
    	];
    	return $this->_links;
    }
}