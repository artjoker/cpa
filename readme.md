# Laravel CPA Network Integration
inspired by wearesho-team/bobra-cpa

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

![laravel cpa](logo.png)

Laravel Package for [CPA](https://en.wikipedia.org/wiki/Cost_per_action) networks integration and target customer actions registration in your application.
Currently supported: [Admitad](https://www.admitad.com/ru/), [Credy](https://www.adcredy.com/), [DoAffiliate](https://www.doaffiliate.net/), [Finline](https://finline.ua/),
 [LeadGid](https://leadgid.eu/), [Leads.su](https://leads.su/), [PapaKarlo](https://papakarlo.com/), [Sales Doubler](https://www.salesdoubler.com.ua/), Storm Digital, Loangate, Appscorp, PAP, GoodAff, LetMeAds, GuruLeads, Nolimit, MoneyGo, LeadLoan.

## Installation

Install the package via composer:

``` bash
$ composer require artjoker/cpa
```

For Laravel 5.4 and below it necessary to register the service provider

### Configuration

In order to edit the default configuration you may execute:
```
php artisan vendor:publish --provider="Artjoker\Cpa\CpaServiceProvider"
```

After that, `config/cpa.php` will be created.

### Environment
This package can be configured by environment variables out-of-box:

- **SALES_DOUBLER_ID** - personal id for request to SalesDoubler
- **SALES_DOUBLER_TOKEN** - token for request URI for SalesDoubler
- **STORM_DIGITAL_GOAL** - (default: 1), goal in URL for StormDigital
- **STORM_DIGITAL_SECURE** - secure in URL for StormDigital
- **PAPA_KARLO_TYPE** - ('offer' or 'goal') postback type for PapaKarlo
- **PAPA_KARLO_OFFER** - (default: 35) personal offer id for PapaKarlo
- **PAPA_KARLO_GOAL** - (default: 75) personal goal id for PapaKarlo
- **PDL_PROFIT_OFFER** - ID of the advertiser in the PDL-Profit system
- **DO_AFFILIATE_PATH** - path for DoAffiliate API (example: pozichka-ua in http://tracker2.doaffiliate.net/pozichka-ua)
- **LEADS_SU_TOKEN** - token for LeadsSu
- **ADMITAD_POSTBACK_KEY** - postback request authentication key, constant string 32 char
- **ADMITAD_CAMPAIGN_CODE** - AdmitAd defined campaign code, constant string 10 char
- **ADMITAD_ACTION_CODE** - target action code, get it from AdmitAd
- **CREDY_OFFER** - offer code, get it from Credy
- **LET_ME_ADS_PATH** - path for LetMeAds API (example: api/v1.1/y7r/dcfgs1tg:awvv47ghn1jv1f$am/get/postback.json)
- **GURU_LEADS_PATH** - path for GuruLeads API (example: postback)
- **CLICK2MONEY_PATH** - path for Click2Money API (example: cpaCallback)
- **NOLIMIT_PATH** - path for Nolimit API (example: postback)
- **MONEY_GO_PATH** - path for MoneyGo API (example: postback)
- **SD_TOP_ID** - personal id for request to SD_Top
- **SD_TOP_TOKEN** - token for request URI for SD_Top
- **LEAD_LOAN_PATH** - path for LeadLoan API (example: postback)

If one of key for some CPA network not set 
postback requests for this network will not be done. 

### Register Middleware

You may register the package middleware in the `app/Http/Kernel.php` file:

```php
<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
    * The application's route middleware.
    *
    * @var array
    */
    protected $routeMiddleware = [
        /**** OTHER MIDDLEWARE ****/
        'lead.check' => \Artjoker\Cpa\Middleware\LeadCheckMiddleware::class
    ];
}
```

You may add middleware to your group like this:

```php
Route::group(
    [
        'middleware' => [ 'lead.check' ]
    ], 
    function(){ //...
});
```

## Usage

Create Lead when user registered
```php
CpaLead::createFromCookie(auth()->user());
// or
CpaLead::createFromCookie($userId);
```

When goal is achieved register conversion 
```php
CpaConversion::register($user, $transactionId, 'sale');
```
Events (e.g. 'sale') **must** be specified in config. You can add additional params for specific events. See `config/cpa.php` samples

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

[ Volodymyr Taranenko @ VT2 ][link-author]

## License

license. Please see the [license file](license.md) for more information.

## Динамічне додавання та керування CPA-мережами через БД

### Опис функціоналу

Починаючи з версії 5.0.0, пакет підтримує динамічне додавання, редагування та вимкнення CPA-мереж через базу даних. Це дозволяє:
- Додавати нові мережі без зміни коду
- Керувати налаштуваннями мереж через БД
- Всі сервіси пакету автоматично працюють як із мережами з конфігу, так і з тими, що додані через БД

### Міграція

Виконайте міграцію для створення таблиці налаштувань мереж:

```bash
php artisan migrate
```

### Отримання списку мереж у коді

```php
use App\Repositories\CpaNetworkRepository;

$networks = app(CpaNetworkRepository::class)->all();
```

### Отримання налаштувань мережі за slug

```php
$network = app(CpaNetworkRepository::class)->findBySlug('my-cpa');
```

### Використання у сервісах пакету

Всі сервіси (ліди, конверсії, парсери) автоматично працюють з усіма активними мережами (з конфігу та БД).

**Зберігання даних про ліди, конверсії, cookies — у тих самих таблицях, що й раніше.**

### Пояснення полів таблиці `cpa_networks`
- `name` — Людинозрозуміла назва мережі
- `slug` — Унікальний ідентифікатор (для інтеграції)
- `base_url` — Базова адреса API мережі
- `api_key` — Ключ для авторизації в API (якщо потрібен)
- `config` — Додаткові параметри (JSON)
- `is_active` — Чи активна мережа

## Універсальний парсер та сервіс для динамічних CPA-мереж

Починаючи з версії 5.0.0, пакет підтримує автоматичну інтеграцію нових CPA-мереж, доданих у БД, без необхідності створювати окремий парсер чи сервіс для кожної мережі.

### Як це працює
- Додаєте нову мережу у таблицю `cpa_networks` (через сидер, Tinker, адмінку тощо)
- Вказуєте:
    - `slug` — це значення utm_source, яке буде приходити у посиланні
    - `base_url` — базова адреса API мережі
    - `config` — масив з параметрами (наприклад, method, path, default_params)
- Універсальний парсер автоматично визначає мережу за utm_source і створює LeadInfo
- Універсальний сервіс формує та відправляє запит згідно з шаблоном у config

### Приклад додавання мережі
```php
use App\Models\CpaNetwork;

CpaNetwork::create([
    'name' => 'Super CPA',
    'slug' => 'supercpa',
    'base_url' => 'https://api.supercpa.com',
    'config' => [
        'method' => 'get',
        'path' => 'lead',
        'default_params' => [
            'api_key' => 'your-key',
        ],
    ],
    'is_active' => true,
]);
```

### Пояснення config
- `method` — HTTP-метод (get/post)
- `path` — шлях до endpoint (опційно)
- `default_params` — масив параметрів, які будуть додані до кожного запиту

### Переваги
- Не потрібно створювати нові класи для кожної мережі
- Всі нові мережі з БД працюють автоматично
- Для складних кейсів можна додати кастомний парсер/сервіс (має пріоритет над універсальним)

[ico-version]: https://img.shields.io/packagist/v/artjoker/cpa.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/artjoker/cpa.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/artjoker/cpa/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/artjoker/cpa
[link-downloads]: https://packagist.org/packages/artjoker/cpa
[link-travis]: https://travis-ci.org/artjoker/cpa
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/artjoker
[link-contributors]: ../../contributors
