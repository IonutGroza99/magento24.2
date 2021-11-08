<?php
namespace Bitstone\Newsfeed\Controller\Adminhtml\Posts;

use \Bitstone\Newsfeed\Controller\Adminhtml\Posts;

class Grid extends Posts
{
    public function execute()
    {
        return $this->_resultPageFactory->create();
    }
}
