<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/rt",
     *     summary="Get all RT data",
     *     tags={"RT"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved all RT data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all rt data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="rw_id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Emory Blanda"),
     *                     @OA\Property(property="balance", type="integer", example=1000000),
     *                     @OA\Property(property="bank", type="string", example="887683261999012"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $rt = Rt::all();
        return ResponseTemplate::send('Success retrieve all rt data', $rt, 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/rt",
     *     summary="Store new RT data",
     *     tags={"RT"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rw_Id","name", "bank"},
     *             @OA\Property(property="rw_id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Emory Blanda"),
     *             @OA\Property(property="bank", type="string", example="887683261999012")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully stored RT data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store rt"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Emory Blanda"),
     *                 @OA\Property(property="balance", type="integer", example=1000000),
     *                 @OA\Property(property="bank", type="string", example="887683261999012"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object", example={"name": {"The nama rt field is required."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="SQLSTATE[23000]: Integrity constraint violation..."),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rw_id' => 'required|integer|exists:rws,id',
            'name' => 'required|string',
            'bank' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        try {
            $data = $request->all();
            $rt = Rt::create($data);
            return ResponseTemplate::send('Success store rt', $rt, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/rt/{id}",
     *     summary="Get RT data by ID",
     *     tags={"RT"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the RT",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved RT data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve rt data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Emory Blanda"),
     *                 @OA\Property(property="balance", type="integer", example=1000000),
     *                 @OA\Property(property="bank", type="string", example="887683261999012"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="RT data not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed retrieve rt data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $rt = Rt::find($id);
        if ($rt) {
            return ResponseTemplate::send('Success retrieve rt data', $rt, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve rt data', null, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/rt/{id}",
     *     summary="Update RT data",
     *     tags={"RT"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the RT to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "bank"},
     *             @OA\Property(property="rw_id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="Emory Blanda Updated"),
     *             @OA\Property(property="bank", type="number", example=887683261999012)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully updated RT data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update rt data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Emory Blanda Updated"),
     *                 @OA\Property(property="bank", type="string", example="887683261999012"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-04T09:10:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Your input is invalid"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="RT not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="rt not found"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'rw_id' => 'required|integer|exists:rws,id',
            'name' => 'required|string',
            'bank' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $rt = Rt::find($id);
        if (!$rt) {
            return ResponseTemplate::send('rt not found', null, 404);
        }
        try {
            $data = $request->all();
            $rt->update($data);
            return ResponseTemplate::send('Success update rt data', $rt, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/rt/{id}",
     *     summary="Delete RT data",
     *     tags={"RT"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the RT to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully deleted RT data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success delete rt data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="RT not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed delete rt data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to delete RT due to server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Error message"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $rt = Rt::find($id);
        if ($rt) {
            try {
                $rt->delete();
                return ResponseTemplate::send('Success delete rt data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete rt data', null, 404);
        }
    }
}
