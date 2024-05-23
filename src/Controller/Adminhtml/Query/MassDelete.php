<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Controller\Adminhtml\Query;

use Infrangible\CatalogSearchRedirect\Traits\Query;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class MassDelete
    extends \Infrangible\BackendWidget\Controller\Backend\Object\MassDelete
{
    use Query;

    /**
     * @return string
     */
    protected function getObjectsDeletedMessage(): string
    {
        return __('%d terms have been deleted.')->render();
    }
}
