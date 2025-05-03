<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Login success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="1|longapitokenstring")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User not activated",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="You are unactivated"),
     *             @OA\Property(property="data", type="object", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invalid login credentials",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Login failed"),
     *             @OA\Property(property="data", type="object", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal"),
     *             @OA\Property(property="errors", type="object", example={
     *                 "email": {"The email field is required."},
     *                 "password": {"The password field is required."}
     *             })
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseTemplate::send('Login failed', null, 404);
        }

        if ($user->aktivasi !== 'Activated') {
            return ResponseTemplate::send('You are unactivated', null, 403);
        }
        if ($user && Hash::check($request->password, $user->password)) {
            $user->tokens()->delete();
            $data = [
                "token" => $user->createToken($request['email'])->plainTextToken
            ];
            return ResponseTemplate::send('Login success', $data, 200);
        }
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logout the authenticated user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Logout success"),
     *             @OA\Property(property="data", type="object", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
     *             @OA\Property(property="data", type="object", nullable=true, example=null)
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return ResponseTemplate::send('Logout success', null, 200);
        } catch (\Exception $e) {
            return ResponseTemplate::send($e->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Get the authenticated user's profile",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved profile",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve your profile"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="warga_id", type="string", example="3026300139170900"),
     *                  @OA\Property(property="email", type="string", format="email", example="fritsch.raheem@nicolas.biz"),
     *                  @OA\Property(property="no_hp", type="string", example="+1-602-662-8653"),
     *                  @OA\Property(property="role", type="string", example="Super_Admin"),
     *                  @OA\Property(property="aktivasi", type="string", example="Activated"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z")
     *             )
     *         )
     *     )
     * )
     */

    public function me(Request $request)
    {
        return ResponseTemplate::send('Success retrieve your profile', $request->user(), 200);
    }
}
