<?php

/**
 * Company data helper
 */

namespace Netenrich\Company\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * Path to store config where count of Company posts per page is stored
     *
     * @var string
     */
    const XML_PATH_ITEMS_PER_PAGE = 'company/view/items_per_page';

    /**
     * Media path to extension images
     *
     * @var string
     */
    const MEDIA_PATH = 'company/';

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

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
    \Magento\Framework\App\Helper\Context $context, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\File\Size $fileSize, \Magento\Framework\HTTP\Adapter\FileTransferFactory $httpFactory, \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory, \Magento\Framework\Filesystem\Io\File $ioFile, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Image\Factory $imageFactory
    // \Magento\Framework\App\ObjectManager $objectManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->httpFactory = $httpFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_ioFile = $ioFile;
        $this->_storeManager = $storeManager;
        $this->_imageFactory = $imageFactory;
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
     * Return URL for resized Company Item Image
     *
     * @param Netenrich\Company\Model\Company $item
     * @param integer $width
     * @param integer $height
     * @return bool|string
     */
    public function resize(\Netenrich\Company\Model\Company $item, $width, $height = null) {
        if (!$item->getNdadocument()) {
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

        $imageFile = $item->getNdadocument();
        $cacheDir = $this->getBaseDir() . '/' . 'cache' . '/' . $width;
        $cacheUrl = $this->getBaseUrl() . '/' . 'cache' . '/' . $width . '/';

        $io = $this->_ioFile;
        $io->checkAndCreateFolder($cacheDir);
        $io->open(array('path' => $cacheDir));
        if ($io->fileExists($imageFile)) {
            return $cacheUrl . $imageFile;
        }

        try {
            echo $image = $this->_imageFactory->create($this->getBaseDir() . '/company/' . $imageFile);
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
            $uploader->setAllowedExtensions(['csv','jpg', 'jpeg', 'gif', 'png', 'pdf', 'docx', 'doc', 'tiff' , 'bmp']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);
            $uploader->setAllowCreateFolders(true);

            if ($uploader->save($this->getBaseDir())) {
                $filename="company/".$uploader->getUploadedFileName();
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
                ) . '/company/' . self::MEDIA_PATH;
    }

    /**
     * Return the number of items per page,
     * @return int
     */
    public function getCompanyPerPage() {
        return abs((int) $this->_scopeConfig->getValue(self::XML_PATH_ITEMS_PER_PAGE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
    }

    public function getServiceProvider($orgId, $serviceProId) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION');
        $result1 = $connection->fetchRow("SELECT org_type_id FROM netenrich_organisation_type where org_id='" . $serviceProId . "'");
       
		$result12 = $connection->fetchAll("SELECT org_id,name FROM netenrich_organisation_type where org_type_id='" . $result1['org_type_id'] . "'");
         $newArray = array();
        foreach ($result12 as $k => $v) {
            $newArray[] = array('value' => $v['org_id'], 'label' => $v['name']);
        } 
        return $newArray;
    }

}
