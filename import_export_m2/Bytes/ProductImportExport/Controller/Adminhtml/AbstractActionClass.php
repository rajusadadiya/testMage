<?php

namespace Bytes\ProductImportExport\Controller\Adminhtml;

abstract class AbstractActionClass extends \Magento\Backend\App\Action {
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * A factory that knows how to create a "page" result
     * Requires an instance of controller action in order to impose page type,
     * which is by convention is determined from the controller action class.
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Registry object.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * dir reader Object.
     *
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_directoryReader;

    /**
     * Csv object.
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $_csv;
    
    /**
     * Helper object.
     *
     * @var \Bytes\ProductImportExport\Helper\Data
     */
    protected $_moduleHelper;

    /** 
     * Process  Csv Data
     *
     * @var \Bytes\ProductImportExport\Model\ProcessData
     */
    public $_processData;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Module\Dir\Reader $directoryReader
     * @param \Magento\Framework\File\Csv $csv
     * @param \Bytes\ProductImportExport\Hepler\Data $helper
     * @param \Bytes\ProductImportExport\Model\ProcessData $processData
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,                        
        \Magento\Framework\Registry $coreRegistry,            
        \Magento\Framework\View\Result\PageFactory $resultPageFactory, 
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory, 
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Module\Dir\Reader $directoryReader,
        \Magento\Framework\File\Csv $csv,
        \Bytes\ProductImportExport\Helper\Data $helper,
        \Bytes\ProductImportExport\Model\ProcessData $processData
    ) {

        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_storeManager = $storeManager;
        $this->_directoryReader = $directoryReader;
        $this->_csv = $csv;
        $this->_moduleHelper = $helper;
        $this->_processData = $processData;
    }

    protected function _getBackResultRedirect(\Magento\Framework\Controller\Result\Redirect $resultRedirect, $paramCrudId = null) {
        switch ($this->getRequest()->getParam('back')) {
            case 'edit':
                $resultRedirect->setPath('*/*/edit', [
                    static::PARAM_CRUD_ID => $paramCrudId,
                    '_current' => true,
                    ]);
                break;
            case 'new':
                $resultRedirect->setPath('*/*/new', ['_current' => true]);
                break;
            default:
                $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect;
    }

    protected function setIsDefault($object, $status) {
        $object->setIsDefault($status);
        try {
            $object->save();
        } catch (Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }

}
