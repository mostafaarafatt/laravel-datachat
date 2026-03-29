<?php

namespace Mostafaarafat\DataChat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Mostafaarafat\DataChat\Models\ChatConfig;

class ValidateOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var ChatConfig $config */
        $config = $request->get('datachat_config');
        $origin = $request->header('Origin');

        if ($origin && !$config->isOriginAllowed($origin)) {
            return response()->json([
                'error' => 'Origin not allowed',
                'message' => 'Requests from this domain are not permitted'
            ], 403);
        }

        return $next($request);
    }
}