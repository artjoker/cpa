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
        public $gclid;

        public function rules(): array
        {
            return [
                'data1'  => 'required|string',
                'gclid' => 'required|string',
            ];
        }
    }