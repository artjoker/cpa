<?php

    namespace Artjoker\Cpa\Providers\Appscorp;

    class LeadModel
    {
        /** @var string */
        public $data1;
        /**
         * Web master identifier
         * @var string
         */
        public $gclid1;

        public function rules(): array
        {
            return [
                'data1'  => 'required|string',
                'gclid1' => 'required|string',
            ];
        }
    }