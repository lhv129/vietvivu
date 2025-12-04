<?php

namespace App\Api\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\RefreshToken;
use Illuminate\Http\Request;
use App\Api\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Api\Requests\RegisterRequest;
use App\Api\Controllers\BaseController;

class AuthController extends BaseController
{
    /**
     * Đăng ký
     */
    public function register(RegisterRequest $request)
    {
        // Tạo user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone_number'    => $request->phone_number,
            'sex'    => $request->sex,
        ]);

        /** @var \Tymon\JWTAuth\JWTGuard $jwt */
        $jwt = auth('api');

        // Tạo access token cho user mới
        $accessToken = $jwt->login($user);

        // Tạo refresh token
        $plainRefresh  = Str::random(64);
        $hashedRefresh = hash('sha256', $plainRefresh);

        RefreshToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token'      => $hashedRefresh,
                'expires_at' => now()->addDays(30),
                'revoked'    => false,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Đăng ký thành công',
            'data' => [
                'access_token'  => $accessToken,
                'refresh_token' => $plainRefresh,
                'user'          => $user,
                'token_type'    => 'bearer',
                'expires_in'    => $jwt->factory()->getTTL() * 60,
            ]
        ], 201);
    }


    /**
     * Đăng nhập
     */
    public function login(LoginRequest $request)
    {
        /** @var \Tymon\JWTAuth\JWTGuard $jwt */
        $jwt = auth('api');

        if (!$token = $jwt->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status'  => false,
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 401);
        }

        $user = $jwt->user();

        if (!Role::where('id', $user->role_id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Tài khoản của bạn đang sử dụng role không hợp lệ.'
            ], 403);
        }

        // Tạo refresh token
        $plainRefresh = Str::random(64);
        $hashedRefresh = hash('sha256', $plainRefresh);

        RefreshToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token'      => $hashedRefresh,
                'expires_at' => now()->addDays(30),
                'revoked'    => false,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'access_token'  => $token,
                'refresh_token' => $plainRefresh,
                'user'          => $user,
                'token_type'    => 'bearer',
                'expires_in'    => $jwt->factory()->getTTL() * 60,
            ]
        ]);
    }


    /**
     * Profile
     */
    public function me()
    {
        return response()->json([
            'status' => true,
            'data'   => auth('api')->user()
        ]);
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        $jwt = auth('api');
        $user = $jwt->user();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Không thể xác thực người dùng'
            ], 401);
        }

        // Thu hồi access token JWT
        /** @var \Tymon\JWTAuth\JWTGuard $jwt */
        $jwt->logout();

        // Thu hồi refresh token
        RefreshToken::where('user_id', $user->id)->update([
            'revoked' => true
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    /**
     * REFRESH TOKEN
     */
    public function refreshToken(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $hashed = hash('sha256', $request->refresh_token);

        $record = RefreshToken::where('token', $hashed)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json([
                'status'  => false,
                'message' => 'Refresh token không hợp lệ hoặc hết hạn'
            ], 401);
        }

        /** @var \Tymon\JWTAuth\JWTGuard $jwt */
        $jwt = auth('api');

        // Tạo access token mới
        $newAccessToken = $jwt->login($record->user);

        // (Quan trọng) Sinh refresh token mới
        $newPlain = Str::random(64);
        $newHash  = hash('sha256', $newPlain);

        // Update refresh token hiện tại
        $record->update([
            'token'      => $newHash,
            'expires_at' => now()->addDays(30),
        ]);

        return response()->json([
            'status' => true,
            'data'   => [
                'access_token'  => $newAccessToken,
                'refresh_token' => $newPlain,
                'token_type'    => 'bearer',
                'expires_in'    => $jwt->factory()->getTTL() * 60,
            ]
        ]);
    }

    /**
     * FORMAT RESPONSE TOKEN
     */
    protected function respondWithToken($accessToken, $refreshToken)
    {
        /** @var \Tymon\JWTAuth\JWTGuard $jwt */
        $jwt = auth('api');

        return response()->json([
            'status' => true,
            'data'   => [
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type'    => 'bearer',
                'expires_in'    => $jwt->factory()->getTTL() * 60,
            ]
        ]);
    }
}
