<?php

namespace Artjoker\Cpa\Providers\DoAffiliate;

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
        $this->source = LeadSource::DO_AFFILIATE;
    }


    protected function getRequest(Conversion $conversion, array $params): Request
    {
        $visitor  = $conversion->getConfig()['visitor'] ?? null;
        $path = $params['path'] ?? $this->config->getPath($conversion->getProduct());
        $type = $params['type'] ?? 'CPA';

        $queryParams = http_build_query([
            'type' => $type,
            'lead' => $conversion->getId(),
            'v'    => $visitor,
        ]);

        $customParams = '';
        if (!empty($params['custom_params'])) {
            $customParams = '&' . http_build_query($params['custom_params']);
        }

        $url = "{$this->getDomain()}/api/{$path}?{$queryParams}{$customParams}";

        return new Request('get', $url);
    }
}