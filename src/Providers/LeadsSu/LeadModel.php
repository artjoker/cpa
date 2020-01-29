<?php

namespace Artjoker\Cpa\Providers\LeadsSu;

class LeadModel
{
    /**
     * Click identifier
     * @var string
     */
    public $transactionId;

    public function rules(): array
    {
        return [
            'transactionId' => 'required|string|max:32',
        ];
    }
}