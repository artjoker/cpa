<?php

    namespace Artjoker\Cpa\Providers\FinMe;

    class LeadModel
    {
        /**
         * Click identifier
         * @var string
         */
        public $clickId;

        /**
         * Web master identifier
         * @var string
         */
        public $utm_medium;

        public function rules(): array
        {
            return [
                'clickId'    => 'required|string',
                'utm_medium' => 'string',
            ];
        }
    }