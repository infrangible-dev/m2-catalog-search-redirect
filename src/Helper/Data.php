<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
    extends AbstractHelper
{
    public const MODE_EQUALS = 'equals';
    public const MODE_PART_OF = 'part_of';
    public const MODE_WORDS_ALL = 'words_all';
    public const MODE_WORDS_ANY = 'words_any';
}
