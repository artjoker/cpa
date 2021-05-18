<?php

    namespace Artjoker\Cpa\Providers\LetMeAds;

    class LeadModel
    {
        /** @var string */
        public $click_id;

        /**
         * Web master identifier
         * @var string
         */
        public $utm_term;

        /**
         * @var string
         */
        public $utm_medium;

        /**
         * @var string
         */
        public $utm_campaign;

        public function rules(): array
        {
            return [
                'click_id'     => 'required|string',
                'utm_term'     => 'string',
                'utm_medium'   => 'string',
                'utm_campaign' => 'string',
            ];
        }
    }