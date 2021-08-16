<?php

    namespace Artjoker\Cpa\Providers\Pap;

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
            $this->source = LeadSource::PAP;
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

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/{$path}?{$queryParams}{$customParams}";

            return new Request('get', $url);
        }
    }
