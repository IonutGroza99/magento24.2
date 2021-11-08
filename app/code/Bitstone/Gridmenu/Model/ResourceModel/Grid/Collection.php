<?php

namespace Bitstone\Gridmenu\Model\ResourceModel\Grid;
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Bitstone\Gridmenu\Model\Grid', 'Bitstone\Gridmenu\Model\ResourceModel\Grid');
    }
}
