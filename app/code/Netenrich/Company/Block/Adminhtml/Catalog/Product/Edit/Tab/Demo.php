<?php
namespace Netenrich\Company\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Demo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'product/edit/demo.phtml';

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $_coreRegistry = null;
	protected $_companyCollectionFactory;
	protected $_DistibutorFactory;

    public function __construct(
        Context $context,
        Registry $registry,
		\Netn\Distibutorproducts\Model\ResourceModel\Distibutorproducts\Collection $companyCollectionFactory,
		\Netenrich\Company\Model\ResourceModel\Company\CollectionFactory  $DistibutorFactory,

        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
		$this->_companyCollectionFactory = $companyCollectionFactory;
		$this->_DistibutorFactory = $DistibutorFactory;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve product
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

	public function getCompanyCollection()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');//get current product

        $collection =$this->_companyCollectionFactory
		->addFieldToFilter('productid', array('eq' => $product->getId()))
		->load();

		  $this->setCollection($collection);

			//parent::_prepareCollection();

			$productids= array();
				foreach($collection as $product)
				{
					$productids[]=$product->getDistibutor_id();
				}
					 $productvalues=implode(",",$productids);

					 return $productvalues;
    }



	public function getCompanyList()
    {


        $collection =$this->_DistibutorFactory->create();
		$collection=$collection->addFieldToFilter('status',1);
    $collection=$collection->setOrder('org_type_id','ASC');
        $this->setCollection($collection);


			//parent::_prepareCollection();

			$distibutors= array();
				foreach($collection as $distibutor)
				{
          if($distibutor->getOrg_type_id()==1)
          {
            $type="Service Provider";
          } else if($distibutor->getOrg_type_id()==2) {
            $type="Distributor";
          }
          else if($distibutor->getOrg_type_id()==3) {
            $type="Partner";
          }
          else if($distibutor->getOrg_type_id()==4) {
            $type="Client";
          }
					$distibutors[]=array('Name'=>$distibutor->getName(),'Country'=>$distibutor->getCountry(),'Address'=>$distibutor->getAddress(),'Type'=>$type,'Orgid'=>$distibutor->getOrg_id());

				}
					 ///.$productvalues=implode(",",$productids);

					 return $distibutors;
    }
	
	
	public function getCompanyList1()
    {


        $collection =$this->_DistibutorFactory->create();
		$collection=$collection->addFieldToFilter('status',1);
		$collection=$collection->addFieldToFilter('org_type_id',2);
        $collection=$collection->setOrder('org_type_id','ASC');
        $this->setCollection($collection);


			//parent::_prepareCollection();

			$distibutors= array();
				foreach($collection as $distibutor)
				{
          if($distibutor->getOrg_type_id()==1)
          {
            $type="Service Provider";
          } else if($distibutor->getOrg_type_id()==2) {
            $type="Distributor";
          }
          else if($distibutor->getOrg_type_id()==3) {
            $type="Partner";
          }
          else if($distibutor->getOrg_type_id()==4) {
            $type="Client";
          }
					$distibutors[]=array('Name'=>$distibutor->getName(),'Country'=>$distibutor->getCountry(),'Address'=>$distibutor->getAddress(),'Type'=>$type,'Orgid'=>$distibutor->getOrg_id());

				}
					 ///.$productvalues=implode(",",$productids);

					 return $distibutors;
    }

}
