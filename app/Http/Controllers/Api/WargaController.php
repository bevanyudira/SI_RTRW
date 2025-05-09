<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseTemplate;
use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Warga",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="nik", type="string", example="3026300139170900"),
 *     @OA\Property(property="rt_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Magdalen Gleichner V"),
 *     @OA\Property(property="birth", type="date", example="2022-01-01"),
 *     @OA\Property(property="address", type="string", example="1626 Jast Keys Apt. 210\nMagnusside, ND 33533"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-03T20:22:05.000000Z")
 * )
 */

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/warga",
     *     summary="Get all warga data",
     *     tags={"Warga"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved all warga data",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success retrieve all warga data"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Warga")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $warga = Warga::all();
        return ResponseTemplate::send('Success retrieve all warga data', $warga, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|min_digits:15',
            'rt_id' => 'required|numeric|exists:rts,id',
            'name' => 'required|string|min:5',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }

        try {
            $data = $request->all();
            $warga = Warga::create($data);
            return ResponseTemplate::send('Success store warga', $warga, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Post(
     *     path="/warga",
     *     summary="Store new warga",
     *     tags={"Warga"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nik", "rt_id", "name", "address"},
     *             @OA\Property(property="nik", type="string", example="3026300139170900"),
     *             @OA\Property(property="birth", type="date", example="2022-01-01"),
     *             @OA\Property(property="rt_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Magdalen Gleichner V"),
     *             @OA\Property(property="address", type="string", example="1626 Jast Keys Apt. 210\nMagnusside, ND 33533")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warga stored successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success store warga"),
     *             @OA\Property(property="data", ref="#/components/schemas/Warga")
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
     *             @OA\Property(property="data", type="object", example={"nik": {"The nik must be at least 15 digits."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="SQLSTATE[23000]: Integrity constraint violation: ..."),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */

    public function show(string $id)
    {
        $warga = Warga::find($id);
        if ($warga) {
            return ResponseTemplate::send('Success retrieve warga data', $warga, 200);
        } else {
            return ResponseTemplate::send('Failed retrieve warga data', null, 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/warga/{id}",
     *     summary="Update existing warga data",
     *     tags={"Warga"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the warga to update",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nik", "rt_id", "name", "address","birth"},
     *             @OA\Property(property="nik", type="string", example="3026300139170900"),
     *             @OA\Property(property="birth", type="date", example="2022-01-01"),
     *             @OA\Property(property="rt_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Magdalen Gleichner V"),
     *             @OA\Property(property="address", type="string", example="1626 Jast Keys Apt. 210\nMagnusside, ND 33533")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warga updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success update warga data"),
     *             @OA\Property(property="data", ref="#/components/schemas/Warga")
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
     *             @OA\Property(property="data", type="object", example={"nik": {"The nik must be at least 15 digits."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Warga not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Warga not found"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="SQLSTATE[23000]: Integrity constraint violation: ..."),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|min_digits:15',
            'rt_id' => 'required|numeric|exists:rts,id',
            'name' => 'required|string|min:5',
            'address' => 'required|string',
            'birth' => 'required|date'
        ]);

        if ($validator->fails()) {
            return ResponseTemplate::send('Your input is invalid', $validator->messages(), 400);
        }
        $warga = Warga::find($id);
        if (!$warga) {
            return ResponseTemplate::send('Warga not found', null, 404);
        }
        try {
            $data = $request->all();
            $warga->update($data);
            return ResponseTemplate::send('Success update warga data', $warga, 200);
        } catch (\Throwable $th) {
            return ResponseTemplate::send($th->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/warga/{id}",
     *     summary="Delete a warga",
     *     tags={"Warga"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the warga to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Warga deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success delete warga data"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Warga not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="message", type="string", example="Failed delete warga data"),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=500),
     *             @OA\Property(property="message", type="string", example="SQLSTATE[23000]: Integrity constraint violation: ..."),
     *             @OA\Property(property="data", type="null", example=null)
     *         )
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $warga = Warga::find($id);
        if ($warga) {
            try {
                $warga->delete();
                return ResponseTemplate::send('Success delete warga data', null, 200);
            } catch (\Throwable $th) {
                return ResponseTemplate::send($th->getMessage(), null, 400);
            }
        } else {
            return ResponseTemplate::send('Failed delete warga data', null, 404);
        }
    }
}
