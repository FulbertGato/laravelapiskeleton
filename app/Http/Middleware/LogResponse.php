<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogResponse
{
    /**
     * Handle an outgoing response.
     *
     * @param Request $request
     * @param \Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        Log::info('OUTGOING RESPONSE', [
            'status' => $response->status(),
            'content' => $response->content(),
        ]);
        return $response;
    }
}
