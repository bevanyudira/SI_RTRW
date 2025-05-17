<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Iuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IuranController extends Controller
{
    /**
     * @OA\Get(
     *     path="/iuran",
     *     summary="Get RT and RW Iuran Data for the authenticated user",
     *     tags={"Iuran"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all iuran data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all iuran data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="rt",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=6),
     *                         @OA\Property(property="rt_id", type="integer", example=1),
     *                         @OA\Property(property="rw_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="name", type="string", example="fsfs"),
     *                         @OA\Property(property="value", type="integer", example=200000),
     *                         @OA\Property(property="month", type="string", example="February"),
     *                         @OA\Property(property="variance", type="string", example="monthly"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T07:12:53.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T07:50:11.000000Z")
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="rw",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                         @OA\Property(property="rw_id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Labore dolorem."),
     *                         @OA\Property(property="value", type="integer", example=40000),
     *                         @OA\Property(property="month", type="string", example="August"),
     *                         @OA\Property(property="variance", type="string", example="additional"),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T07:12:52.000000Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T07:12:52.000000Z")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function index()
    {
        $warga = auth()->user()->warga;
        $rt = $warga->rt;
        $iuranRt = $rt->iurans;
        $iuranRw = $rt->rw->iurans;
        $iuran = ["rt" => $iuranRt, "rw" => $iuranRw];
        return ResponseTemplate::send('Success retrieve all iuran data', $iuran, 200);
    }

    /**
     * @OA\Post(
     *     path="/iuran",
     *     summary="Store new Iuran (RT or RW)",
     *     tags={"Iuran"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "month", "value", "variance"},
     *             @OA\Property(property="rw_id", type="integer", nullable=true, example=1, description="ID of RW (required if rt_id not provided)"),
     *             @OA\Property(property="rt_id", type="integer", nullable=true, example=1, description="ID of RT (required if rw_id not provided)"),
     *             @OA\Property(property="name", type="string", example="Iuran Bulanan", description="Name of the iuran"),
     *             @OA\Property(property="month", type="string", example="May", enum={
     *                 "January","February","March","April","May","June","July","August","September","October","November","December"
     *             }),
     *             @OA\Property(property="value", type="integer", example=50000, minimum=1000, maximum=1000000),
     *             @OA\Property(property="variance", type="string", example="monthly", enum={"monthly","additional"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store iuran",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store iuran"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="rw_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="name", type="string", example="Iuran Bulanan"),
     *                 @OA\Property(property="value", type="integer", example=50000),
     *                 @OA\Property(property="month", type="string", example="May"),
     *                 @OA\Property(property="variance", type="string", example="monthly"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T08:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T08:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request (validation failed or business logic error)",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message from server"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rw_id' => 'integer|exists:rws,id',
            'rt_id' => 'integer|exists:rts,id',
            'name' => 'required|string',
            'month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'value' => 'required|integer|min:1000|max:1000000',
            'variance' => 'required|string|in:monthly,additional'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        if (!$request->rw_id && !$request->rt_id) {
            return ResponseTemplate::send('Give at least one RT or RW ID', null, 400);
        }
        if ($request->rw_id && $request->rt_id) {
            return ResponseTemplate::send('Give only one RT or RW ID', null, 400);
        }

        try {
            $iuran = Iuran::create($request->all());
            return ResponseTemplate::send('Success store iuran', $iuran, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/iuran/{id}",
     *     summary="Get iuran detail by ID",
     *     tags={"Iuran"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the iuran",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve iuran data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve iuran data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="rw_id", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="name", type="string", example="Iuran Bulanan"),
     *                 @OA\Property(property="month", type="string", example="May"),
     *                 @OA\Property(property="value", type="integer", example=50000),
     *                 @OA\Property(property="variance", type="string", example="monthly"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T08:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T08:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iuran not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed retrieve iuran data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */


    public function show(string $id)
    {
        $iuran = Iuran::find($id);
        if ($iuran) {
            return ResponseTemplate::send('Success retrieve iuran data', $iuran, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve iuran data', null, 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/iuran/{id}",
     *     summary="Update iuran data by ID",
     *     tags={"Iuran"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the iuran to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "month", "value", "variance"},
     *             @OA\Property(property="name", type="string", example="Iuran Keamanan"),
     *             @OA\Property(property="month", type="string", example="May"),
     *             @OA\Property(property="value", type="integer", example=50000),
     *             @OA\Property(property="variance", type="string", example="monthly")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update iuran data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update iuran data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Iuran Keamanan"),
     *                 @OA\Property(property="month", type="string", example="May"),
     *                 @OA\Property(property="value", type="integer", example=50000),
     *                 @OA\Property(property="variance", type="string", example="monthly"),
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
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Authorization failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="You're not authorizated"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iuran not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Iuran not found"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Server error"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'month' => 'required|string|in:January,February,March,April,May,June,July,August,September,October,November,December',
            'value' => 'required|integer|min:1000|max:1000000',
            'variance' => 'required|string|in:monthly,additional'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        $iuran = Iuran::find($id);
        if (!$iuran) {
            return ResponseTemplate::send('Iuran not found', null, 404);
        }

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

        try {
            $iuran->update($request->all());
            return ResponseTemplate::send('Success update iuran data', $iuran, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/iuran/{id}",
     *     summary="Delete iuran by ID",
     *     tags={"Iuran"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the iuran to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success delete iuran data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success delete iuran data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="You're not authorizated"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iuran not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed delete iuran data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Delete failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Delete failed message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $iuran = Iuran::find($id);
        if ($iuran) {
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
            try {
                $iuran->delete();
                return ResponseTemplate::send('Success delete iuran data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete iuran data', null, 404);
        }
    }
}
