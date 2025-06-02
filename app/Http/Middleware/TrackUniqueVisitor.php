<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Visits;

class TrackUniqueVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = 'visitor_id';
        $visitorId = $request->cookie($cookieName);

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();
            cookie()->queue(cookie($cookieName, $visitorId, 60 * 24));
        }

        if (!Visits::where('identifier', $visitorId)->exists()) {
            Visits::create([
                'identifier' => $visitorId,
                'ip' => $request->ip(),
                'last_visit' => now(),
                'user_agent' => $request->userAgent(),
            ]);
        } else {
            Visits::where('identifier', $visitorId)->update([
                'last_visit' => now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}
