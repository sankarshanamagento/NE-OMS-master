<?php

/**
 * Company data helper
 */

namespace Netenrich\Services\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * Path to store config where count of Services posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE = 'services/view/items_per_page';

    /**
     * Media path to extension images
     *
     * @var string
     */
    const MEDIA_PATH = 'Services';

    /**
     * Maximum size for image in bytes
     * Default value is 1M
     *
     * @var int
     */
    const MAX_FILE_SIZE = 1048576;

    /**
     * Manimum image height in pixels
     *
     * @var int
     */
    const MIN_HEIGHT = 50;

    /**
     * Maximum image height in pixels
     *
     * @var int
     */
    const MAX_HEIGHT = 800;

    /**
     * Manimum image width in pixels
     *
     * @var int
     */
    const MIN_WIDTH = 50;

    /**
     * Maximum image width in pixels
     *
     * @var int
     */
    const MAX_WIDTH = 1024;

    /**
     * Array of image size limitation
     *
     * @var array
     */
    protected $_imageSize = array(
        'minheight' => self::MIN_HEIGHT,
        'minwidth' => self::MIN_WIDTH,
        'maxheight' => self::MAX_HEIGHT,
        'maxwidth' => self::MAX_WIDTH,
    );

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;
	protected $_messageManager;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\HTTP\Adapter\FileTransferFactory
     */
    protected $httpFactory;

    /**
     * File Uploader factory
     *
     * @var \Magento\Core\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * File Uploader factory
     *
     * @var \Magento\Framework\Io\File
     */
    protected $_ioFile;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
	protected $_systemStore;
	
	protected $attributeFactory;
	protected $collectionFactory;
	protected $storeCollectionFactory;
	protected $category;
	protected $categoryRepository;
	protected $categoryFactory;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context, 
	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, 
	\Magento\Framework\Filesystem $filesystem, 
	\Magento\Framework\File\Size $fileSize, 
	\Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory, 
	\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory, 
	\Magento\Framework\Filesystem\Io\File $ioFile, 
	\Magento\Store\Model\System\Store $systemStore,
	\Magento\Framework\Image\Factory $imageFactory,
	\Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
	\Netenrich\Services\Model\ResourceModel\Services\CollectionFactory $collectionFactory,
	\Magento\Framework\Message\ManagerInterface $messageManager,
	\Magento\Store\Model\GroupFactory $storeCollectionFactory,
    \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $category,
    \Magento\Catalog\Model\CategoryRepository $categoryRepository,
	\Magento\Catalog\Model\CategoryFactory $categoryFactory
    // \Magento\Framework\App\ObjectManager $objectManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_systemStore = $systemStore;
        $this->_imageFactory = $imageFactory;
		$this->eavAttributeRepository = $eavAttributeRepository;
		$this->_collectionFactory = $collectionFactory;
		$this->storeCollectionFactory = $storeCollectionFactory;
		$this->category = $category;
        $this->categoryRepository = $categoryRepository;
		$this->_messageManager = $messageManager;
		$this->_categoryFactory = $categoryFactory;
        // $this->_objectManager = $objectManager;
        parent::__construct($context);
    }

    /**
     * Remove Company item image by image filename
     *
     * @param string $imageFile
     * @return bool
     */
    public function removeImage($imageFile) {
        $io = $this->_ioFile;
        $io->open(array('path' => $this->getBaseDir()));
        if ($io->fileExists($imageFile)) {
            return $io->rm($imageFile);
        }
        return false;
    }

    /**
     * Return URL for resized Services Item Image
     *
     * @param Netenrich\Services\Model\Services $item
     * @param integer $width
     * @param integer $height
     * @return bool|string
     */
    public function resize(\Netenrich\Services\Model\Services $item, $width, $height = null) {
        if (!$item->getImage()) {
            return false;
        }

        if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH) {
            return false;
        }
        $width = (int) $width;

        if (!is_null($height)) {
            if ($height < self::MIN_HEIGHT || $height > self::MAX_HEIGHT) {
                return false;
            }
            $height = (int) $height;
        }

        $imageFile = $item->getImage();
        $cacheDir = $this->getBaseDir() . '/' . 'cache' . '/' . $width;
        $cacheUrl = $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';

        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(array('path' => $cacheDir));
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            $image = $this->_imageFactory->create($this->getBaseDir() . '/' . $imageFile);
            $image->resize($width, $height);
            $image->save($cacheDir . '/' . $imageFile);
            return $cacheUrl . $imageFile;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Upload image and return uploaded image file name or false
     *
     * @throws Mage_Core_Exception
     * @param string $scope the request key for file
     * @return bool|string
     */
     public function uploadImage($scope) {
        $adapter = $this->httpFactory->create();
        //$adapter->addValidator(new \Zend_Validate_File_ImageSize($this->_imageSize));
        //$adapter->addValidator(
              //  new \Zend_Validate_File_FilesSize(['max' => self::MAX_FILE_SIZE])
       // );

        if ($adapter->isUploaded($scope)) {
            // validate image
            //if (!$adapter->isValid($scope)) {
               // throw new \Magento\Framework\Model\Exception(__('Uploaded image is not valid.'));
            //}

            $uploader = $this->_fileUploaderFactory->create(['fileId' => $scope]);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->setAllowCreateFolders(true);

            if ($uploader->save($this->getBaseDir())) {
                $filename="Services/".$uploader->getUploadedFileName();
				return $filename;
            }
        }
        return false;
    }

    /**
     * Return the base media directory for Company Item images
     *
     * @return string
     */
    public function getBaseDir() {
        $path = $this->filesystem->getDirectoryRead(
                        DirectoryList::MEDIA
                )->getAbsolutePath(self::MEDIA_PATH);
        return $path;
    }

    /**
     * Return the Base URL for Company Item images
     *
     * @return string
     */
    public function getBaseUrl() {
        return $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . '/' . self::MEDIA_PATH;
    }

    /**
     * Return the number of items per page,
     * @return int
     */
    public function getServicesPerPage() {
        return abs((int) $this->_scopeConfig->getValue(self::XML_PATH_ITEMS_PER_PAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
    }
	
	public function getoptionid($attribute,$optionlabel)
	{
		
        $attributes = $this->eavAttributeRepository->get(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE,$attribute);
		$options = $attributes->getSource()->getAllOptions(true);
		
		foreach($options as $id=>$values)
		{
			if($values['label']==$optionlabel)
				{
					return $values['value'];
				}
			
		}
	}
		
	
	public function getwebsiteid($stores)
	{
		
		 $store=$this->_systemStore->getWebsiteValuesForForm();
		foreach($store as $storegate)
		{
			//print_r($storegate);
			//echo $storegate->getLabel();
			if($storegate['label'] == $stores)
			{
				return $storegate['value'];
			}
			
		} 
	
	}
	
	
	public function getcatid($catname,$storeid)
	{
		
		 
		
		$substoreid=$this->storeCollectionFactory->create()->getCollection()->addFieldToFilter('website_id',$storeid);
			 foreach($substoreid as $substoregroup)
			 {
				 $rootcatid=$substoregroup->getRootCategoryId();
			 }
			 
		$subcategories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("name",$catname)->addAttributeToFilter("level",2)->addAttributeToFilter("parent_id",$rootcatid);
		foreach($subcategories as $catinfo)
		{
			return $catinfo->getId();
		}

		
		
	}

    public function getsubcatid($subcatname,$parent)
	{
		
		
		$sublevelcategories = $this->category->create()->addFieldToFilter('is_active', 1)->addAttributeToSelect('*')->addAttributeToFilter("name",$subcatname)->addAttributeToFilter("level",3)->addAttributeToFilter("parent_id",$parent);
		foreach($sublevelcategories as $subcatinfo)
		{
			return $subcatinfo->getId();
		}
		
	}	
	
	
	

	public function getcounts($partnumber)
	{
		$collection = $this->_collectionFactory->create()->addFieldToFilter('partnumber',$partnumber);
		return $collection->getSize();
	}
    
	public function callapis($data_string)
	{
		
		
	    $agent = $_SERVER['HTTP_USER_AGENT'];
		$service_url = 'https://f1.netenrich.us/webhook/magentoAttributeSync/ppp';
		$curl = curl_init($service_url);
		$this->_messageManager->addNotice( __('This is your notice message.') );																				  
		   
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                                                                
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $agent);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		$curl_response = curl_exec($curl);
		
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
            //curl_close($curl);
			//die('error occured during curl exec. Additioanl info: ' . var_export($info));
		 //$this->_messageManager->addError(__('error occured during curl exec. Additioanl info:'.$data_string));
		 
		}
		curl_close($curl);

		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') 
		{
			
			//$this->_messageManager->addError(__($decoded->response->errormessage));
			
		}

		if(isset($decoded->status) && $decoded->status === "success")
		{
			
			return $decoded->status;
		}
		
	}
	
	
	public function Servicesapis($data_string)
	{
		
		
	    $agent = $_SERVER['HTTP_USER_AGENT'];
		
		$service_url = 'https://f1.netenrich.us/webhook/magentoServiceSync';
		$curl = curl_init($service_url);
																						  
		   
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));                                                                                                                
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, $agent);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$data_string);
		$curl_response = curl_exec($curl);
		
		if ($curl_response === false) {
			$info = curl_getinfo($curl);
		 //$this->_messageManager->addError(__('error occured during curl exec. Additioanl info:'.$data_string));
		 
		}
		curl_close($curl);

		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') 
		{
			
			//$this->_messageManager->addError(__($decoded->response->errormessage));
			
		}

		if(isset($decoded->status) && $decoded->status === 400 )
		{
			
			//$this->_messageManager->addError(__($decoded->status."...".$data_string));
		}

		if(isset($decoded->status) && $decoded->status === "success" )
		{
			
			//return $decoded->response;
			return $decoded->status;
		}
		
	}

}
