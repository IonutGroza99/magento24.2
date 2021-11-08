<?php

namespace Bitstone\Gridmenu\Block;


use Bitstone\Gridmenu\Model\ResourceModel\Grid\Collection as GridCollection;
use Bitstone\Gridmenu\Model\ResourceModel\Grid\CollectionFactory as GridCollectionFactory;
use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use Bitstone\Gridmenu\Model\Grid;

class Posts extends Template
{
    /**
     * CollectionFactory
     * @var null|CollectionFactory
     */
    protected $_gridCollectionFactory = null;

    /**
     * Constructor
     *
     * @param Context $context
     * @param GridCollectionFactory $gridCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context                       $context,
        GridCollectionFactory         $gridCollectionFactory,
        \Bitstone\Gridmenu\Model\Grid $customFactory,
        array                         $data = []
    )
    {
        parent::__construct($context, $data);
        $this->customFactory = $customFactory;
        $this->_gridCollectionFactory = $gridCollectionFactory;

    }

    /**
     * @return Grid[]
     */
    public function getPosts()
    {
        /** @var GridCollection $postCollection */
        //get values of current page
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 1;

        $postCollection = $this->_gridCollectionFactory->create();
        $postCollection->addFieldToSelect('*');
        $postCollection->setOrder('title', 'ASC');
        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);
        return $postCollection;
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();


        if ($this->getPosts()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'test.news.pager'
            )->setAvailableLimit(array(1 => 1,5 => 5, 10 => 10, 15 => 15))->setShowPerPage(true)->setCollection(
                $this->getPosts()
            );
            $this->setChild('pager', $pager);
            $this->getPosts()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * For a given post, returns its url
     * @param Grid $post
     * @return string
     */
    public function getPostUrl(
        Grid $post
    )
    {
        return '/magento24/gridview/post/view/id/' . $post->getId();
    }
}

