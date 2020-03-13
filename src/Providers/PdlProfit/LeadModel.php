<?php

namespace Artjoker\Cpa\Providers\PdlProfit;

class LeadModel
{
    /** @var string */
    public $click_id;
    /**
     * Web master identifier
     * @var string
     */
    public $utm_term;

    public function rules(): array
    {
        return [
            'click_id' => 'required|string',
            'utm_term' => 'string',
        ];
    }
}