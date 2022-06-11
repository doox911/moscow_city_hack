<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleNotMiddleware {
  public function handle($request, Closure $next, $role) {
    if (Auth::guest()) {
      throw UnauthorizedException::notLoggedIn();
    }

    $roles = is_array($role)
      ? $role
      : explode('|', $role);

    if (Auth::user()->hasAnyRole($roles)) {
      throw UnauthorizedException::forRoles($roles);
    }

    return $next($request);
  }
}
