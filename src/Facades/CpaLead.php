<?php

namespace Artjoker\Cpa\Facades;

use Artjoker\Cpa\Models\CpaCookie;
use Artjoker\Cpa\Models\Lead;
use Illuminate\Support\Facades\Facade;

/**
 * Class CpaLead
 * @package Artjoker\Cpa\Facades
 *
 * @method static Lead|null getLastLeadByUser($user): ?Lead
 * @method static Lead|null create($user, $urls): ?Lead
 * @method static Lead|null createFromCookie($user): ?Lead
 * @method static CpaCookie storeToCookie($url)
 *
 * @see \Artjoker\Cpa\Lead\LeadService
 */
class CpaLead extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'cpaLead';
    }
}
