<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * @OA\Get(
     *     path="/wallet",
     *     summary="Get all wallet transactions of authenticated user",
     *     description="Retrieve all wallet transaction records associated with the authenticated user.",
     *     operationId="getUserWallets",
     *     tags={"Wallet"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all wallets data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all wallets data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="user_id", type="integer", example=14),
     *                     @OA\Property(property="name", type="string", example="Top Up"),
     *                     @OA\Property(property="variance", type="string", example="inflow"),
     *                     @OA\Property(property="status", type="string", example="success"),
     *                     @OA\Property(property="value", type="integer", example=100000),
     *                     @OA\Property(property="before", type="integer", example=200000),
     *                     @OA\Property(property="after", type="integer", example=300000),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T18:37:12.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T18:37:12.000000Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */

    public function index()
    {
        $wallet = auth()->user()->wallets;
        return ResponseTemplate::send('Success retrieve all wallets data', $wallet, 200);
    }
    /**
     * @OA\Post(
     *     path="/wallet",
     *     summary="Store a new wallet transaction (Top Up)",
     *     description="Create a new wallet transaction of type inflow (Top Up) for the authenticated user.",
     *     operationId="storeWalletTransaction",
     *     tags={"Wallet"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status", "value"},
     *             @OA\Property(property="status", type="string", enum={"success", "failed"}, example="success"),
     *             @OA\Property(property="value", type="integer", example=100000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store wallet",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store wallet"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=14),
     *                 @OA\Property(property="name", type="string", example="Top Up"),
     *                 @OA\Property(property="variance", type="string", example="inflow"),
     *                 @OA\Property(property="status", type="string", example="success"),
     *                 @OA\Property(property="value", type="integer", example=100000),
     *                 @OA\Property(property="before", type="integer", example=200000),
     *                 @OA\Property(property="after", type="integer", example=300000),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T18:37:12.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T18:37:12.000000Z")
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
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:success,failed',
            'value' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $data = $request->all();
        $before = User::find(auth()->user()->id);
        $data["before"] = $before->balance;
        $before->balance = $before->balance + $request->value;
        $before->save();
        $data["after"] = $before->balance;
        $data["user_id"] = $before->id;
        $data["variance"] = "inflow";
        $data["name"] = "Top Up";
        try {
            $wallet = Wallet::create($data);
            return ResponseTemplate::send('Success store wallet', $wallet, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }
}
