<?php

namespace Netenrich\Webhook\Controller\Adminhtml\Index;



class Payload extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $_collectionFactory;
    protected $_productCollectionFactory;
	protected $directory_list;
    protected $fileFactory;
	protected $mediaDirectory;
    /**
     * @param \Magento\Framework\App\Action\Context           $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, 
	\Magento\Framework\View\Result\PageFactory $resultPageFactory, 
	\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
	\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
	\Netenrich\Webhook\Model\WebhookFactory $collectionFactory,
	
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->resultRawFactory = $resultRawFactory;
		$this->_productCollectionFactory = $productCollectionFactory; 
		$this->_collectionFactory=$collectionFactory;
		
	
        parent::__construct($context,$data);
    }

    public function execute()
    {
		$apiid=$this->getRequest()->getParam('org_id');
		$collection = $this->_collectionFactory->create()->load($apiid);
        
		echo $data_string=$collection->getPayload();
		
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$directory = $objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
       $uploadtPath  =  $directory->getPath('upload');
		$myfile = fopen($uploadtPath."/newfile.txt", "w") or die("Unable to open file!");
		//fwrite($myfile, $data_string);
		
		fclose($myfile);

$file = $uploadtPath."/newfile.txt";

if(!$file){ // file does not exist
    die('file not found');
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=payloadfile.txt");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}
		
		exit;
		$this->messageManager->addSuccess(__('The Catalog Sny Data has been saved .')); 
		$this->_redirect('*/*/');
        return;
		//print_r($selectlist);
     	
    }
}
