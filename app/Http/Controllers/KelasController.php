<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class KelasController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $kelas = $request->query('kelas');

        $data = Classes::with(['students:id,class_id,nama_lengkap'])->when($kelas, function ($query) use ($kelas) {
            $query->where('tingkat', $kelas);
        })->get();

        if ($data->isEmpty()) {
            return $this->sendErrorResponse('Kelas tidak ditemukan', 404);
        }


        return $this->sendSuccessResponse('Success', $data, 200);
        //
    }
    public function get(Request $request) {
        $kelas = $request->query('kelas');

        $data = Classes::with(['students:id,class_id,nama_lengkap'])->get();

        if ($data->isEmpty()) {
            return $this->sendErrorResponse('Kelas tidak ditemukan', 404);
        }

        return $this->sendSuccessResponse('Success', $data, 200);
        //
    }

    /**
     * 
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validate = $request->validate([
            'nama_kelas' => 'required',
            'jurusan' => 'required',
            'tingkat' => 'required',

        ]);

        Classes::create($validate);

        return $this->sendSuccessResponse('Kelas berhasil ditambahkan', null, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $result = Classes::find($id);

        $validate = $request->validate([
            'nama_kelas' => 'sometimes|string',
            'jurusan' => 'sometimes|string',
            'tingkat' => 'sometimes|string',
        ]);

        $result->update($validate);

        return $this->sendSuccessResponse('Kelas berhasil diupdate', null, 200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $kelas = Classes::find($id);
        if (!$kelas) {
            return $this->sendErrorResponse('Data not found', 404);
        }

        $kelas->delete();

        return $this->sendSuccessResponse('Delete kelas successfully', null, 200);
    }
}
