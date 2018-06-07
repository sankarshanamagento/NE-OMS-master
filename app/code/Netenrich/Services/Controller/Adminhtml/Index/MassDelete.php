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
		 $ids = $this->getRequest()->getParam('part_id');
		 $customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load(1);
         $orgid= $customerObj->getDivauserid();
		if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select Services(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $product = $this->_objectManager->get('Netenrich\Services\Model\Services')->load($id);
					
					$categorydata=array("serviceId"=>$id,
								"partNumber"=>$product->getPartnumber(),
								"serviceName"=>$product->getServicename(),
								"storeId"=>$product->getStore(),
								"categoryId"=>$product->getCategoryid(),
								"subCategoryId"=>$product->getSubcategoryid(),
								"packageId"=>$product->getServicePackage(),
								"serviceLevelId"=>$product->getServicelevel(),
								"serviceTypeId"=>$product->getServiceType(),
								"orgId"=>$orgid,
								"action" => "delete");
								
					$content = json_encode($categorydata, JSON_NUMERIC_CHECK);
					$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
					$imageFile = $imageHelper->Servicesapis($content);
					
					if($imageFile['status'] === "success")
					{
					   $webhook = $this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
					   $webhook->setJobid($imageFile['jobid']);
			           $webhook->setEntityName('Services');
			           $webhook->setEntityId($id);
			           $webhook->setPayload($content);
			           $webhook->setStatus('inprogress');
					   $webhook->setCreateaction('Delete');
			           $webhook->save();
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
					    $this->messageManager->addSuccess(__(' Delete Sync with Web Hook inprogress.'));
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
