<?php

namespace Artjoker\Cpa\Providers\AdmitAd;

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
            'uid' => 'required|string',
        ];
    }
}