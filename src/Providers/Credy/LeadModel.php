<?php

namespace Artjoker\Cpa\Providers\Credy;

class LeadModel
{
    /**
     * Transaction identifier
     * @var string
     */
    public $tid;

    public function rules(): array
    {
        return [
            'tid' => 'required|string',
        ];
    }
}