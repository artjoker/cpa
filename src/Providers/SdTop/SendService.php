<?php

    namespace Artjoker\Cpa\Providers\SdTop;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Psr7\Request;

    class SendService implements SendServiceInterface
    {
        use SendServiceTrait;

        public const PATH_POSTBACK = 'in/postback';

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
            $this->source = LeadSource::SD_TOP;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $clickId = $conversion->getConfig()['clickId'] ?? null;
            $transId = $conversion->getId();
            $affId   = $conversion->getConfig()['aid'] ?? null;

            $token = $params['token'] ?? $this->config->getToken($conversion->getProduct());
            $id    = $params['id'] ?? $this->config->getId($conversion->getProduct());
            $path  = $params['path'] ?? self::PATH_POSTBACK;

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/{$path}/{$id}/{$clickId}?trans_id={$transId}&aff_id={$affId}&token={$token}{$customParams}";

            return new Request('get', $url);
        }
    }