<?php

    namespace Artjoker\Cpa\Providers\SdTop;

    class LeadModel
    {
        /** @var string */
        public $clickId;
        /**
         * Web master identifier
         * @var string
         */
        public $aid;

        public function rules(): array
        {
            return [
                'clickId' => 'required|string',
                'aid'     => 'string',
            ];
        }
    }