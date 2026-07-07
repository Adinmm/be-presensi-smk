<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class StudentController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $kelas = $request->query('kelas');
        $search = $request->query('search');
        $perPage = $request->query('per_page');

        $data = Students::with('kelas:id,nama_kelas')
            ->when($kelas, function ($query) use ($kelas) {
                $query->where('class_id', $kelas);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('nama_lengkap', 'ILIKE', "%{$search}%")
                    ->orWhere('nis', 'ILIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);

        return $this->sendSuccessResponse('Success', [
            'items' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validate = $request->validate([

            'nis' => 'required',
            'jenis_kelamin' => 'required',
            'nama_lengkap' => 'required',
            'no_telepon' => 'nullable',
            'class_id' => 'required'
        ]);

        Students::create($validate);

        return $this->sendSuccessResponse('Success', null, 200);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        $student = Students::find($id);

        if (!$student) {
            return $this->sendErrorResponse('Data not found', 404);
        }

        $validated = $request->validate([
            'nis' => 'sometimes|string',
            'jenis_kelamin' => 'sometimes|string',
            'nama_lengkap' => 'sometimes|string',
            'no_telepon' => 'nullable|string',
        ]);

        $student->update($validated);

        return $this->sendSuccessResponse(
            'Update student successfully',
            $student->fresh(),
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $student = Students::find($id);
        if (!$student) {
            return $this->sendErrorResponse('Data not found', 404);
        }

        $student->delete();

        return $this->sendSuccessResponse('Delete student successfully', null, 200);
    }
}
