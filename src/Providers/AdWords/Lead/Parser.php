<?php

    namespace Artjoker\Cpa\Providers\AdWords\Lead;


    use Artjoker\Cpa\Lead\LeadInfo;
    use Artjoker\Cpa\Traits\QueryParams;

    class Parser implements \Artjoker\Cpa\Interfaces\Lead\LeadParser
    {
        use QueryParams;

        protected const UTM_SOURCE = 'adwords';

        public function parse(string $url): ?LeadInfo
        {
            $query        = $this->getQueryParams($url);
            $isQueryValid = ($query['source'] ?? null) === static::UTM_SOURCE;

            if (!$isQueryValid) {
                return null;
            }

            return new LeadInfo(
                \Artjoker\Cpa\Interfaces\Lead\LeadSource::AD_WORDS,
                collect($query)->except('source')->toArray()
            );
        }
    }