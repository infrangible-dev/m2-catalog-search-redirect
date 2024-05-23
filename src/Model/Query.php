<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @method string getText()
 * @method int getStoreId()
 * @method string getMode()
 * @method int getCaseSensitive()
 * @method string getRedirectUrl()
 * @method int getStatus()
 */
class Query
    extends AbstractModel
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(ResourceModel\Query::class);
    }
}
