<?php

namespace Artjoker\Cpa\Providers\LeadsSu\Lead;


use Artjoker\Cpa\Interfaces\Lead\LeadParser;
use Artjoker\Cpa\Lead\LeadInfo;
use Artjoker\Cpa\Traits\QueryParams;

class Parser implements LeadParser
{
    use QueryParams;

    protected const UTM_SOURCES    = ['leads-su', 'leads.su'];
    protected const TRANSACTION_ID = 'transaction_id';

    public function parse(string $url): ?LeadInfo
    {
        $query = $this->getQueryParams($url);
        $isQueryValid = in_array(($query['utm_source'] ?? null), static::UTM_SOURCES, true)
            && array_key_exists(static::TRANSACTION_ID, $query);

        if (!$isQueryValid) {
            return null;
        }

        return new LeadInfo(
            \Artjoker\Cpa\Interfaces\Lead\LeadSource::LEADS_SU,
            [
                'transactionId' => $query[static::TRANSACTION_ID]
            ]
        );
    }
}