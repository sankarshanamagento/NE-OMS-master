<?php use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

$_productlocal = $objectManager->create('Netenrich\Services\Model\Services')->getCollection()->addFieldToFilter('partnumber',$_POST['pbrand']);
$response = array();
foreach($_productlocal as $dataproduct)
{
	
	
	                          $response['name']=$dataproduct->getServicename();
							  $response['tower']=$dataproduct->getTowers();
							  $response['assets']=$dataproduct->getAssets();
							  $response['payable']=$dataproduct->getPayable();
							  $response['contractrem']=$dataproduct->getContractterm();
							  $response['package']=$dataproduct->getServicePackage();
							  $response['servicelevel']=$dataproduct->getServicelevel();
							  $response['servicetype']=$dataproduct->getServiceType();
							  $response['practice']=$dataproduct->getPractice();
							  $response['categoryid']=$dataproduct->getCategoryid();
							  $response['subcategoryid']=$dataproduct->getSubcategoryid();
							  $response['store']=$dataproduct->getStore();
}

echo $content = json_encode($response);
?>