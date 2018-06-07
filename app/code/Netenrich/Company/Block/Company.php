<?php

namespace Netenrich\Company\Block;

/**
 * Company content block
 */
class Company extends \Magento\Framework\View\Element\Template
{
    /**
     * Company collection
     *
     * @var Netenrich\Company\Model\ResourceModel\Company\Collection
     */
    protected $_CompanyCollection = null;
    
    /**
     * Company factory
     *
     * @var \Netenrich\Company\Model\CompanyFactory
     */
    protected $_CompanyCollectionFactory;
    
    /** @var \Netenrich\Company\Helper\Data */
    protected $_dataHelper;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $CompanyCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Netenrich\Company\Model\ResourceModel\Company\CollectionFactory $CompanyCollectionFactory,
        \Netenrich\Company\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_companyCollectionFactory = $CompanyCollectionFactory;
        $this->_dataHelper = $dataHelper;
        parent::__construct(
            $context,
            $data
        );
    }
    
    /**
     * Retrieve Company collection
     *
     * @return Netenrich\Company\Model\ResourceModel\Company\Collection
     */
    protected function _getCollection()
    {
        $collection = $this->_companyCollectionFactory->create();
        return $collection;
    }
    
    /**
     * Retrieve prepared Company collection
     *
     * @return Netenrich\Company\Model\ResourceModel\Company\Collection
     */
    public function getCollection()
    {
        
		if (is_null($this->_CompanyCollection)) {
            $this->_CompanyCollection = $this->_getCollection();
            $this->_CompanyCollection->setCurPage($this->getCurrentPage());
            $this->_CompanyCollection->setPageSize($this->_dataHelper->getCompanyPerPage());
            //$this->_CompanyCollection->setOrder('created_at','asc');
        }

        return $this->_CompanyCollection;
    }
    
    /**
     * Fetch the current page for the Company list
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
     * @param Netenrich\Company\Model\Company $CompanyItem
     * @return string
     */
    public function getItemUrl($CompanyItem)
    {
        return $this->getUrl('*/*/view', array('id' => $CompanyItem->getId()));
    }
    
    /**
     * Return URL for resized Company Item image
     *
     * @param Netenrich\Company\Model\Company $item
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
        $pager = $this->getChildBlock('Company_list_pager');
        if ($pager instanceof \Magento\Framework\Object) {
            $CompanyPerPage = $this->_dataHelper->getCompanyPerPage();

            $pager->setAvailableLimit([$CompanyPerPage => $CompanyPerPage]);
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
