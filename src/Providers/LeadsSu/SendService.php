<?php

    namespace Artjoker\Cpa\Providers\LeadsSu;

    use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
    use Artjoker\Cpa\Interfaces\Lead\LeadSource;
    use Artjoker\Cpa\Models\Conversion;
    use Artjoker\Cpa\Traits\SendServiceTrait;
    use GuzzleHttp\Psr7\Request;

    class SendService implements SendServiceInterface
    {
        use SendServiceTrait;

        public const STATUS_REJECTED = 'rejected';
        public const STATUS_PENDING  = 'pending';
        public const STATUS_APPROVED = 'approved';

        public const PATH_POSTBACK = 'advertiser/conversion/createUpdate';

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
            $this->source = LeadSource::LEADS_SU;
        }


        protected function getRequest(Conversion $conversion, array $params): Request
        {
            $transactionId = $conversion->getConfig()['transactionId'] ?? null;
            $conversionId  = $conversion->getId();

            $token  = $params['token'] ?? $this->config->getToken($conversion->getProduct());
            $goal   = $params['goal'] ?? $this->config->getGoal($conversion->getProduct());
            $status = $params['status'] ?? self::STATUS_APPROVED;
            $path   = $params['path'] ?? self::PATH_POSTBACK;

            $queryParams = http_build_query([
                'token'          => $token,
                'goal_id'        => $goal,
                'transaction_id' => $transactionId,
                'adv_sub'        => $conversionId,
                'status'         => $status,
            ]);

            $customParams = '';
            if (!empty($params['custom_params'])) {
                $customParams = '&' . http_build_query($params['custom_params']);
            }

            $url = "{$this->getDomain()}/{$path}?{$queryParams}{$customParams}";

            return new Request('get', $url);
        }
    }