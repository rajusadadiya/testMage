<?php
namespace Bytes\ProductImportExport\Block\Adminhtml\Import\Edit;
 
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
 
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('products');
        $this->setTitle(__('Import Product'));
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 
                'action' => $this->getData('action'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
                ]
            ]
        );
 
        $form->setHtmlIdPrefix('btimport_');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Product Import'), 'class' => 'fieldset-wide']
        );
 
        
 
        $fieldset->addField(
            'csvfile',
            'file',
            ['name' => 'csvfile', 'label' => __('CSV File'), 'title' => __('CSV File'),'required' => true, 'class' => 'required-file']
        );

        /*$user = unserialize($model->getUser());
        $model->setUsername($user["display_name"]);
        $date = date_format(date_create($model->getCreatedAt()),"d M Y");
        $date = $model->getCreatedAt()->format('d M Y-m-d H:i:s');
        $model->setCreatedAt($date);
        $form->setValues($model->getData());*/
        $form->setUseContainer(true);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
}