<?php

    namespace Artjoker\Cpa\Providers\Appscorp;

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
            $this->source = LeadSource::APPSCORP;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId      = $conversion->getConfig()['data1'] ?? null;
            $gclid        = $conversion->getConfig()['gclid'] ?? null;
            $conversionId = $conversion->getId();

            $path      = $params['path'] ?? null;
            $action    = $params['action'] ?? null;
            $comission = $params['comission'] ?? null;
            $status    = $params['status'] ?? null;
            $campaign  = $params['campaign'] ?? null;

            $queryParams = http_build_query([
                'data1'         => $clickId,
                'transactionId' => $conversionId,
                'actionName'    => $action,
                'comission'     => $comission,
                'status'        => $status,
                'campaignName'  => $campaign,
                'gclid'         => $gclid,
            ]);

            $url = "{$this->getDomain()}/{$path}?{$queryParams}";

            return new Request('get', $url);
        }
    }