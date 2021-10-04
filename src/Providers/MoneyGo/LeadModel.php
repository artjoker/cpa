<?php

    namespace Artjoker\Cpa\Providers\MoneyGo;

    class LeadModel
    {
        /** @var string */
        public $click_id;

        /**
         * @var string
         */
        public $utm_medium;

        public function rules(): array
        {
            return [
                'click_id'     => 'required|string',
                'utm_medium'   => 'string',
            ];
        }
    }