<?php



declare(strict_types=1);

namespace BrianFaust\Twitter\Facades;

use Illuminate\Support\Facades\Facade;

class Twitter extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'twitter';
    }
}
