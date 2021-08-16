<?php

    namespace Artjoker\Cpa\Providers\Click2Money;

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
            $this->source = LeadSource::CLICK2MONEY;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId      = $conversion->getConfig()['click_id'] ?? null;
            $conversionId = $conversion->getId();

            $path    = $params['path'] ?? $this->config->getPath($conversion->getProduct());
            $action  = $params['action'] ?? null;
            $partner = $params['partner'] ?? null;

            $queryParams = http_build_query([
                'cid'     => $clickId,
                'lead_id' => $conversionId,
                'action'  => $action,
                'partner' => $partner,
            ]);

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/{$path}?{$queryParams}{$customParams}";

            return new Request('get', $url);
        }
    }
