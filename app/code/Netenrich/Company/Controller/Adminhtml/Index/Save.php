<?php

namespace Netenrich\Company\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Prince\Helloworld\Model\HelloFactory;

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
        return $this->_authorization->isAllowed('Netenrich_Company::save');
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
			$distibutorproducts = $this->_objectManager->create('Netn\Distibutorproducts\Model\DistibutorproductsFactory');
			$distibutorcollection = $this->_objectManager->create('Netn\Distibutorproducts\Model\ResourceModel\Distibutorproducts\CollectionFactory');

            $id = $this->getRequest()->getParam('org_id');
            if ($id) {
                $model->load($id);
            }
            
            // save image data and remove from data array
            if (isset($data['assign'])) {
				
                $imageData = $data['assign'];
                unset($data['assign']);
            } else {
                $imageData = array();
            }
			$model->addData($data);
			
			/* $this->_resources = \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Framework\App\ResourceConnection');
            $connection= $this->_resources->getConnection();
            //$themeTable = $this->_resources->getTableName('netenrich_organisation_path');
			
			$result1 = $connection->fetchCol("SELECT max(org_id) as maxid FROM `netenrich_organisation_type` ");
         
			
			$themeTable = $this->_resources->getTableName('netenrich_organisation_path');
			$id=$result1[0]+1;
			$orgname=$data['name'];
			$orgtype=$data['org_type_id'];
			$sql = "INSERT INTO " . $themeTable . "(org_id,path,level) VALUES ('".$id."','".$orgname."', '".$orgtype."')";
				$connection->query($sql);
 */
            if (!$this->dataProcessor->validate($data)) {
                $this->_redirect('*/*/edit', ['org_id' => $model->getId(), '_current' => true]);
                return;
            }

            try {
                $imageHelper = $this->_objectManager->get('Netenrich\Company\Helper\Data');
                $imageFile = $imageHelper->uploadImage('assign');
				/* assign the products to the orginzation in by using excel sheet @sankarshana*/
                if ($imageFile) {
					
                    $imagepath="pub/media/".$imageFile;
					$file = fopen($imagepath,"r");
					$falsesku=array();
                      while (($line = fgetcsv($file)) !== FALSE) 
					  {
						//echo $line[0]."<br>";
						$collection = $productObj->create()
                                     ->addAttributeToSelect(array('name', 'entity_id'))
									 ->addAttributeToFilter('sku',$line[0])
                                     ->load();
						
					    $collectionsp= $distibutorcollection->create()
						              ->addFieldToFilter('assign_id',$line[0])
									  ->addFieldToFilter('distibutor_id',$id);
									  
					
						//echo $collectionsp->getsize();
						
						if($collection->getsize()!=0 && $collectionsp->getsize()==0)
						{								
									foreach ($collection as $product)
									{
                                      $productname=$product->getName();
									  $productid=$product->getId();
									 
                                     }
                             $distibutorModel=$distibutorproducts->create();
							  $distibutorModel->setProductid($productid);
							  $distibutorModel->setProductname($productname);
							  $distibutorModel->setDistibutorId($id);
							  $distibutorModel->setAssignId($line[0]);
							  $distibutorModel->save();
                            							
						} 
						else
						{
							if($line[0]!='sku')
							{
								if($collectionsp->getsize()==0)
								{
								$falsesku[]=$line[0];
								$skulist=implode(",",$falsesku);
								$notfoundsku="This Sku are not found in Catalog data to assign : -  ".$skulist;
								}
							}
							
						}
                      }
                      fclose($file);
					
                }
				/* assign the products to the orginzation in by using excel sheet @sankarshana*/
				
                
                $model->save();
                $this->messageManager->addSuccess(__('The Data has been saved.'));
				$this->messageManager->addError(__($notfoundsku));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['org_id' => $model->getId(), '_current' => true]);
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
            $this->_redirect('*/*/edit', ['org_id' => $this->getRequest()->getParam('org_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
