<?php

namespace Netenrich\Services\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Netenrich_Services::services_delete');
    }

    /**
     * Delete action
     *
     * @return void
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('part_id');
		/** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
		$customerObj = $this->_objectManager->create('Magento\Customer\Model\Customer')->load(1);
         $orgid= $customerObj->getDivauserid();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create('Netenrich\Services\Model\Services');
                $model->load($id);
                $title = $model->getTitle();
				$categorydata=array("serviceId"=>$id,
								"partNumber"=>$model->getPartnumber(),
								"serviceName"=>$model->getServicename(),
								"storeId"=>$model->getStore(),
								"categoryId"=>$model->getCategoryid(),
								"subCategoryId"=>$model->getSubcategoryid(),
								"packageId"=>$model->getServicePackage(),
								"serviceLevelId"=>$model->getServicelevel(),
								"serviceTypeId"=>$model->getServiceType(),
								"orgId"=>$orgid,
								"action" => "delete");
								
					$content = json_encode($categorydata, JSON_NUMERIC_CHECK);
					$imageHelper = $this->_objectManager->get('Netenrich\Services\Helper\Data');
					$imageFile = $imageHelper->Servicesapis($content);
					if($imageFile['status'] === "success")
					{
					 $webhook = $this->_objectManager->create('Netenrich\Webhook\Model\Webhook');
                      $webhook->setJobid($imageFile['jobid']);
			$webhook->setEntityName('Service');
			$webhook->setEntityId($model->getServicename());
			$webhook->setPayload($content);
			$webhook->setStatus('inprogress');
			$webhook->setCreateaction('Delete');
			$webhook->save();					 
                     $model->delete();
					 $this->messageManager->addSuccess(__("Delete Record Sync with Web Hook "));
					 $this->messageManager->addSuccess(__("Deleted Record success"));
					}else
					{
					$this->messageManager->addError(__("please try again  "));
					 	
					}
                // display success message
                
                // go to grid
                //return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['page_id' => $id]);
            }
        }
        // display error message
        //$this->messageManager->addError(__('We can\'t find a data to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
