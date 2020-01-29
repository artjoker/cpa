<?php

namespace Artjoker\Cpa\Providers\Credy;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    public $source = LeadSource::CREDY;

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
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $transactionId = $conversion->getConfig()['tid'] ?? null;
        $offer         = $this->config->getOffer($conversion->getProduct());
        $conversionId  = $conversion->getId();

        $queryParams = http_build_query([
            'offer_id'       => $offer,
            'transaction_id' => $transactionId,
            'adv_sub'        => $conversionId,
        ]);

        $url = "{$this->getDomain()}/aff_lsr?{$queryParams}";

        return new Request('get', $url);
    }
}