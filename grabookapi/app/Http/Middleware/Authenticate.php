<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }
		try {
            $user = JWTAuth::parseToken()->authenticate();
            Auth::setUser($user);
        }
        catch (TokenExpiredException $e) {

            return response()->json([
                'error' => 'Token Expired!',
                'statusCode' => (int)401
            ], 401);

        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => 'Not Authorized!',
                'statusCode' => (int)401
            ], 401);

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Not Authorized!',
                'statusCode' => (int)401
            ], 401);
        }
        return $next($request);
    }
}
