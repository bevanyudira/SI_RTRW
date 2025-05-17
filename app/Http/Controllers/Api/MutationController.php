<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Mutation;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MutationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/mutasi",
     *     summary="Get mutation data for RT and RW",
     *     description="Retrieve all mutation data related to the authenticated user's RT and its associated RW.",
     *     operationId="getMutations",
     *     tags={"Mutasi"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all mutation data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="rt",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=6),
     *                         @OA\Property(property="rw_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="rt_id", type="integer", example=1),
     *                         @OA\Property(property="variance", type="string", example="outflow"),
     *                         @OA\Property(property="value", type="integer", example=89700),
     *                         @OA\Property(property="before", type="integer", example=70900),
     *                         @OA\Property(property="after", type="integer", example=10700),
     *                         @OA\Property(property="notes", type="string", example="Vitae sed est repellendus repellat."),
     *                         @OA\Property(property="image", type="string", example="voluptas"),
     *                         @OA\Property(property="date", type="string", format="date", example="1998-05-27"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T11:08:12.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T11:08:12.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="rw",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="rw_id", type="integer", example=1),
     *                         @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="variance", type="string", example="inflow"),
     *                         @OA\Property(property="value", type="integer", example=41600),
     *                         @OA\Property(property="before", type="integer", example=19000),
     *                         @OA\Property(property="after", type="integer", example=10200),
     *                         @OA\Property(property="notes", type="string", example="Odit qui non qui."),
     *                         @OA\Property(property="image", type="string", example="provident"),
     *                         @OA\Property(property="date", type="string", format="date", example="2002-08-22"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T11:08:12.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T11:08:12.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */


    public function index()
    {
        $warga = auth()->user()->warga;
        $rt = $warga->rt;
        $mutationRt = $rt->mutations;
        $mutationRw = $rt->rw->mutations;
        $mutation = ["rt" => $mutationRt, "rw" => $mutationRw];
        return ResponseTemplate::send('Success retrieve all mutation data', $mutation, 200);
    }
    /**
     * @OA\Post(
     *     path="/mutasi",
     *     summary="Create a new mutation (inflow/outflow)",
     *     description="Store a new mutation related to the authenticated user's RT or RW.",
     *     operationId="storeMutation",
     *     tags={"Mutasi"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"value", "variance", "date"},
     *                 @OA\Property(property="value", type="integer", format="int32", example=50000, minimum=1000, maximum=1000000),
     *                 @OA\Property(property="variance", type="string", enum={"inflow", "outflow"}, example="inflow"),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-05-17"),
     *                 @OA\Property(property="notes", type="string", example="Donation from RW residents"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store mutation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store mutation"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=10),
     *                 @OA\Property(property="rw_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="variance", type="string", example="inflow"),
     *                 @OA\Property(property="value", type="integer", example=50000),
     *                 @OA\Property(property="before", type="integer", example=250000),
     *                 @OA\Property(property="after", type="integer", example=300000),
     *                 @OA\Property(property="notes", type="string", example="Donation from RW residents"),
     *                 @OA\Property(property="image", type="string", example="bukti/example.png"),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-05-17"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T13:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Your input is invalid"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You're not authorizated"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|integer|min:1000|max:1000000',
            'variance' => 'required|string|in:inflow,outflow',
            'date' => 'required|date',
            'notes' => 'string',
            'image' => 'image|mimes:jpeg,png,jpg,svg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $user = auth()->user();
        $data = $request->all();
        $warga = $user->warga;
        if (str_ends_with($user->role, 'RT')) {
            $before = Rt::find($warga->rt_id);
            $data["rt_id"] = $before->id;
        } elseif (str_ends_with($user->role, 'RW')) {
            $before = Rw::find($warga->rt->rw->id);
            $data["rw_id"] = $before->id;
        } else {
            return ResponseTemplate::send('You\'re not authorizated', null, 403);
        }

        if ($request->variance == 'inflow') {
            $data["before"] = $before->balance;
            $before->balance = $before->balance + $request->value;
        } else {
            $data["before"] = $before->balance;
            $before->balance = $before->balance - $request->value;
        }
        $before->save();
        $data["after"] = $before->balance;
        if ($request->hasFile('image')) {
            $image = Storage::disk('public')->put('bukti', $request->file('image$image = '));
            $data["image"] = $image;
        }
        try {
            $mutation = Mutation::create($data);
            return ResponseTemplate::send('Success store mutation', $mutation, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/mutasi/{id}",
     *     summary="Get mutation detail by ID",
     *     description="Retrieve specific mutation data by ID for authenticated users.",
     *     operationId="getMutationById",
     *     tags={"Mutasi"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Mutation ID",
     *         @OA\Schema(type="string", example="12")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve mutation data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="rw_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="variance", type="string", example="inflow"),
     *                 @OA\Property(property="value", type="integer", example=25000),
     *                 @OA\Property(property="before", type="integer", example=150000),
     *                 @OA\Property(property="after", type="integer", example=175000),
     *                 @OA\Property(property="notes", type="string", example="Monthly donation"),
     *                 @OA\Property(property="image", type="string", example="bukti/donation.png"),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-05-17"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T13:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Failed retrieve mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed retrieve mutation data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function show(string $id)
    {
        $mutation = Mutation::find($id);
        if ($mutation) {
            return ResponseTemplate::send('Success retrieve mutation data', $mutation, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve mutation data', null, 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/mutasi/{id}",
     *     summary="Update mutation by ID",
     *     description="Update a mutation data. Only authorized users (RT/RW/Super Admin) can update mutations.",
     *     operationId="updateMutation",
     *     tags={"Mutasi"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Mutation ID",
     *         @OA\Schema(type="string", example="12")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value", "variance", "date"},
     *             @OA\Property(property="value", type="integer", example=25000, description="Nominal mutation value"),
     *             @OA\Property(property="variance", type="string", enum={"inflow", "outflow"}, example="outflow"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-05-17"),
     *             @OA\Property(property="notes", type="string", example="Updated description or notes")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update mutation data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="variance", type="string", example="outflow"),
     *                 @OA\Property(property="value", type="integer", example=25000),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-05-17"),
     *                 @OA\Property(property="notes", type="string", example="Updated description or notes"),
     *                 @OA\Property(property="rw_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="before", type="integer", example=150000),
     *                 @OA\Property(property="after", type="integer", example=125000),
     *                 @OA\Property(property="image", type="string", example="bukti/outflow.jpg"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T13:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T14:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Your input is invalid",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", example={"value": {"The value must be at least 1000."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You're not authorizated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mutation not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|integer|min:1000|max:1000000',
            'variance' => 'required|string|in:inflow,outflow',
            'date' => 'required|date',
            'notes' => 'string'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $mutation = Mutation::find($id);
        if (!$mutation) {
            return ResponseTemplate::send('Mutation not found', null, 404);
        }

        $user = auth()->user();
        $role = $user->role;
        $warga = $user->warga;
        if ($role === 'Warga') {
            return ResponseTemplate::send('You\'re not authorizated', null, 403);
        }

        if ($mutation->rw_id) {
            if (!in_array($role, ['Admin_RW', 'Ketua_RW', 'Super_Admin'])) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
            if ($role !== 'Super_Admin' && $warga->rw_id !== $mutation->rw_id) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
        }

        if ($mutation->rt_id) {
            if (!in_array($role, ['Admin_RT', 'Ketua_RT', 'Super_Admin'])) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
            if ($role !== 'Super_Admin' && $warga->rt_id !== $mutation->rt_id) {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }
        }

        try {
            $mutation->update($request->all());
            return ResponseTemplate::send('Success update mutation data', $mutation, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/mutasi/{id}",
     *     summary="Delete mutation by ID",
     *     description="Delete a mutation record. Only authorized users (RT/RW/Super Admin) can perform this action.",
     *     operationId="deleteMutation",
     *     tags={"Mutasi"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Mutation ID",
     *         @OA\Schema(type="string", example="12")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success delete mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success delete mutation data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="You're not authorizated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="You're not authorizated"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Failed delete mutation data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed delete mutation data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
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

    public function destroy(string $id)
    {
        $mutation = Mutation::find($id);
        if ($mutation) {
            $user = auth()->user();
            $role = $user->role;
            $warga = $user->warga;
            if ($role === 'Warga') {
                return ResponseTemplate::send('You\'re not authorizated', null, 403);
            }

            if ($mutation->rw_id) {
                if (!in_array($role, ['Admin_RW', 'Ketua_RW', 'Super_Admin'])) {
                    return ResponseTemplate::send('You\'re not authorizated', null, 403);
                }
                if ($role !== 'Super_Admin' && $warga->rw_id !== $mutation->rw_id) {
                    return ResponseTemplate::send('You\'re not authorizated', null, 403);
                }
            }

            if ($mutation->rt_id) {
                if (!in_array($role, ['Admin_RT', 'Ketua_RT', 'Super_Admin'])) {
                    return ResponseTemplate::send('You\'re not authorizated', null, 403);
                }
                if ($role !== 'Super_Admin' && $warga->rt_id !== $mutation->rt_id) {
                    return ResponseTemplate::send('You\'re not authorizated', null, 403);
                }
            }
            try {
                $mutation->delete();
                return ResponseTemplate::send('Success delete mutation data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete mutation data', null, 404);
        }
    }
}
