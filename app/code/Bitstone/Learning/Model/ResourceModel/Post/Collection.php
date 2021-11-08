<?php

namespace Bitstone\Learning\Model\ResourceModel\Post;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection
{
    /**
     * Remittance File Collection Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Bitstone\Learning\Model\Post', 'Bitstone\Learning\Model\ResourceModel\Post');
    }
}
