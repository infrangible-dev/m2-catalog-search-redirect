<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Query
    extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('catalogsearch_query', 'query_id');
    }
}
