<?php

namespace Artjoker\Cpa\Providers\SalesDoubler;

use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Interfaces\Lead\LeadSource;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Traits\SendServiceTrait;
use GuzzleHttp\Psr7\Request;

class SendService implements SendServiceInterface
{
    use SendServiceTrait;

    public $source = LeadSource::SALES_DOUBLER;

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


    protected function getRequest(Conversion $conversion): Request
    {
        $clickId = $conversion->getConfig()['clickId'] ?? null;
        $transId = $conversion->getId();
        $affId = $conversion->getConfig()['aid'] ?? null;
        $token = $this->config->getToken($conversion->getProduct());
        $id = $this->config->getId($conversion->getProduct());

        $url = "{$this->getDomain()}/in/postback/{$id}/{$clickId}?trans_id={$transId}&aff_id={$affId}&token={$token}";

        return new Request('get', $url);
    }
}