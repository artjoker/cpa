<?php

namespace Artjoker\Cpa\Providers\PapaKarlo;

class LeadModel
{
    /** @var string */
    public $clickId;
    /**
     * Web master identifier
     * @var string
     */
    public $utmTerm;

    public function rules(): array
    {
        return [
            'clickId' => 'required|string',
            'utmTerm' => 'string',
        ];
    }
}