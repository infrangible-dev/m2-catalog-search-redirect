<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Controller\Adminhtml\Query;

use Infrangible\CatalogSearchRedirect\Traits\Query;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Delete
    extends \Infrangible\BackendWidget\Controller\Backend\Object\Delete
{
    use Query;

    /**
     * @return string
     */
    protected function getObjectDeletedMessage(): string
    {
        return __('The term has been deleted.')->render();
    }
}
