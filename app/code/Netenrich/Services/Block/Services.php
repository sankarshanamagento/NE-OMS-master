<?php

namespace Netenrich\Services\Block;

/**
 * Services content block
 */
class Services extends \Magento\Framework\View\Element\Template
{
    /**
     * Services collection
     *
     * @var Netenrich\Services\Model\ResourceModel\Services\Collection
     */
    protected $_ServicesCollection = null;
    
    /**
     * Services factory
     *
     * @var \Netenrich\Services\Model\ServicesFactory
     */
    protected $_ServicesCollectionFactory;
    
    /** @var \Netenrich\Services\Helper\Data */
    protected $_dataHelper;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Netenrich\Services\Model\ResourceModel\Services\CollectionFactory $ServicesCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Netenrich\Services\Model\ResourceModel\Services\CollectionFactory $ServicesCollectionFactory,
        \Netenrich\Services\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_servicesCollectionFactory = $ServicesCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct(
            $context,
            $data
        );
    }
    
    /**
     * Retrieve Services collection
     *
     * @return Netenrich\Services\Model\ResourceModel\Services\Collection
     */
    protected function _getCollection()
    {
        $collection = $this->_servicesCollectionFactory->create();
        return $collection;
    }
    
    /**
     * Retrieve prepared Services collection
     *
     * @return Netenrich\Services\Model\ResourceModel\Services\Collection
     */
    public function getCollection()
    {
        
		if (is_null($this->_ServicesCollection)) {
            $this->_ServicesCollection = $this->_getCollection();
            $this->_ServicesCollection->setCurPage($this->getCurrentPage());
            $this->_ServicesCollection->setPageSize($this->_dataHelper->getServicesPerPage());
            //$this->_CompanyCollection->setOrder('created_at','asc');
        }

        return $this->_ServicesCollection;
    }
    
    /**
     * Fetch the current page for the Services list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }
    
    /**
     * Return URL to item's view page
     *
     * @param Netenrich\Services\Model\Services $ServicesItem
     * @return string
     */
    public function getItemUrl($ServicesItem)
    {
        return $this->getUrl('*/*/view', array('id' => $ServicesItem->getId()));
    }
    
    /**
     * Return URL for resized Services Item image
     *
     * @param Netenrich\Services\Model\Services $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return $this->_dataHelper->resize($item, $width);
    }
    
    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
        $pager = $this->getChildBlock('Services_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $ServicesPerPage = $this->_dataHelper->getServicesPerPage();

            $pager->setAvailableLimit([$ServicesPerPage => $ServicesPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(TRUE);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            );

            return $pager->toHtml();
        }

        return NULL;
    }
}
