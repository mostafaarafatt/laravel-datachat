<?php

namespace Mostafaarafat\DataChat\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Mostafaarafat\DataChat\Models\ChatConfig;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-DataChat-Key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'API key required',
                'message' => 'Please provide your DataChat API key in the X-DataChat-Key header'
            ], 401);
        }

        $config = ChatConfig::where('api_key', $apiKey)
            ->where('is_active', true)
            ->first();

        if (!$config) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is invalid or has been deactivated'
            ], 401);
        }

        // Attach config to request for use in controllers
        $request->attributes->set('datachat_config', $config);
        $request->merge(['datachat_config' => $config]);

        return $next($request);
    }
}