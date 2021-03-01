<?php

    namespace Artjoker\Cpa\Providers\Pap;

    class LeadModel
    {
        /** @var string */
        public $papid;
        /**
         * Web master identifier
         * @var string
         */
        public $utm_medium;

        public function rules(): array
        {
            return [
                'papid'      => 'required|string',
                'utm_medium' => 'string',
            ];
        }
    }