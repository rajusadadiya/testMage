<?php 
class Bytes_Ustheme_Block_Homeslider extends Mage_Core_Block_Template 
{
    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getBlockData() {
        if (!$this->hasData('block_data')) {
            $bannerslider_id = $this->getBannersliderId();
            if ($bannerslider_id) {
                $block_data = Mage::getModel('bannerslider/bannerslider')->load($bannerslider_id);
            } else {
                $block_data = $this->getSliderData();
            }
            $category = Mage::registry('current_category');
            $cateIds = array();
            if ($category) {
                $cateIds = $category->getPathIds();
                $categoryIds = $block_data->getCategoryIds();
                $categoryIds = explode(",", $categoryIds);
                if (strncasecmp('category', $block_data->getPosition(), 8) == 0) {
                    if (count(array_intersect($cateIds, $categoryIds)) == 0) {
                        $block_data = null;
                        return null;
                    }
                }
            }
            $today=Mage::getModel('core/date')->gmtDate();
            $randomise = $block_data->getSortType() ? false : true;
            $banners = Mage::getModel('bannerslider/banner')->getCollection()
                    ->addFieldToFilter('bannerslider_id', $block_data->getId())
                    ->addFieldToFilter('status', 0)                   
                    ->addFieldToFilter('start_time', array('lteq' => $today))
                    ->addFieldToFilter('end_time', array('gteq' => $today))
                   ->setOrder('order_banner', "ASC");
           $banners->getSelect()->columns(array($randomise ? 'Rand() as order' : ''));

            
            $result = array();
            $result['block'] = $block_data;
            $result['banners'] = array();
            foreach ($banners as $banner){
                $result['banners'][] = $banner->getData();
            }                      
            $this->setData('block_data', $result);
        }
        return $this->getData('block_data');
    }

    public function getBannerImage($imageName) {
        return Mage::helper('bannerslider')->getBannerImage($imageName);
    }
}