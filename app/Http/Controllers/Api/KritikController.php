<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Kritik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KritikController extends Controller
{
    /**
     * @OA\Get(
     *     path="/kritik",
     *     summary="Get all kritik data",
     *     description="Retrieve all kritik data from the database",
     *     operationId="getAllKritik",
     *     tags={"Kritik"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all kritik data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all kritik data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="rt_id", type="integer", nullable=true, example=4),
     *                     @OA\Property(property="rw_id", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="text", type="string", example="Halo ini kritik"),
     *                     @OA\Property(property="status", type="string", example="yet"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z")
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
        $kritik = Kritik::all();
        return ResponseTemplate::send('Success retrieve all kritik data', $kritik, 200);
    }

    /**
     * @OA\Post(
     *     path="/kritik",
     *     summary="Store new kritik",
     *     description="Create a new kritik record with either RT or RW ID",
     *     operationId="storeKritik",
     *     tags={"Kritik"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rt_id", type="integer", example=4, nullable=true),
     *             @OA\Property(property="rw_id", type="integer", example=null, nullable=true),
     *             @OA\Property(property="text", type="string", example="Ini adalah kritik")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store kritik",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store kritik"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", example=4, nullable=true),
     *                 @OA\Property(property="rw_id", type="integer", example=null, nullable=true),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="text", type="string", example="Ini adalah kritik"),
     *                 @OA\Property(property="status", type="string", example="yet"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation or logic error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rw_id' => 'integer|exists:rws,id',
            'rt_id' => 'integer|exists:rts,id',
            'text' => 'required|string',
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
            $data = $request->all();
            $data["user_id"] = $request->user()->id;
            $kritik = Kritik::create($data);
            return ResponseTemplate::send('Success store kritik', $kritik, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/kritik/{id}",
     *     summary="Get a specific kritik by ID",
     *     description="Retrieve a single kritik entry by its ID",
     *     operationId="getKritikById",
     *     tags={"Kritik"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the kritik to retrieve",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve kritik data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve kritik data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="rt_id", type="integer", example=4, nullable=true),
     *                 @OA\Property(property="rw_id", type="integer", example=null, nullable=true),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="text", type="string", example="Halo ini kritik"),
     *                 @OA\Property(property="status", type="string", example="yet"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kritik not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed retrieve kritik data"),
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
        $kritik = Kritik::find($id);
        if ($kritik) {
            return ResponseTemplate::send('Success retrieve kritik data', $kritik, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve kritik data', null, 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/kritik/{id}",
     *     summary="Update kritik status",
     *     description="Update the status of a specific kritik entry",
     *     operationId="updateKritik",
     *     tags={"Kritik"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the kritik to update",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"yet", "done", "read"}, example="done")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update kritik data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update kritik data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="rt_id", type="integer", example=4, nullable=true),
     *                 @OA\Property(property="rw_id", type="integer", example=null, nullable=true),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="text", type="string", example="Halo ini kritik"),
     *                 @OA\Property(property="status", type="string", example="done"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:48:52.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:00:00.000000Z")
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
     *         response=404,
     *         description="Kritik not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="kritik not found"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:yet,done,read'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $kritik = Kritik::find($id);
        if (!$kritik) {
            return ResponseTemplate::send('kritik not found', null, 404);
        }

        try {
            $kritik->status = $request->status;
            $kritik->save();
            return ResponseTemplate::send('Success update kritik data', $kritik, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/kritik/{id}",
     *     summary="Delete kritik",
     *     description="Delete a kritik by ID",
     *     operationId="deleteKritik",
     *     tags={"Kritik"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the kritik to delete",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success delete kritik data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success delete kritik data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to delete kritik",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Some error message"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kritik not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed delete kritik data"),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $kritik = Kritik::find($id);
        if ($kritik) {
            try {
                $kritik->delete();
                return ResponseTemplate::send('Success delete kritik data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete kritik data', null, 404);
        }
    }
}
