<?php

    return [

        /*
        |--------------------------------------------------------------------------
        | Leads Table
        |--------------------------------------------------------------------------
        |
        | This is the table used by Cpa to save lead info to the database.
        |
        */
        'user_leads_table'  => 'cpa_leads',

        /*
        |--------------------------------------------------------------------------
        | Conversion Table
        |--------------------------------------------------------------------------
        |
        | This is the table used by Cpa to save lead`s conversion to the database.
        |
        */
        'conversions_table' => 'cpa_conversions',

        /*
        |--------------------------------------------------------------------------
        | CPA cookie Table
        |--------------------------------------------------------------------------
        |
        | This is the table used by Cpa to save cookie id to the database.
        |
        */
        'cookies_table'     => 'cpa_cookies',

        /*
        |--------------------------------------------------------------------------
        | Lead Model and Guard
        |--------------------------------------------------------------------------
        |
        | This is the Lead model used by Cpa to create correct relations with auth user.
        | Update the lead if it is in a different namespace.
        | e.g. User, Client, Customer models
        */
        'lead_model'        => 'App\User',
        'lead_guard'        => 'user',

        /*
        |--------------------------------------------------------------------------
        | Cookie life time period
        |--------------------------------------------------------------------------
        |
        | If user not sign in app store cpa source cookie for this time, minutes
        | This time you will get from CPA Network deal, usually 30 days
        |
        */
        'cookie_period'     => 30 * 24 * 60,

        /*
        |--------------------------------------------------------------------------
        | Lead sources
        |--------------------------------------------------------------------------
        |
        | Enable or disable cpa networks are you using
        |
        */
        'sources'           => [
            'admitad'       => false,
            'credy'         => false,
            'do_affiliate'  => false,
            'fin_line'      => false,
            'lead_gid'      => false,
            'leads_su'      => false,
            'papa_karlo'    => false,
            'pdl_profit'    => false,
            'sales_doubler' => false,
            'storm_digital' => false,
            'loangate'      => false,
            'appscorp'      => false,
            'pap'           => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | CPA network domains
        |--------------------------------------------------------------------------
        |
        | Specify domains for send cpa network post back
        |
        */
        'domains'           => [
            'admitad'       => 'https://ad.admitad.com',
            'credy'         => 'http://tracking.adcredy.com',
            'do_affiliate'  => 'http://tracker2.doaffiliate.net',
            'fin_line'      => 'http://offers.finline.affise.com',
            'lead_gid'      => 'http://go.leadgid.ru',
            'leads_su'      => 'http://api.leads.su',
            'papa_karlo'    => 'http://targetme.go2cloud.org',
            'pdl_profit'    => 'https://tds.pdl-profit.com',
            'sales_doubler' => 'http://rdr.salesdoubler.com.ua',
            'storm_digital' => 'http://offers.stormdigital.affise.com',
            'loangate'      => 'http://offers.loangate.affise.com',
            'appscorp'      => 'https://iamdataninja.com',
            'pap'           => 'https://squidleads.com',
        ],


        /*
        |--------------------------------------------------------------------------
        | CPA events
        |--------------------------------------------------------------------------
        |
        | Specify events and additional params for concrete CPA network
        | e.g. 'lead', 'purchase', 'register', see documentations
        |
        */
        'events'            => [

            'purchase' => [
                'admitad'       => [],
                'do_affiliate'  => [
                    'type' => 'CPA',
                ],
                'fin_line'      => [
                    'goal' => 1,
                ],
                'lead_gid'      => [
                    'type'     => 'goal',
                    'offer_id' => 1,
                    'goal_id'  => 1,
                ],
                'leads_su'      => [
                    'goal' => 0,
                ],
                'papa_karlo'    => [
                    'type'     => 'offer',
                    'offer_id' => 1,
                ],
                'sales_doubler' => [],
                'storm_digital' => [
                    'goal' => 1,
                ],
                'loangate'      => [
                    'goal' => 1,
                ],
                'credy'         => [
                    'type'     => 'goal',
                    'offer_id' => 1,
                    'goal_id'  => 1,
                ],
                'appscorp'      => [
                    'action'    => 'CPL',
                    'path'      => 'pb', // 'pb/site'
                    'comission' => 0,
                    'status'    => 'A',
                    'campaign'  => 'site.com',
                ],
                'pap'           => [
                    'path'        => 'scripts/sale.php',
                    'action_code' => 'new_loan',
                    'status'      => 'A',
                ],
            ],

            'register' => [
                'papa_karlo'    => [
                    'type'    => 'goal',
                    'goal_id' => '1',
                ],
                'storm_digital' => [
                    'goal' => 3,
                ],
                'lead_gid'      => [
                    'type'     => 'offer',
                    'offer_id' => 1,
                    'goal_id'  => 1,
                ],
                'credy'         => [
                    'type'     => 'offer',
                    'offer_id' => 1,
                ],
            ],

            'lead' => [
                'papa_karlo'    => [
                    'type'    => 'goal',
                    'goal_id' => '2',
                ],
                'storm_digital' => [
                    'goal' => 4,
                ],
            ],
        ],

    ];