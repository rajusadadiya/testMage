<?php /* MD_Membership_Block_Adminhtml_Subscribers_Grid */
class Bytes_Customer_Block_Adminhtml_Customer_Subscriber_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_stores = array();
    protected $_helper = null;
    protected $_mediaPath = null;
    public function __construct()
    {
        parent::__construct();
        $this->_helper = Mage::helper('md_membership');
        $this->_stores = Mage::getModel('core/store')->getCollection()->toOptionHash();
        $this->_mediaPath = str_replace('index.php/','',Mage::getUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA));
        $this->setId('membershipPlansGrid');
        $this->setUseAjax(false);
        $this->setDefaultSort('subscriber_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    protected function _prepareCollection() {
        $collection = Mage::getModel('md_membership/subscribers')->getCollection();
        $collection->getSelect()
                ->joinLeft(
                        array('p1'=>Mage::getSingleton('core/resource')->getTableName('md_membership/plans')),
                        'p1.plan_id=main_table.plan_id',
                        array('p1.title')
                    );
        $collection->getSelect()
                ->joinLeft(
                        array(
                        'state'=>Mage::getSingleton('core/resource')->getTableName('bytescustomer/customer')),
                        'state.customer_id=main_table.customer_id',
                        array('state.category_name')
                );
        $collection->getSelect()->group('main_table.subscriber_id');        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    public function _prepareColumns() {
        $this->addColumn('subscriber_id', array(
            'header' => $this->_helper->__('Id'),
            'index' => 'subscriber_id',
            'width'     => '15',
            'type'  => 'number',
        ));
        $this->addColumn('profile_id', array(
            'header' => $this->_helper->__('Profile Id'),
            'index' => 'profile_id',
            'type'  => 'text',
        ));
        
        $this->addColumn('store_id',array(
           'header'=> $this->_helper->__('Store'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_all'     => false,
                'store_view'    => true,
                'sortable'      => false,
        ));
        $this->addColumn('title', array(
            'header' => $this->_helper->__('Plan'),
            'index' => 'title',
        ));

        $this->addColumn('category_name', array(
            'header'    => Mage::helper('bytescustomer')->__('Subscription Category'),
            'index'     => 'category_name',
        ));
        
        $this->addColumn('name', array(
            'header' => $this->_helper->__('Name'),
            'index' => 'name',
        ));
        
        $this->addColumn('email', array(
            'header'    => $this->_helper->__('Email'),
            'width'     => '150',
            'index'     => 'email'
        ));
        
        $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

        /*$this->addColumn('group', array(
            'header'    =>  $this->_helper->__('Group'),
            'width'     =>  '100',
            'index'     =>  'group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
        ));*/
        
        $this->addColumn('country_id', array(
            'header'    => $this->_helper->__('Country'),
            'width'     => '100',
            'type'      => 'country',
            'index'     => 'country_id',
        ));

        $this->addColumn('region', array(
            'header'    => $this->_helper->__('State/Province'),
            'width'     => '100',
            'index'     => 'region',
        ));
        
        $this->addColumn('payment_method', array(
            'header'    => $this->_helper->__('Payment Method'),
            'width'     => '100',
            'index'     => 'payment_method',
            'type'      =>  'options',
            'options'   =>  $this->_helper->getPaymentArray(),
        ));
        
        $this->addColumn('status',array(
           'header'=>$this->_helper->__('Status'),
            'index'=>'status',
        'type'=>'options',
            'filter_index'=> '`main_table`.`status`',
        'options'=> $this->_helper->getStatusLabels(),
            'frame_callback' => array($this, 'decorateStatus')
        ));
        
        $this->addColumn('profile_start_date',array(
           'header'=>$this->_helper->__('Subscription Date'),
            'index'=>'profile_start_date',
            'type'=>'date',
            'gmtoffset' => true,
                        'width'=> '50px'
        ));
        return parent::_prepareColumns();
    }
    
    public function decorateStores($value, $row, $column, $isExport)
    {
        
        $stores = explode(",",$row->getStoreIds());
        
        $this->_stores[0] = $this->_helper->__('All Store Views');
        $string = array();
        
        foreach($stores as $store){
            $string[] = $this->_stores[$store];
        }
        
        return implode('<br />',$string);
    }
    
    public function decorateStatus($value, $row, $column, $isExport)
    {
        $class = '';
        $class = '';
        switch($row->getStatus()){
            case MD_Membership_Model_Subscribers::SUBSCRIPTION_STATUS_ACTIVE:
                    $class .= 'grid-severity-notice';
                    break;
            case MD_Membership_Model_Subscribers::SUBSCRIPTION_STATUS_EXPIRED:
                    $class .= 'grid-severity-critical';
                    break;
            case MD_Membership_Model_Subscribers::SUBSCRIPTION_STATUS_TERMINATED:
                    $class .= 'grid-severity-major';
                    break;
            default:
                    $class .= 'grid-severity-minor';
                    break;
        }
            $cell = '<span class="'.$class.'"><span>'.$value.'</span></span>';
        return $cell;
    }
    
    public function decorateAmount($value, $row, $column, $isExport)
    {
        $string = '';
        $store = $this->_getStore();
        if($value){
            $string .= '<strong>'.Mage::helper('core')->currencyByStore($value,$store,true,false).'</strong>';
        }
        return $string;
    }
    
    public function decorateImage($value, $row, $column, $isExport)
    {
        $string = '';
        if($value){
            $string .= '<img src="'.$this->_mediaPath.'md/membership/plans/'.$value.'" height="50px" width="50px" />';
        }
        return $string;
    }
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('subscriber_id');
        $this->getMassactionBlock()->setFormFieldName('subscribers');
        
        $this->getMassactionBlock()->addItem('status',array(
            'label'=>$this->_helper->__('Change Status'),
            'url'=>$this->getUrl('*/*/massSubscribersStatus'),
            'additional'=>array(
                'visibility'=>array(
                    'name'=>'status',
                    'type'=>'select',
                    'class'=>'required-entry',
                    'label'=>$this->_helper->__('Status'),
                    'values'=>array(
                                            4 => $this->_helper->__('Cancel Profile'),
                                            3 => $this->_helper->__('Suspend Profile'),
                                            1 => $this->_helper->__('Reactivate Profile'),
                                        ),
                )
            )
        ));
         return parent::_prepareMassaction();
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/mdmembership_subscribers/view',array('id'=>$row->getId()));
    }
    
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
}