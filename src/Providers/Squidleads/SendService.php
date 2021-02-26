<?php

    namespace Artjoker\Cpa\Providers\Squidleads;

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
            $this->source = LeadSource::SQUID_LEADERS;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId      = $conversion->getConfig()['papid'] ?? null;
            $conversionId = $conversion->getId();
            $accountId    = $params['account_id'] ?? $this->config->getAccountId($conversion->getProduct());

            $path        = $params['path'] ?? null;
            $action_code = $params['action_code'] ?? null;
            $status      = $params['status'] ?? null;

            $queryParams = http_build_query([
                'AccountId'  => $accountId,
                'visitorID'  => $clickId,
                'OrderID'    => $conversionId,
                'ActionCode' => $action_code,
                'PStatus'    => $status,
            ]);

            $url = "{$this->getDomain()}/{$path}?{$queryParams}";

            return new Request('get', $url);
        }
    }
