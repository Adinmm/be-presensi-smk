<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;

class AttendencesController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $kelas = $request->query('kelas');
        $tanggal = $request->query('tanggal');
        $status = $request->query('status');
        $data = Attendances::with(['student:id,nama_lengkap,nis', 'kelas:id,nama_kelas'])
            ->when(
                $kelas,
                function ($query) use ($kelas) {
                    $query->where('class_id', $kelas);
                }
            )->when($tanggal, function ($query) use ($tanggal) {
                $query->where('date', $tanggal);
            })->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })->orderBy('created_at', 'desc')->latest()->get();


        return $this->sendSuccessResponse('Success', $data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
            '*.student_id' => 'required',
            '*.class_id' => 'required',
            '*.date' => 'required|date',
            '*.status' => 'required'
        ]);

        $data = $request->all();

        if (empty($data)) {
            return $this->sendErrorResponse('Data presensi kosong', 400);
        }

        $studentId = $data[0]['student_id'];

        $date    = $data[0]['date'];
        $classId = $data[0]['class_id'];

        $cekAttendance = Attendances::where('student_id', $studentId)->where('date', $date)
            ->where('class_id', $classId)
            ->exists();

        if ($cekAttendance) {
            return $this->sendErrorResponse('Presensi untuk kelas ini pada tanggal tersebut sudah ada', 400);
        }

        foreach ($request->all() as $item) {
            Attendances::create([
                'student_id' => $item['student_id'],
                'class_id' => $item['class_id'],
                'date' => $item['date'],
                'status' => $item['status'],
            ]);
        }

        return $this->sendSuccessResponse(
            'Presensi berhasil ditambahkan',
            null,
            200
        );
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
        $attendance = Attendances::find($id);

        if (!$attendance) {
            return $this->sendErrorResponse('Presensi tidak ditemukan', 404);
        }

        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $attendance->update([
            'status' => $request->status,
        ]);

        return $this->sendSuccessResponse('Success', $attendance, 200);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
