<?php
namespace Bytes\ProductImportExport\Controller\Adminhtml\Import;

class Index extends \Bytes\ProductImportExport\Controller\Adminhtml\Import {

    public function execute() {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Bytes_Core::base');
        return $resultPage;
    }
}
