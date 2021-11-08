<?php

namespace Bitstone\Newsfeed\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Posts extends AbstractDb
{
    protected function _construct()
    {
        // magetop_blog is table name and id is Primary of Table
        $this->_init('bitstone_newsfeed_post', 'post_id');
    }
}
