<?php
namespace Bitstone\Newsfeed\Model;
class Posts extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'bitstone_newsfeed_post';

    protected $_cacheTag = 'bitstone_newsfeed_post';

    protected $_eventPrefix = 'bitstone_newsfeed_post';

    protected function _construct()
    {
        $this->_init('Bitstone\Newsfeed\Model\ResourceModel\Posts');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
