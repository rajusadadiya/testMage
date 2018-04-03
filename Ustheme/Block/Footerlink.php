<?php
class Bytes_Ustheme_Block_Footerlink extends Mage_Core_Block_Template
{
    public $_links;

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }

    public function addLink($title,$url,$cssClass){
        if($this->_links == null){
            $this->_links = [];
        }
        $this->_links[] = ["label"=> $title,"url"=>$this->getUrl($url),"css_class"=>$cssClass];
    }

    public function getLinks(){
        return $this->_links;
    }
}
