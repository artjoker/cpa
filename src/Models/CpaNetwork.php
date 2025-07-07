<?php

namespace Artjoker\Cpa\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель для CPA-мережі, що зберігає налаштування інтеграції.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $base_url
 * @property string|null $api_key
 * @property array|null $config
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class CpaNetwork extends Model
{
    /**
     * @var string
     */
    protected $table = 'cpa_networks';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'base_url',
        'config',
        'is_active',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];
} 