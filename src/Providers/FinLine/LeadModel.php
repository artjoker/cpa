<?php

namespace Artjoker\Cpa\Providers\FinLine;

class LeadModel
{
    /**
     * Click identifier
     * @var string
     */
    public $clickId;

    public function rules(): array
    {
        return [
            'clickId' => 'required|string',
        ];
    }
}