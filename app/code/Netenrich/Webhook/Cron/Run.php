<?php namespace Netenrich\Webhook\Cron;
 
class Run 
{
   protected $_logger;
   public function __construct(
       \Psr\Log\LoggerInterface $logger
   ) 
    {
     $this->_logger = $logger;
    }
 
   public function execute(\Magento\Cron\Model\Schedule $schedule)
   {
       //Edit it according to your requirement
       $this->_logger->debug('Cron run successfully');
	   $content =  "cron tab is places here";
	   $path = $_SERVER['DOCUMENT_ROOT'] . "NE-OMS/cron".date('Y/m/d H:i:s').".txt";
	   $fp = fopen($path,"wb");
			fwrite($fp,$content);
            fclose($fp);
       return $this;
   }
}?>