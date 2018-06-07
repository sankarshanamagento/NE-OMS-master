<?php

namespace Netenrich\Webhook\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;


class Save extends \Magento\Backend\App\Action
{
    /**
     * @var PostDataProcessor
     */
    protected $dataProcessor;

    /**
     * @param Action\Context $context
     * @param PostDataProcessor $dataProcessor
     */
    public function __construct(Action\Context $context, PostDataProcessor $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Netenrich_Webhook::save');
    }

    /**
     * Save action
     *
     * @return void
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            $model = $this->_objectManager->create('Netenrich\Company\Model\Company');
			$productObj = $this->_objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
			
            $id = $this->getRequest()->getParam('api_id');
            if ($id) {
                $model->load($id);
            }
            
            // save image data and remove from data array
            
            if (!$this->dataProcessor->validate($data)) {
                $this->_redirect('*/*/edit', ['api_id' => $model->getId(), '_current' => true]);
                return;
            }

            try {
                
				/* assign the products to the orginzation in by using excel sheet @sankarshana*/
                
				/* assign the products to the orginzation in by using excel sheet @sankarshana*/
				
                
                $model->save();
                $this->messageManager->addSuccess(__('The Data has been saved.'));
				$this->messageManager->addError(__($notfoundsku));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['api_id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['api_id' => $this->getRequest()->getParam('api_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
