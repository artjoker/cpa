<?php

    namespace Artjoker\Cpa\Providers\GuruLeads;

    class LeadModel
    {
        /** @var string */
        public $click_id;

        /**
         * Web master identifier
         * @var string
         */
        public $wm_id;

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
                'wm_id'        => 'string',
                'utm_medium'   => 'string',
                'utm_campaign' => 'string',
            ];
        }
    }