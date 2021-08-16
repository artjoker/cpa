<?php

namespace Artjoker\Cpa\Providers\AdmitAd;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    const PAYMENT_TYPE_SALE = 'sale';
    const PAYMENT_TYPE_LEAD = 'lead';

    /**
     * @var EnvironmentConfig
     */
    protected $config;

    /**
     * SendService constructor.
     * @param EnvironmentConfig $config
     */
    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
        $this->source = LeadSource::ADMITAD;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $uid = $conversion->getConfig()['uid'] ?? null;

        $queryParams = http_build_query([
            'order_id'      => $conversion->getId(),
            'campaign_code' => $this->config->getCampaignCode($conversion->getProduct()),
            'uid'           => $uid,
            'postback'      => 1,
            'postback_key'  => $this->config->getPostbackKey($conversion->getProduct()),
            'action_code'   => $params['action_code'] ?? $this->config->getActionCode($conversion->getProduct()),
            'tariff_code'   => $params['tariff_code'] ?? $this->config->getTariffCode($conversion->getProduct()),
            'payment_type'  => $params['payment_type'] ?? self::PAYMENT_TYPE_SALE,
        ]);

        $customParams = '';
        if (!empty($params['custom_params'])) {
            $customParams = '&' . http_build_query($params['custom_params']);
        }

        $url = "{$this->getDomain()}/r?{$queryParams}{$customParams}";

        return new Request('get', $url);
    }
}