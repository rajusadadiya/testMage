<?php
namespace Bytes\ProductImportExport\Controller\Adminhtml\Import;

class Save extends \Bytes\ProductImportExport\Controller\Adminhtml\Import {
    
    const FILED_DATA_FILE_NAME = "default_fields.csv";    

    public function execute() { 
        $defaultAttr = $this->getDefaultFields();        
        $requestedFile = $this->getRequest()->getFiles("csvfile");        
        if($requestedFile && $requestedFile["type"] == 'text/csv'){        
          try{
            $csvData = $this->_csv->getData($requestedFile['tmp_name']);
            $products = $this->_processData->process($csvData);
          }
          catch(Exception $e){
            throw new \Magento\Framework\Exception\LocalizedException(__('Error :%1',$e->getMessage()));
          }
        }
        else{
          throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }      
  	/*$resultPage = $this->_resultPageFactory->create();
        return $resultPage;*/
    } 

    public function getDefaultFields(){
        if(file_exists($this->getModuleViewPath())){
            $data = $this->_csv->getData($this->getModuleViewPath());
            $fields = [];
            foreach ($data as $fieldItem){
                $fields[] = $fieldItem[0];
            }
            return $fields;
        }
        else{
            throw new \Magento\Framework\Exception\LocalizedException(__('Data File not found.'));
        }
    }

    public function getModuleViewPath()
    {
          $viewDir = $this->_directoryReader->getModuleDir(
              \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
              'Bytes_ProductImportExport'
          );
          return $viewDir . '/data/'.self::FILED_DATA_FILE_NAME;
    }
}
