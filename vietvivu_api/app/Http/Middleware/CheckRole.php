<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn chưa đăng nhập'
            ], 401);
        }

        // Nếu role không tồn tại trong DB ( bị xóa cứng)
        if (!\App\Models\Role::where('id', $user->role_id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Role của bạn không còn tồn tại trong hệ thống.'
            ], 403);
        }

        if (!in_array($user->role_id, $roles)) {
            return response()->json([
                'status'  => false,
                'message' => 'Bạn không có quyền truy cập'
            ], 403);
        }

        return $next($request);
    }
}
