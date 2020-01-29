<?php


namespace Artjoker\Cpa\Interfaces\Lead;


use Artjoker\Cpa\Lead\LeadInfo;

interface LeadParser
{
    public function parse(string $url) :?LeadInfo;
}