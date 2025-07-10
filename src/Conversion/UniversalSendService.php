<?php

namespace Artjoker\Cpa\Conversion;

use Artjoker\Cpa\Models\CpaNetwork;
use Artjoker\Cpa\Interfaces\Conversion\SendServiceInterface;
use Artjoker\Cpa\Models\Conversion;
use Artjoker\Cpa\Conversion\Postback;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;

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
        $url = $this->getDomain();
        $method = $config['method'] ?? 'get';
        
        // Отримуємо налаштування для конкретної події
        $event = $conversion->event ?? 'register';
        $event_config = $config['events'][$event] ?? [];
        
        // Перевіряємо, чи існує подія в конфігурації та чи вона увімкнена
        if (empty($event_config) || (isset($event_config['enabled']) && $event_config['enabled'] === false)) {
            // Повертаємо пустий Postback без відправки запиту
            return new Postback(new Request($method, $url), null);
        }
        
        // Формуємо шлях
        $path = $event_config['path'] ?? $config['path'] ?? '';
        
        // Об'єднуємо параметри
        $default_params = $config['default_params'] ?? [];
        $event_params = $event_config['params'] ?? [];
        $all_params = array_merge($default_params, $event_params, $params);
        
        // Замінюємо плейсхолдери на реальні значення
        $all_params = $this->replacePlaceholders($all_params, $conversion);
        
        $fullUrl = rtrim($url, '/') . ($path ? '/' . ltrim($path, '/') : '') . 
                   ($all_params ? '?' . http_build_query($all_params) : '');

        // Логуємо запит якщо увімкнено
        $this->logRequest($method, $fullUrl, $all_params, $event);

        $request = new Request($method, $fullUrl);
        $response = null;
        try {
            $response = $client->send($request);
            
            // Логуємо успішну відповідь
            $this->logResponse($response, $event);
            
        } catch (\Exception $e) {
            // Логуємо помилку
            $this->logError($e, $event, $fullUrl);
        }
        return new Postback($request, $response);
    }

    public function getDomain(): string
    {
        return $this->network->base_url ?? '';
    }

    public function getSource(): string
    {
        return $this->network->slug ?? '';
    }

    protected function replacePlaceholders(array $params, Conversion $conversion): array
    {
        $click_id = $conversion->getConfig()['click_id'] ?? null;
        
        $replacements = [
            '{amount}' => $conversion->amount ?? '',
            '{conversion_id}' => $conversion->getId(),
            '{user_id}' => $conversion->getUser(),
            '{click_id}' => $click_id,
            // Додайте інші плейсхолдери за потреби
        ];

        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $params[$key] = str_replace(array_keys($replacements), array_values($replacements), $value);
            }
        }

        return $params;
    }

    protected function logRequest(string $method, string $url, array $params, string $event): void
    {
        if (!Config::get('cpa.logging.enabled', false) || !Config::get('cpa.logging.log_requests', false)) {
            return;
        }

        $level = Config::get('cpa.logging.level', 'info');
        \Log::log($level, 'CPA Request', [
            'network' => $this->getSource(),
            'event' => $event,
            'method' => $method,
            'url' => $url,
            'params' => $params,
        ]);
    }

    protected function logResponse($response, string $event): void
    {
        if (!Config::get('cpa.logging.enabled', false) || !Config::get('cpa.logging.log_responses', false)) {
            return;
        }

        $level = Config::get('cpa.logging.level', 'info');
        \Log::log($level, 'CPA Response', [
            'network' => $this->getSource(),
            'event' => $event,
            'status_code' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
        ]);
    }

    protected function logError(\Exception $e, string $event, string $url): void
    {
        \Log::error('CPA UniversalSendService error', [
            'network' => $this->getSource(),
            'event' => $event,
            'url' => $url,
            'error' => $e->getMessage(),
        ]);
    }
} 