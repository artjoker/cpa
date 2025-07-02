<?php

namespace App\Repositories;

use App\Models\CpaNetwork;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Arr;

/**
 * Репозиторій для отримання налаштувань CPA-мереж з конфігу та БД.
 * Дозволяє отримати всі активні мережі або одну за slug.
 */
class CpaNetworkRepository
{
    /**
     * Повертає всі активні CPA-мережі (з конфігу та БД)
     *
     * @return array
     */
    public function all(): array
    {
        $db_networks = CpaNetwork::where('is_active', true)->get()->keyBy('slug')->toArray();

        $config_sources = Config::get('cpa.sources', []);
        $config_domains = Config::get('cpa.domains', []);
        $networks = [];

        foreach ($config_sources as $slug => $enabled) {
            if ($enabled) {
                $networks[$slug] = [
                    'name'      => ucfirst(str_replace('_', ' ', $slug)),
                    'slug'      => $slug,
                    'base_url'  => Arr::get($config_domains, $slug),
                    'config'    => [],
                    'is_active' => true,
                    'source'    => 'config',
                ];
            }
        }

        // Додаємо/перезаписуємо мережі з БД (мають пріоритет)
        foreach ($db_networks as $slug => $network) {
            $networks[$slug] = array_merge($networks[$slug] ?? [], $network, ['source' => 'db']);
        }

        return $networks;
    }

    /**
     * Отримати мережу за slug
     *
     * @param string $slug
     * @return array|null
     */
    public function findBySlug(string $slug): ?array
    {
        $all = $this->all();
        return $all[$slug] ?? null;
    }
} 