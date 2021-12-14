<?php

namespace Artjoker\Cpa\Middleware;

use Artjoker\Cpa\Lead\LeadService;
use App\Helpers\System;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class LeadCheckMiddleware
{
    /**
     * @var LeadService
     */
    private $leadService;

    /**
     * LeadCheckMiddleware constructor.
     * @param  LeadService  $leadService
     */
    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Parse incoming request for cpa sources
     * and store cpa get params to db if user is authenticated
     * or store this data to cookie if anonymous user
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->has('utm_source') || (!$request->has('utm_source') && $request->has('source'))) {
            $leadGuard = Config::get('cpa.lead_guard');
            $requestUrl = $request->fullUrl();
            if (Auth::guard($leadGuard)->check()) {
                $this->leadService->create(Auth::guard($leadGuard)->user(), $requestUrl);
            } else {
                $this->leadService->storeToCookie($requestUrl);
            }
        }

        return $next($request);
    }
}
