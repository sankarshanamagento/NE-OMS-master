<?php use Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();

$sql="delete from url_rewrite where entity_type='product' and entity_id NOT IN (Select entity_id from catalog_product_entity);";
$result = $connection->query($sql);
$apistatus="inprogress";
$jobs = $objectManager->create('Netenrich\Webhook\Model\Webhook')->getCollection()->addFieldToFilter('status',$apistatus);

foreach($jobs as $jobwebhook)
	{
			$categorydata['jobUniqueIDS'][]=$jobwebhook->getJobid();
	}
	
	$jsonstr=json_encode($categorydata);

/*      print_r($jsonstr);
	 exit; */
	


         $agent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0';
		
		$service_url = 'https://f1.netenrich.us/automation/jobStatus';
		$curl = curl_init($service_url);
																						  
		   
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                                                                
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $agent);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$jsonstr);
		$curl_response = curl_exec($curl);
		
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
		 //$this->_messageManager->addError(__('error occured during curl exec. Additioanl info:'.$data_string));
		 
		}
		curl_close($curl);



$json_a =  json_decode($curl_response, true);


	
foreach($json_a['response'] as $ids=>$value)
{
	
	
	


	foreach($value as $data=>$res)
	{
		//$apires="";
		
		
		  $reason = json_decode($res['triggerResponse'], true);
		   
		 
		 
		   if($res['status']=='failed')
	    {
		     $divares="";
			  if(count($reason['response'])>1)
			  { 
				  foreach($reason['response'] as $apiid=>$val)
				  {
					$resfail[]=$val['reason'];
					$divares=implode(",",$resfail);
				  }
			  }
				  else if(count($reason['response']) == 1)
				  {
					foreach($reason['response'] as $apiid=>$val)
				    {  
					 $divares=$val['reason'];  
				    }
				  }
			
	   }
    else
	{
		$divares="";	
	} 
			
	    
		//echo $divares;
	     $status=$res['status'];
	
	 
	
		
	}
	
	//print_r($json_a);
	$_productlocal = $objectManager->create('Netenrich\Webhook\Model\Webhook')->getCollection()->addFieldToFilter('jobid',$ids);
	foreach($_productlocal as $webhook)
	{
		
		$_editweb = $objectManager->get('Netenrich\Webhook\Model\Webhook')->load($webhook->getApiId());
		$_editweb->setStatus($status);
		$_editweb->setResponse($divares);
		$_editweb->save(); 
		
	} 

}




?>