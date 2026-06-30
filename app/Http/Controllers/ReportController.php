<?php

namespace App\Http\Controllers;

use App\Models\Attendances;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Models\Students;

class ReportController extends Controller {
    public function index(Request $request) {
        $kelas = $request->query('kelas');
        $tanggal = $request->query('tanggal');

        $studentsQuery = Students::select('id', 'class_id', 'nis', 'nama_lengkap');
        if ($kelas) {
            $studentsQuery->where('class_id', $kelas);
        }
        $students = $studentsQuery->get();
        $attendancesQuery = Attendances::with(['student:id,nama_lengkap,nis', 'kelas:id,nama_kelas'])
            ->when($kelas, function ($query) use ($kelas) {
                $query->where('class_id', $kelas);
            })->when($tanggal, function ($query) use ($tanggal) {
                $query->where('date', 'like', $tanggal . '%');
            });

        $attendances = $attendancesQuery->latest()->get();

        $studentData = $students->map(function ($student) use ($attendances) {

            $studentAbsences = $attendances->where('student_id', $student->id);
            $hadir = $studentAbsences->where('status', 'hadir')->count();
            $izin = $studentAbsences->where('status', 'izin')->count();
            $sakit = $studentAbsences->where('status', 'sakit')->count();
            $alpa = $studentAbsences->where('status', 'alpa')->count();
            $total = $studentAbsences->count();
            $persentase_kehadiran = $total > 0 ? ($hadir / $total) * 100 : 0;

            return [
                'id' => $student->id,
                'class_id' => $student->class_id,
                'nis' => $student->nis,
                'nama_lengkap' => $student->nama_lengkap,
                'rekap_individu' => [
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpa' => $alpa,
                    'total' => $total,
                    'persentase_kehadiran' => $persentase_kehadiran
                ]
            ];
        });

        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalSakit = $attendances->where('status', 'sakit')->count();
        $totalIzin  = $attendances->where('status', 'izin')->count();
        $totalAlpa  = $attendances->where('status', 'alpa')->count();

        $response = [
            'rekap_siswa' => $studentData,
            'total_hadir' => $totalHadir,
            'total_sakit' => $totalSakit,
            'total_izin'  => $totalIzin,
            'total_alpa'  => $totalAlpa
        ];

        return $this->sendSuccessResponse(
            'Reports retrieved successfully',
            $response
        );
    }

    public function dashboard(Request $request) {
        $tanggal = $request->query('tanggal');


        $students = Students::select('id', 'class_id', 'nis', 'nama_lengkap')->get();
        $kelas = Classes::with(['students:id,nama_lengkap,class_id'])->get();
        $attendances = Attendances::with(['student:id,nama_lengkap,nis', 'kelas:id,nama_kelas'])->where('date', 'like', $tanggal . '%')->orderBy('updated_at', 'desc')->latest()->get();

        $totalSiswa =  $students->count();
        $totalKelas = $kelas->count();

        $hadir = $attendances->where('status', 'hadir')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        $izin = $attendances->where('status', 'izin')->count();

        $alpa = $attendances->where('status', 'alpa')->count();

        $highlight = [
            'total_siswa' => $totalSiswa,
            'total_kelas' => $totalKelas,
            'hadir' => $hadir,
            'izin_sakit' => $sakit + $izin,
            'alpa' => $alpa
        ];
        $ringkasanStatus = [
            'hadir' => [
                'jumlah' => $hadir,
                'persentase' => $hadir > 0 ? ($hadir / $totalSiswa) * 100 : 0
            ],
            'izin' => [
                'jumlah' => $izin,
                'persentase' => $izin > 0 ? ($izin / $totalSiswa) * 100 : 0
            ],
            'sakit' => [
                'jumlah' => $sakit,
                'persentase' => $sakit > 0 ? ($sakit / $totalSiswa) * 100 : 0
            ],
            'alpa' => [
                'jumlah' => $alpa,
                'persentase' => $alpa > 0 ? ($alpa / $totalSiswa) * 100 : 0
            ]
        ];

        return $this->sendSuccessResponse(
            'Dashboards retrieved successfully',
            [
                'highlight' => $highlight,
                'ringkasan_status' => $ringkasanStatus,
                'kelas' => $kelas,
                'aktivitas_terbaru' => $attendances
            ]
        );
    }
}
