<?php

namespace Artjoker\Cpa\Providers\DoAffiliate;

class LeadModel
{
    /**
     * Visitor identifier
     * @var string
     */
    public $visitor;

    public function rules(): array
    {
        return [
            'visitor' => 'required|string',
        ];
    }
}