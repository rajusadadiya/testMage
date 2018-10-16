<?php
namespace Bytes\ProductImportExport\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper{
    
    /*public function __construct() {
        
    }*/

    public function getAllAttribute(){
        return [
            "_attribute_set",
            "sku",
            "weight",
            "price",
            "special_price",
            "msrp",
            "_tier_price_website",
            "_tier_price_customer_group",
            "_tier_price_qty",
            "_tier_price_price",
            "_group_price_website",
            "_group_price_customer_group",
            "_group_price_price",
            "is_recurring",
            "gift_message_available",
            "manage_stock",
            "qty",
            "min_qty",
            "min_sale_qty",
            "max_sale_qty",
            "is_qty_decimal",
            "backorders",
            "notify_stock_qty",
            "enable_qty_increments",
            "qty_increments",
            "is_decimal_divided",
            "is_in_stock",
            "use_config_min_qty",
            "use_config_backorders",
            "use_config_min_sale_qty",
            "use_config_max_sale_qty",
            "use_config_notify_stock_qty",
            "use_config_manage_stock",
            "stock_status_changed_auto",
            "use_config_qty_increments",
            "use_config_enable_qty_inc",
            "created_at",
            "updated_at",
            "_type",
            "_category",
            "_root_category",
            "_product_websites",
            "image",
            "image_label",
            "media_gallery",
            "minimal_price",
            "msrp_display_actual_price_type",
            "msrp_enabled",
            "small_image",
            "small_image_label",
            "thumbnail",
            "thumbnail_label",
            "news_from_date",
            "news_to_date",
            "status",
            "country_of_manufacture",
            "manufacture",
            "special_from_date",
            "special_to_date",
            "msrp_enabled",
            "msrp_display_actual_price_type",
            "tax_class_id",
            "name",
            "description",
            "short_description",
            "url_key",
            "url_path",
            "visibility",
            "meta_description",
            "meta_keyword",
            "meta_title",
            "custom_design",
            "custom_design_from",
            "custom_design_to",
            "custom_layout_update",
            "page_layout",
            "options_container",
            "color",
            "cost",
            "gallery",
            "has_options",
            "manufacturer",
            "required_options",
        ];
    }

    public function getCustomOptionAttribute(){
        return [
            "_custom_option_store",
            "_custom_option_type",
            "_custom_option_title",
            "_custom_option_is_required",
            "_custom_option_price",
            "_custom_option_sku",
            "_custom_option_max_characters",
            "_custom_option_sort_order",
            "_custom_option_row_title",
            "_custom_option_row_price",
            "_custom_option_row_sku",
            "_custom_option_row_sort"
        ];
    }
    
    public function isCustomOptionEmpty($item){
        $flag = true;
        foreach ($this->getCustomOptionAttribute() as $attr){
            if(!empty($item[$attr])){
                $flag = false;
            }
        }
        return $flag;
    }
    
    public function getMediaAttribute(){
        return [
            "_media_attribute_id",
            "_media_image",
            "_media_lable",
            "_media_position",
            "_media_is_disabled"            
        ];
    }
    
    public function isMediaEmpty($item){
        $flag = true;
        foreach ($this->getMediaAttribute() as $attr){
            if(!empty($item[$attr])){
                $flag = false;
            }
        }
        return $flag;
    }

    public function getCustomOption($item){
        if(!$this->isCustomOptionEmpty($item)){
            $option = [];
            foreach ($this->getCustomOptionAttribute() as $value) {
                //var_dump($item[$value]);
                if(isset($item[$value])){
                    $option[$value] = $item[$value];                
                }
            }
            return $option;
        }
        return false;
    }
    
    public function getMedia($item){
        if(!$this->isMediaEmpty($item)){
            $media = [];
            foreach ($this->getMediaAttribute() as $value) {
                if(isset($item[$value])){
                    $media[$value] = $item[$value];                
                }
            }
            return $media;
        }
        return false;
    }

    public function getStoreAttribute(){
        return [
            "name",
            "description",
            "short_description",
            "url_key",
            "url_path",
            "visibility",
            "meta_description",
            "meta_keyword",
            "meta_title",
            "custom_design",
            "custom_design_from",
            "custom_design_to",
            "custom_layout_update",
            "page_layout",
            "options_container"
        ];
    }

    public function getUpsellLinkAttribute(){
        return ["_links_upsell_sku","_links_upsell_position"];

    }

    public function getCrosssellAttribute(){
        return ["_links_crosssell_sku","_links_crosssell_position"];

    }

    public function getRelatedAttribute(){
        return ["_links_related_sku","_links_related_position"];

    }

    public function getAssociatedAttribute(){
        return ["_associated_sku","_associated_default_qty","_associated_position"];
    }

    public function getCustomAttribute($attributes){
        $_customAttributes = [];
        foreach ($attributes as $attr) {
            if(!in_array($attr, $this->getAllAttribute()) && !in_array($attr, $this->getUpsellLinkAttribute()) && !in_array($attr, $this->getCrosssellAttribute()) && !in_array($attr, $this->getRelatedAttribute()) && !in_array($attr, $this->getAssociatedAttribute()) && !in_array($attr, $this->getCustomOptionAttribute()) && !in_array($attr, $this->getMediaAttribute())){
                if($attr != "_store"){
                    $_customAttributes[] = $attr;   
                }                
            }
        }
        return $_customAttributes;
    }
}