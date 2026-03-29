<?php

namespace Mostafaarafat\DataChat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
                ->header('Access-Control-Allow-Methods', implode(', ', config('datachat.cors.allowed_methods')))
                ->header('Access-Control-Allow-Headers', implode(', ', config('datachat.cors.allowed_headers')))
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
            ->header('Access-Control-Allow-Methods', implode(', ', config('datachat.cors.allowed_methods')))
            ->header('Access-Control-Allow-Headers', implode(', ', config('datachat.cors.allowed_headers')));
    }

    protected function getAllowedOrigin(Request $request): string
    {
        $origin = $request->header('Origin');
        $allowed = config('datachat.cors.allowed_origins');

        if ($allowed === '*') {
            return '*';
        }

        if (is_array($allowed) && in_array($origin, $allowed)) {
            return $origin;
        }

        return config('app.url');
    }
}