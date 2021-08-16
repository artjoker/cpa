<?php

namespace Artjoker\Cpa\Providers\PdlProfit;

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
     * @param EnvironmentConfig $config
     */
    public function __construct(EnvironmentConfig $config)
    {
        $this->config = $config;
        $this->source = LeadSource::PDL_PROFIT;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $clickId      = $conversion->getConfig()['click_id'] ?? null;
        $conversionId = $conversion->getId();

        $leadId     = $params['lead_id'] ?? null;
        $leadStatus = $params['lead_status'] ?? null;

        $queryParams = http_build_query([
            'click_id'       => $clickId,
            'transaction_id' => $conversionId,
            'lead_id'        => $leadId,
            'lead_status'    => $leadStatus,
        ]);

        $customParams = '';
        if (!empty($params['custom_params'])) {
            $customParams = '&' . http_build_query($params['custom_params']);
        }

        $url = "{$this->getDomain()}/postback?{$queryParams}{$customParams}";

        return new Request('get', $url);
    }
}