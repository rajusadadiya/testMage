<?php
namespace Bytes\ProductImportExport\Model;

class ProcessData extends \Magento\Framework\Model\AbstractModel
{ 
    public $_helper;

    public function __construct(\Bytes\ProductImportExport\Helper\Data $helper){
        $this->_helper = $helper;
    }

    public function process($csvData){

        $allAttribute = $this->_helper->getAllAttribute();
        $storeAttribute = $this->_helper->getStoreAttribute();
        $upsellAttribute = $this->_helper->getUpsellLinkAttribute();
        $crosssellAttribute = $this->_helper->getCrosssellAttribute();
        $relatedAttribute = $this->_helper->getRelatedAttribute();
        $associatedAttribute = $this->_helper->getAssociatedAttribute();
        $mediaAttribute = $this->_helper->getMediaAttribute();
        $attr = $csvData[0];
        $customAttribute = count($this->_helper->getCustomAttribute($attr)) > 0 ? $this->_helper->getCustomAttribute($attr) : [];
        
        $allAttribute = array_merge($allAttribute,$customAttribute);
        $storeAttribute = array_merge($storeAttribute,$customAttribute);
        unset($csvData[0]);


        $productItems = [];
        foreach ($csvData as $keyIndex => $item){
            $productItem = [];                        
            foreach ($item as $key => $productData){
                $productItem[$attr[$key]] = $productData;                    
            }
            $productItems[] = $productItem;
        }
        
        $collection = [];
        $sku ='';
        $productItem = [];
        foreach ($productItems as $itemkey => $product){
            if($product["sku"] != '' && $itemkey != 0){
                $collection[] = $productItem;
            }
            if($product["sku"] != ''){
                $sku = $product["sku"];
                $productItem = [];
            }
            $store = "_";
            foreach ($allAttribute as $attribute) {                    
                if(isset($product["_store"]) && $product["_store"] != '' && in_array($attribute, $storeAttribute)){
                    if(isset($product[$attribute]) && $product[$attribute] != ''){
                        $productItem[$sku][$product["_store"]][$attribute][] = $product[$attribute];                             
                    }
                }
                else{
                    if(isset($product[$attribute]) && $product[$attribute] != '' && $attribute != "_store"){
                        $productItem[$sku][$store][$attribute][] = $product[$attribute];
                    }
                }                    
            }
            $relatedItem = [];
            foreach ($relatedAttribute as $relatedAttr) {
                if(isset($product[$relatedAttr]) && $product[$relatedAttr] != ''){
                    $relatedItem[$relatedAttr] = $product[$relatedAttr];   
                }
            }
            if(count($relatedItem) > 0){
                $productItem[$sku][$store]["related"][] = $relatedItem;                  
            }

            $upsellItem = [];
            foreach ($upsellAttribute as $upsellAttr) {
                if(isset($product[$upsellAttr]) && $product[$upsellAttr] != ''){
                    $upsellItem[$upsellAttr] = $product[$upsellAttr];
                }
            }
            if(count($upsellItem) > 0){
                $productItem[$sku][$store]["upsell"][] = $upsellItem;                  
            }

            $crosssellItem = [];
            foreach ($crosssellAttribute as $crosssellAttr) {
                if(isset($product[$crosssellAttr]) && $product[$crosssellAttr] != ''){
                    $crosssellItem[$crosssellAttr] = $product[$crosssellAttr];   
                }
            }
            if(count($crosssellItem) > 0){
                $productItem[$sku][$store]["crosssell"][] = $crosssellItem;               
            }

            $associatedItem = [];
            foreach ($associatedAttribute as $associatedAttr) {
                if(isset($product[$associatedAttr]) && $product[$associatedAttr] != ''){
                    $associatedItem[$associatedAttr] = $product[$associatedAttr];   
                }
            }
            if(count($associatedItem) > 0){
                $productItem[$sku][$store]["associated"][] = $associatedItem;
            }
            
            $media = $this->_helper->getMedia($product);
            if(is_array($media)){
                $productItem[$sku][$store]["media"][] = $media;
            }
            $customOption = $this->_helper->getCustomOption($product);
            if(is_array($customOption)){
                $productItem[$sku][$store]["custom_option"][] = $this->_helper->getCustomOption($product);
            }            
        }

        foreach ($collection as $productItem) {
            $this->createProductData($productItem);
        }
    }


    public function createProductData($productArray){
        $productEntity = [];
        foreach($productArray as $sku => $stores){
            $productEntity[$sku] = [];
            foreach ($stores as $storecode => $product){
                foreach ($product as $attribute => $data){
                    if(is_array($data) && count($data) > 1){
                       $productEntity[$sku][$storecode][$attribute] = $data; 
                    }
                    else{
                       $productEntity[$sku][$storecode][$attribute] = $data[0]; 
                    }
                }
            }
        }
        echo "<pre>";
            print_r($productEntity);
        echo "</pre>";
    }
}