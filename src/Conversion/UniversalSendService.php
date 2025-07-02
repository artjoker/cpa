<?php

namespace App\Conversion;

use App\Models\CpaNetwork;
use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Conversion\Postback;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class UniversalSendService implements SendServiceInterface
{
    protected $network;

    public function __construct(CpaNetwork $network)
    {
        $this->network = $network;
    }

    public function send(Conversion $conversion, array $params = []): Postback
    {
        $client = new Client();
        $config = $this->network->config ?? [];
        $url = $this->network->base_url;
        $method = $config['method'] ?? 'get';
        $path = $config['path'] ?? '';
        $default_params = $config['default_params'] ?? [];
        $queryParams = array_merge($default_params, $params);
        $fullUrl = rtrim($url, '/') . ($path ? '/' . ltrim($path, '/') : '') . ($queryParams ? '?' . http_build_query($queryParams) : '');

        $request = new Request($method, $fullUrl);
        $response = null;
        try {
            $response = $client->send($request);
        } catch (\Exception $e) {
            // логування або обробка помилки
        }
        return new Postback($request, $response);
    }
} 