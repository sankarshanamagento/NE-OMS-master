<?php
namespace Netenrich\Services\Controller\Adminhtml\Index;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		 $deletearray=array();
		 $undelete=array();
		 $ids = $this->getRequest()->getParam('api_id');
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select Services(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $product = $this->_objectManager->get('Netenrich\Services\Model\Services')->load($id);
					
					$categorydata=array("serviceId"=>$product->getServiceuniqueid(),
								"partNumber"=>$product->getPartnumber(),
								"serviceName"=>$product->getServicename(),
								"storeId"=>$product->getStore(),
								"categoryId"=>$product->getCategoryid(),
								"subCategoryId"=>$product->getSubcategoryid(),
								"packageId"=>$product->getServicePackage(),
								"serviceLevelId"=>$product->getServicelevel(),
								"serviceType"=>$product->getServiceType(),
								"action" => "delete");
								
					 $content = json_encode($categorydata, JSON_NUMERIC_CHECK);
					$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
					$imageFile = $imageHelper->Servicesapis($content);
					
					if($imageFile === "success")
					{
					   $deletearray[]=$id;
					   $product->delete();
					}
					else{
						 $undelete[]=$id;
					}
					
				}
				$sucdel=implode(",",$deletearray);
				$unsucdel=implode(",",$undelete);
				if(isset($sucdel))
					 {						 
					    $this->messageManager->addSuccess(__('Following Part Numbers '.$sucdel.'  Delete Sync with Web Hook.'));
					 }
					 
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($deletearray))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
		 $this->_redirect('*/*/');
    }
}
