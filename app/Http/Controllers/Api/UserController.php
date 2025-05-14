<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Get the authenticated user's profile",
     *     tags={"User"},
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
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                  @OA\Property(property="warga", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="nik", type="string", example="66662387461305"),
     *                     @OA\Property(property="rt_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="address", type="string", example="Jl. Kebon Jeruk No. 45"),
     *                     @OA\Property(property="birth", type="string", format="date", example="2005-03-17"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:56:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T11:26:49.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function me(Request $request)
    {
        $user = User::with('warga')->where('id', $request->user()->id)->first();
        return ResponseTemplate::send('Success retrieve your profile', $user, 200);
    }

    /**
     * @OA\Put(
     *     path="/me",
     *     summary="Update current user's profile",
     *     description="Update the authenticated user's profile and related warga data",
     *     operationId="profileUpdate",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "email", "phone", "birth"},
     *             @OA\Property(property="name", type="string", example="John Doe", description="Full name (min 8 characters)"),
     *             @OA\Property(property="address", type="string", example="Jl. Kebon Jeruk No. 45", description="Full address (min 8 characters)"),
     *             @OA\Property(property="email", type="string", format="email", example="cobauser@gmail.com"),
     *             @OA\Property(property="phone", type="string", example="6289646055420"),
     *             @OA\Property(property="birth", type="string", format="date", example="2005-03-17")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update your profile"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=15),
     *                 @OA\Property(property="warga_id", type="integer", example=4),
     *                 @OA\Property(property="email", type="string", example="cobauser@gmail.com"),
     *                 @OA\Property(property="phone", type="string", example="6289646055420"),
     *                 @OA\Property(property="role", type="string", example="Warga"),
     *                 @OA\Property(property="activated", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:50:35.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T11:26:49.000000Z"),
     *                 @OA\Property(property="warga", type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="nik", type="string", example="66662387461305"),
     *                     @OA\Property(property="rt_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="address", type="string", example="Jl. Kebon Jeruk No. 45"),
     *                     @OA\Property(property="birth", type="string", format="date", example="2005-03-17"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:56:22.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T11:26:49.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", example={"email": {"The email must be a valid email address."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Some error message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:8',
            'address' => 'required|string|min:8',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'birth' => 'required|date'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $warga = $request->user()->warga;
        $warga->update($request->only(['address', 'birth', 'name']));
        $request->user()->update($request->except(['address', 'birth', 'name']));
        $user = User::with('warga')->where('id', $request->user()->id)->first();
        return ResponseTemplate::send('Success update your profile', $user, 200);
    }

    /**
     * @OA\Post(
     *     path="/request",
     *     summary="Create new user",
     *     description="Create a new user from existing Warga by providing NIK and account credentials",
     *     operationId="createUser",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nik", "email", "phone", "password", "role"},
     *             @OA\Property(property="nik", type="string", example="12345678901234", description="NIK from warga table (must exist)"),
     *             @OA\Property(property="email", type="string", format="email", example="cobauser@gmail.com"),
     *             @OA\Property(property="phone", type="string", example="6289646055420"),
     *             @OA\Property(property="password", type="string", format="password", example="strongpassword123"),
     *             @OA\Property(property="role", type="string", enum={"Admin_RT", "Ketua_RT", "Admin_RW", "Ketua_RW", "Warga"}, example="Warga")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store user"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=16),
     *                 @OA\Property(property="warga_id", type="integer", example=11),
     *                 @OA\Property(property="email", type="string", example="cobauser@gmail.com"),
     *                 @OA\Property(property="phone", type="string", example="6289646055420"),
     *                 @OA\Property(property="role", type="string", example="Warga"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T11:23:45.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T11:23:45.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", example={"nik": {"The selected nik is invalid."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Some error message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */

    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|min_digits:14|exists:wargas,nik',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:Admin_RT,Ketua_RT,Admin_RW,Ketua_RW,Warga'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $warga = Warga::where('nik', $request->nik)->first();
        try {
            $data = $request->all();
            unset($data["nik"]);
            $data["warga_id"] = $warga->id;
            $data["password"] = Hash::make($data["password"]);
            $user = User::create($data);
            return ResponseTemplate::send('Success store user', $user, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/activate",
     *     summary="Activate user account",
     *     description="Activate a user account using NIK",
     *     operationId="activateUser",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nik"},
     *             @OA\Property(property="nik", type="string", example="12345678901234", description="NIK (must exist in wargas table)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success activate user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success activate user"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=15),
     *                 @OA\Property(property="warga_id", type="integer", example=4),
     *                 @OA\Property(property="email", type="string", example="kamaluddin.arsyad17@gmail.com"),
     *                 @OA\Property(property="phone", type="string", example="6289646055430"),
     *                 @OA\Property(property="role", type="string", example="Warga"),
     *                 @OA\Property(property="activated", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:50:35.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T11:21:21.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", example={"nik": {"The selected nik is invalid."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Some error message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */

    public function activateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|min_digits:14|exists:wargas,nik',
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        try {
            $user = Warga::where('nik', $request->nik)->first()->user;
            $user->update(["activated" => true]);
            return ResponseTemplate::send('Success activate user', $user, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }
}
