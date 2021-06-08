<?php

    namespace Artjoker\Cpa\Providers\Click2Money;

    class LeadModel
    {
        /** @var string */
        public $click_id;

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
                'utm_medium'   => 'string',
                'utm_campaign' => 'string',
            ];
        }
    }