<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendances;
use Illuminate\Support\Facades\DB;

use App\Models\IdempotencyKeys as IdempotencyKey;

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
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return $this->sendErrorResponse(
                'Idempotency-Key is required',
                400
            );
        }

        $request->validate([
            '*.student_id' => 'required|exists:students,id',
            '*.class_id'   => 'required|exists:classes,id',
            '*.date'       => 'required|date',
            '*.status'     => 'required',
        ]);

        $data = $request->all();

        if (empty($data)) {
            return $this->sendErrorResponse(
                'Data presensi kosong',
                400
            );
        }

        return DB::transaction(function () use ($request, $key, $data) {

            $idempotency = IdempotencyKey::where('user_id', $request->user()->id)
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if ($idempotency) {
                return response()->json(
                    json_decode($idempotency->response, true),
                    200
                );
            }

            foreach ($data as $item) {

                $exists = Attendances::where('student_id', $item['student_id'])
                    ->where('class_id', $item['class_id'])
                    ->whereDate('date', $item['date'])
                    ->exists();

                if ($exists) {
                    return $this->sendErrorResponse(
                        "Presensi sudah ada pada tanggal tersebut.",
                        409
                    );
                }
            }
            $attendances = [];

            foreach ($data as $item) {

                $attendances[] = Attendances::create([
                    'student_id' => $item['student_id'],
                    'class_id'   => $item['class_id'],
                    'date'       => $item['date'],
                    'status'     => $item['status'],
                ]);
            }

            // Response yang akan dikembalikan
            $response = [
                'success' => true,
                'message' => 'Presensi berhasil ditambahkan',
                'data'    => $attendances,
            ];

            // Simpan idempotency
            IdempotencyKey::create([
                'user_id'  => $request->user()->id,
                'key'      => $key,
                'endpoint' => $request->path(),
                'response' => json_encode($response),
            ]);

            return $this->sendSuccessResponse('Presensi berhasil ditambahkan', null, 201);
        });
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
