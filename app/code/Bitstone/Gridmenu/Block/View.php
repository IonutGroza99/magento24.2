<?php

namespace Bitstone\Gridmenu\Block;

use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\Registry;
use Bitstone\Gridmenu\Model\Grid;
use Bitstone\Gridmenu\Model\GridFactory;
use Bitstone\Gridmenu\Controller\Post\View as ViewAction;

class View extends Template
{
    /**
     * Core registry
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Post
     * @var null|Grid
     */
    protected $_post = null;

    /**
     * PostFactory
     * @var null|GridFactory
     */
    protected $_postFactory = null;

    /**
     * Constructor
     * @param Context $context
     * @param Registry $coreRegistry
     * @param GridFactory $postCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        GridFactory $postFactory,
        array $data = []
    ) {
        $this->_postFactory = $postFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Lazy loads the requested post
     * @return Grid
     * @throws LocalizedException
     */
    public function getPost()
    {
        if ($this->_post === null) {
            /** @var Grid $post */
            $post = $this->_postFactory->create();
            $post->load($this->_getPostId());

            if (!$post->getEntityId()) {
                throw new LocalizedException(__('Post not found'));
            }

            $this->_post = $post;
        }
        return $this->_post;
    }

    /**
     * Retrieves the post id from the registry
     * @return int
     */
    protected function _getPostId()
    {
        return (int) $this->_coreRegistry->registry(
            ViewAction::REGISTRY_KEY_POST_ID
        );
    }
}
