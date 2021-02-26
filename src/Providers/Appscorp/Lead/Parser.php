<?php

    namespace Artjoker\Cpa\Providers\Appscorp\Lead;

    use Artjoker\Cpa\Lead\LeadInfo;
    use Artjoker\Cpa\Traits\QueryParams;

    class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
    {
        use QueryParams;

        protected const UTM_SOURCE = 'appscorp';
        protected const CLICK_ID   = 'data1';

        public function parse(string $url): ?LeadInfo
        {
            $query        = $this->getQueryParams($url);
            $isQueryValid = ($query['utm_source'] ?? null) === static::UTM_SOURCE
                && array_key_exists(static::CLICK_ID, $query);

            if (!$isQueryValid) {
                return null;
            }

            return new LeadInfo(
                \Artjoker\Cpa\Interfaces\Lead\LeadSource::APPSCORP,
                [
                    'data1'  => $query[static::CLICK_ID],
                    'gclid1' => $query['gclid1'] ?? null,
                ]
            );
        }
    }