<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\DetailIuran;
use App\Models\Iuran;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PayIuranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/iuran/pay",
     *     summary="Get all pay records for authenticated user",
     *     description="Retrieve all payment (iuran) data associated with the authenticated user.",
     *     operationId="getUserPays",
     *     tags={"Pay"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all pays data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all pays data"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="iuran_id", type="integer", example=11),
     *                     @OA\Property(property="user_id", type="integer", example=14),
     *                     @OA\Property(property="status", type="string", example="done"),
     *                     @OA\Property(property="bank", type="integer", example=0),
     *                     @OA\Property(property="image", type="string", nullable=true, example=null),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T18:54:27.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T19:05:29.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $user = auth()->user();
        $pays = $user->pays;
        return ResponseTemplate::send('Success retrieve all pays data', $pays, 200);
    }
    /**
     * @OA\Post(
     *     path="/iuran/pay",
     *     summary="Submit iuran payment",
     *     description="Create a new iuran payment by authenticated user. If bank is not provided, the balance will be deducted directly.",
     *     operationId="storeIuranPay",
     *     tags={"Pay"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"iuran"},
     *                 @OA\Property(property="iuran", type="integer", example=11, description="ID of the iuran"),
     *                 @OA\Property(property="bank", type="integer", example=123456, description="Bank number (optional)"),
     *                 @OA\Property(property="image", type="string", format="binary", description="Proof of payment image (optional)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all pays data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all pays data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=14),
     *                 @OA\Property(property="iuran_id", type="integer", example=11),
     *                 @OA\Property(property="status", type="string", example="yet"),
     *                 @OA\Property(property="bank", type="string", example="000000"),
     *                 @OA\Property(property="image", type="string", example="iuran/image.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T18:54:27.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T19:05:29.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or insufficient balance",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="You need more balance on wallets")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iuran not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed retrieve iuran data")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iuran' => 'required|integer|exists:iurans,id',
            'bank' => 'numeric',
            'image' => 'image|mimes:jpeg,png,jpg,svg,webp|max:2048'
        ]);
        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $iuran = Iuran::find($request->iuran);
        if (!$iuran) {
            return ResponseTemplate::send('Failed retrieve iuran data', null, 404);
        }
        $user = User::find(auth()->user()->id);
        if ($user->balance < $iuran->value) {
            return ResponseTemplate::send('You need more balance on wallets', $iuran, 400);
        }
        $warga = $user->warga;
        if ($user->role == "Warga") {
            $target = $warga->rt;
            if ($iuran->rt_id != $target->id) {
                return ResponseTemplate::send('You\'re must not pay this', null, 400);
            }
        } elseif (str_ends_with('RT', $user->role)) {
            $target = $warga->rw;
            if ($iuran->rw_id != $target->id) {
                return ResponseTemplate::send('You\'re must not pay this', null, 400);
            }
        }
        $image = null;
        if ($request->hasFile('image')) {
            $image = Storage::disk('public')->put('iuran', $request->file('image$image = '));
        }
        if (!$request->bank) {
            $data["before"] = $user->balance;
            $user->balance = $user->balance - $iuran->value;
            $user->save();
            $data["after"] = $user->balance;
            $data["user_id"] = $user->id;
            $data["variance"] = "outflow";
            $data["name"] = $iuran->name;
            $data["value"] = $iuran->value;
            Wallet::create($data);
        }
        $detail = DetailIuran::create(["user_id" => $user->id, "iuran_id" => $iuran->id, "status" => "yet", "bank" => $request->bank ?? "000000", "image" => $image]);
        return ResponseTemplate::send('Success retrieve all pays data', $detail, 200);
    }
    /**
     * @OA\Put(
     *     path="/iuran/pay/{id}",
     *     summary="Update status of iuran payment",
     *     description="Update the status (done/failed) of a specific iuran payment by authorized users (RT/RW/Super_Admin)",
     *     operationId="updateIuranPay",
     *     tags={"Pay"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Detail Iuran to update",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"done", "failed"}, example="done")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update pay",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update pay"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="status", type="string", example="done"),
     *                 @OA\Property(property="iuran_id", type="integer", example=11),
     *                 @OA\Property(property="user_id", type="integer", example=14),
     *                 @OA\Property(property="bank", type="string", example="000000"),
     *                 @OA\Property(property="image", type="string", example="iuran/image.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="You're not authorizated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pay not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Pay not found")
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:done,failed',
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $pay = DetailIuran::find($id);
        if (!$pay) {
            return ResponseTemplate::send('Pay not found', null, 404);
        }
        $iuran = $pay->iuran;
        $user = auth()->user();
        $role = $user->role;
        $warga = $user->warga;
        if ($role === 'Warga') {
            return ResponseTemplate::send('You\'re not authorizated', null, 403);
        }

        if ($iuran->rw_id) {
            if (!in_array($role, ['Admin_RW', 'Ketua_RW', 'Super_Admin'])) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
            if ($role !== 'Super_Admin' && $warga->rw_id !== $iuran->rw_id) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
        }

        if ($iuran->rt_id) {
            if (!in_array($role, ['Admin_RT', 'Ketua_RT', 'Super_Admin'])) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
            if ($role !== 'Super_Admin' && $warga->rt_id !== $iuran->rt_id) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
        }
        $pay->update($request->all());
        return ResponseTemplate::send('Success update pay', $pay, 200);
    }
}
