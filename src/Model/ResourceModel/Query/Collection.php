<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Model\ResourceModel\Query;

use Infrangible\CatalogSearchRedirect\Model;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Collection
    extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model\Query::class, Model\ResourceModel\Query::class);
    }

    /**
     * @param int $storeId
     */
    public function addStoreFilter(int $storeId)
    {
        $this->addFieldToFilter('store_id', $storeId);
    }

    /**
     * @return void
     */
    public function addActiveFilter()
    {
        $this->addFieldToFilter('status', 1);
    }

    /**
     * @param string $direction
     */
    public function orderByPosition(string $direction = self::SORT_ORDER_ASC)
    {
        $this->addOrder('position', $direction);
    }
}
