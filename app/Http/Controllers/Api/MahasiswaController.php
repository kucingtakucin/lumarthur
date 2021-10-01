<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class MahasiswaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        echo "GET /all\n";
        echo "POST /insert\n";
        echo "PUT /update\n";
        echo "DELETE /delete\n";
    }

    public function all()
    {
        return response()->json([
            'status' => true,
            'message' => 'Found',
            'results' => Mahasiswa::paginate(100)
        ], Response::HTTP_FOUND);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'nim' => ['required', (isset($data['id']) ? Rule::unique('mahasiswa')->ignore($data['id']) : 'unique:mahasiswa,nim')],
            'nama' => ['required'],
            'prodi_id' => ['required'],
            'fakultas_id' => ['required'],
            'angkatan' => ['required'],
            'foto' => [Rule::requiredIf(function () {
                return request()->foto_old;
            })],
            'latitude' => ['required'],
            'longitude' => ['required']
        ], [
            'required' => ':attribute wajib diisi'
        ]);
    }

    public function insert()
    {
        $this->validator(request()->all())->validate();

        DB::beginTransaction();
        try {
            $mahasiswa = new Mahasiswa();
            $mahasiswa->fill(request()->all());
            $mahasiswa->created_by = auth()->id();
            $mahasiswa->save();
            DB::commit();
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'results' => null
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Created Successfuly',
            'results' => null
        ], Response::HTTP_CREATED);
    }

    public function update()
    {
        $this->validator(request()->all())->validate();

        return response()->json([
            'status' => true,
            'message' => 'Updated Successfuly',
            'results' => null
        ], Response::HTTP_NO_CONTENT);
    }

    public function delete()
    {
        return response()->json([
            'status' => true,
            'message' => 'Deleted Successfuly',
            'results' => null
        ], Response::HTTP_NO_CONTENT);
    }
}
