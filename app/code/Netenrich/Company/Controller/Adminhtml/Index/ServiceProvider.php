<?php

namespace Netenrich\Company\Controller\Adminhtml\Index;



class ServiceProvider extends \Magento\Backend\App\Action
{
    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
	protected $doccollectionFactory;
	protected $authSession;
	protected $_date;

    /**
     * @param \Magento\Framework\App\Action\Context           $context
     * @param \Magento\Framework\View\Result\PageFactory      $resultPageFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
	\Magento\Framework\Stdlib\DateTime\DateTime $_date,
	\Magento\Framework\View\Result\PageFactory $resultPageFactory,
	\Magento\Backend\Model\Auth\Session $authSession,
	\Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
	\Netenrich\Company\Model\CompanydocFactory $doccollectionFactory,
	array $data=[]

    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_date = $_date;
		$this->_authSession = $authSession;
        $this->resultRawFactory = $resultRawFactory;
		$this->doccollectionFactory=$doccollectionFactory;

        parent::__construct($context,$data);
    }

    public function execute()
    {
		$docdate = $this->_date->gmtDate();
		$docinsertion=$this->doccollectionFactory->create();
    //$docid=
	$auth = $this->_authSession;
	$createduser=$auth->getUser()->getFirstname();
    if($_POST['docid']!="")
    {
      $docinsertion->load($_POST['docid']);
  		$docinsertion->setComments($_POST['comments']);
  		$docinsertion->setDocdate($docdate);
  		$docinsertion->save();
  		//echo $this->getdocData($_POST['orgid']);
    }
    else {
      $docinsertion->setTitle($_POST['title']);
  		$docinsertion->setOrg_id($_POST['orgid']);
  		$docinsertion->setComments($_POST['comments']);
  		$docinsertion->setDescription($_POST['description']);
  		$docinsertion->setFiledata($_POST['basestring']);
  		$docinsertion->setDocdate($docdate);
		$docinsertion->setCreateduser($createduser);
  		$docinsertion->save();
  		
    }
     
	 echo $this->getdocData($_POST['orgid']);
        
	}

	/**
	*@param string $orgid
	*/
	public function getdocData ($orgid)
    {

		$collection=$this->doccollectionFactory->create()->getCollection();
		$collectionlist=$collection->addFieldToFilter('org_id',['eq'=>$orgid]);
		$selectlist=array();
		$html='<table class="tg">
               <tr><th class="tg-yw4l">Title</th>
      <th class="tg-yw4l">Created Date</th>
      <th class="tg-yw4l">Created By</th>
	  <th class="tg-yw4l">Action</th>
  </tr>';
		foreach($collectionlist as $doclist)
		{
			$html.='<tr >
     <td class="tg-yw4l" >
            
			<div class="data">
			<input id="'.$doclist->getDocid().'description" type="hidden" name="description" value="'.$doclist->getDescription().'">
			<input id="'.$doclist->getDocid().'comments" type="hidden" name="comments" value="'.$doclist->getComments().'">
			<input id="'.$doclist->getDocid().'title" type="hidden" name="title" value="'.$doclist->getTitle().'">
			<input id="'.$doclist->getDocid().'filedata" type="hidden" name="filedata" value="'.$doclist->getFiledata().'">
			</div>
			<div class="clickme viewpop" id="'.$doclist->getDocid().'">'.$doclist->getTitle().'</div></td>
			<td class="tg-yw4l">'.$doclist->getDocdate().'</td>
            <td class="tg-yw4l">'.$doclist->getCreateduser().'</td>
			<td class="tg-yw4l"><a href="'.$doclist->getFiledata().'" target="_blank">Preview</a></td>
           </tr>';
		}
        $html.='</table>';
		return $html;

	}


}
