<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Proker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/proker",
     *     tags={"Proker"},
     *     summary="Get all proker data",
     *     description="Retrieve all program kerja (proker) data. Requires bearer token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve all proker data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success retrieve all proker data"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="title", type="string", example="Assumenda hic beatae aut ut."),
     *                     @OA\Property(property="description", type="string", example="Quod molestiae iusto qui."),
     *                     @OA\Property(property="time", type="string", format="time", example="10:27:26"),
     *                     @OA\Property(property="date", type="string", format="date", example="1991-11-23"),
     *                     @OA\Property(property="location", type="string", example="41506 Durgan Squares Apt. 559"),
     *                     @OA\Property(property="image", type="string", example="programkerja.jpg"),
     *                     @OA\Property(property="rw_id", type="integer", example=1),
     *                     @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="status", type="string", example="progress"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-09T04:16:35.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-09T04:16:35.000000Z"),
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
        $proker = Proker::all();
        return ResponseTemplate::send('Success retrieve all proker data', $proker, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/proker",
     *     tags={"Proker"},
     *     summary="Store a new Proker",
     *     description="Create a new program kerja (proker). Requires bearer token. Either rw_id or rt_id must be provided, but not both.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "description", "date", "location"},
     *                 @OA\Property(property="rw_id", type="integer", example=1, nullable=true),
     *                 @OA\Property(property="rt_id", type="integer", example=null, nullable=true),
     *                 @OA\Property(property="title", type="string", example="Program Bersih Desa"),
     *                 @OA\Property(property="description", type="string", example="Aksi bersih-bersih lingkungan setiap bulan."),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-06-01"),
     *                 @OA\Property(property="time", type="string", format="time", example="08:00:00"),
     *                 @OA\Property(property="location", type="string", example="Balai RW 01, Dusun Maju"),
     *                 @OA\Property(property="image", type="string", format="binary", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success store proker",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success store proker"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Program Bersih Desa"),
     *                 @OA\Property(property="description", type="string", example="Aksi bersih-bersih lingkungan setiap bulan."),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-06-01"),
     *                 @OA\Property(property="time", type="string", format="time", example="08:00:00"),
     *                 @OA\Property(property="location", type="string", example="Balai RW 01, Dusun Maju"),
     *                 @OA\Property(property="image", type="string", example="proker/image123.jpg"),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="status", type="string", example="progress"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T08:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T08:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input or both/none of RT-RW IDs provided"
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
            "title" => 'required|string',
            "description" => 'required|string',
            "date" => 'required|date',
            "location" => 'required|string',
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
            $proker = Proker::create($data);
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('proker');
                $proker->image = $path;
                $proker->save();
            }
            return ResponseTemplate::send('Success store proker', $proker, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/proker/{id}",
     *     tags={"Proker"},
     *     summary="Get Proker by ID",
     *     description="Retrieve a single Proker record by ID. Requires bearer token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Proker",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success retrieve proker data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success retrieve proker data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Program Bersih Desa"),
     *                 @OA\Property(property="description", type="string", example="Aksi bersih-bersih lingkungan setiap bulan."),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-06-01"),
     *                 @OA\Property(property="time", type="string", format="time", example="08:00:00"),
     *                 @OA\Property(property="location", type="string", example="Balai RW 01, Dusun Maju"),
     *                 @OA\Property(property="image", type="string", example="proker/image123.jpg"),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="status", type="string", example="progress"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T08:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T08:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Failed retrieve proker data"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function show(string $id)
    {
        $proker = Proker::find($id);
        if ($proker) {
            return ResponseTemplate::send('Success retrieve proker data', $proker, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve proker data', null, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/proker/{id}",
     *     tags={"Proker"},
     *     summary="Update Proker by ID",
     *     description="Update an existing Proker. Requires bearer token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Proker to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "description", "date", "location", "status"},
     *                 @OA\Property(property="title", type="string", example="Program Kebersihan RW"),
     *                 @OA\Property(property="description", type="string", example="Pembersihan lingkungan sekitar RW."),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
     *                 @OA\Property(property="location", type="string", example="Balai RW 03"),
     *                 @OA\Property(property="status", type="string", enum={"progress", "done"}, example="done"),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success update proker data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success update proker data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Program Kebersihan RW"),
     *                 @OA\Property(property="description", type="string", example="Pembersihan lingkungan sekitar RW."),
     *                 @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
     *                 @OA\Property(property="location", type="string", example="Balai RW 03"),
     *                 @OA\Property(property="status", type="string", example="done"),
     *                 @OA\Property(property="image", type="string", example="proker/image456.jpg"),
     *                 @OA\Property(property="rw_id", type="integer", example=1),
     *                 @OA\Property(property="rt_id", type="integer", nullable=true, example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proker not found"
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
            'rw_id' => 'exists:rws,id',
            'rt_id' => 'exists:rts,id',
            "title" => 'required|string',
            "description" => 'required|string',
            "date" => 'required|date',
            "location" => 'required|string',
            "status" => 'required|string|in:progress,done'
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
        $proker = Proker::find($id);
        if (!$proker) {
            return ResponseTemplate::send('proker not found', null, 404);
        }
        try {
            $data = $request->all();
            $proker->update($data);
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('proker');
                $proker->image = $path;
                $proker->save();
            }
            return ResponseTemplate::send('Success update proker data', $proker, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/proker/{id}",
     *     tags={"Proker"},
     *     summary="Delete Proker by ID",
     *     description="Delete a specific Proker by ID. Requires bearer token.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Proker to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success delete proker data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Success delete proker data"),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Proker not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Delete error or image removal failed silently"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $proker = Proker::find($id);
        if ($proker) {
            try {
                if ($proker->image) {
                    try {
                        Storage::delete($proker->image);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
                $proker->delete();
                return ResponseTemplate::send('Success delete proker data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete proker data', null, 404);
        }
    }
}
