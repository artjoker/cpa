<?php

    namespace Artjoker\Cpa\Providers\Loangate;

    class LeadModel
    {
        /**
         * Click identifier
         * @var string
         */
        public $afclick;

        public function rules(): array
        {
            return [
                'afclick' => 'required|string',
            ];
        }
    }