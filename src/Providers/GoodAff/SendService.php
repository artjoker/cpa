<?php

    namespace Artjoker\Cpa\Providers\GoodAff;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Psr7\Request;

    class SendService implements SendServiceInterface
    {
        use SendServiceTrait;

        /**
         * @var EnvironmentConfig
         */
        protected $config;

        /**
         * SendService constructor.
         *
         * @param EnvironmentConfig $config
         */
        public function __construct(EnvironmentConfig $config)
        {
            $this->config = $config;
            $this->source = LeadSource::GOOD_AFF;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId      = $conversion->getConfig()['click_id'] ?? null;
            $conversionId = $conversion->getId();
            $campaign_id  = $params['campaign_id'] ?? $this->config->getCampaignId($conversion->getProduct());

            $type   = $params['type'] ?? null;
            $status = $params['status'] ?? null;

            $queryParams = http_build_query([
                'click_id'       => $clickId,
                'transaction_id' => $conversionId,
                'type'           => $type,
                'status'         => $status,
                'campaign_id'    => $campaign_id,
            ]);

            $url = "{$this->getDomain()}/?{$queryParams}";

            return new Request('get', $url);
        }
    }
