<?php

namespace Artjoker\Cpa\Providers\Credy;

class LeadModel
{
    /**
     * Transaction identifier
     * @var string
     */
    public $transaction_id;

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|string',
        ];
    }
}