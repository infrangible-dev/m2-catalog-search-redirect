<?php

declare(strict_types=1);

namespace Infrangible\CatalogSearchRedirect\Traits;

/**
 * @author      Andreas Knollmann
 * @copyright   Copyright (c) 2014-2024 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
trait Query
{
    /**
     * @return string
     */
    protected function getModuleKey(): string
    {
        return 'Infrangible_CatalogSearchRedirect';
    }

    /**
     * @return string
     */
    protected function getResourceKey(): string
    {
        return 'infrangible_catalogsearchredirect';
    }

    /**
     * @return string
     */
    protected function getMenuKey(): string
    {
        return 'infrangible_catalogsearchredirect_queries';
    }

    /**
     * @return string
     */
    protected function getObjectName(): string
    {
        return 'Query';
    }

    /**
     * @return string
     */
    protected function getObjectField(): string
    {
        return 'query_id';
    }

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return __('Search Redirect > Terms')->render();
    }

    /**
     * @return bool
     */
    protected function allowAdd(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function allowEdit(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function allowView(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    protected function allowDelete(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function getObjectNotFoundMessage(): string
    {
        return __('Unable to find the term with id: %d!')->render();
    }
}
