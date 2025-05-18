<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return ResponseTemplate::send('Login failed', null, 404);
        }

        if (!$user->activated) {
            return ResponseTemplate::send('You are unactivated', null, 403);
        }
        if ($user && Hash::check($request->password, $user->password)) {
            $user->tokens()->delete();
            $data = [
                "token" => $user->createToken($request['email'])->plainTextToken,
                "user" => $user
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
     * @OA\Post(
     *     path="/reset-password",
     *     summary="Request password reset token",
     *     tags={"Auth"},
     *     description="Send password reset token",
     *     operationId="resetPassword",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link has been created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link has been created")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *     security={}
     * )
     */

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email"
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ResponseTemplate::send('User not found', null, 404);
        }
        $token = Password::createToken($user);

        return ResponseTemplate::send('Success create password reset token', compact('user', 'token'), 200);
    }
    /**
     * @OA\Post(
     *     path="/change-password",
     *     summary="Change user password using reset token",
     *     tags={"Auth"},
     *     description="Change user password using reset token",
     *     operationId="changePassword",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="token", type="string", example="your-reset-token"),
     *             @OA\Property(property="password", type="string", format="password", example="newPassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newPassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password changed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password changed successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="email", type="string", example="user@example.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid token or input",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid token or user not found")
     *         )
     *     ),
     *     security={}
     * )
     */

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email",
            "token" => "required|string",
            "password" => "required|string|confirmed|min:8",
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $user = User::where('email', $request->email)->first();
        $token = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$user || !$token) {
            return ResponseTemplate::send('Invalid token or user not found', null, 400);
        }

        if (!Hash::check($request->token, $token->token)) {
            return ResponseTemplate::send('Invalid token', null, 400);
        }

        $user->update(["password" => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return ResponseTemplate::send('Password changed successfully', compact('user'), 200);
    }
}
