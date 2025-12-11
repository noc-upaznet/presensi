<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lokasi;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
use App\Models\M_ListQuestion;
use App\Models\RoleLokasiModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClockIn extends Component
{
    public $userName;
    public $photo;
    public $jamMasuk = '-';
    public $jamKeluar = '-';
    public $hasClockedIn;
    public $hasClockedOut;
    public $hasPendingClockOut;
    public float|null $latitude = null;
    public float|null $longitude = null;
    public $lokasis;
    public $lokasiId;

    public $question;
    public $options = [];
    public $correct_answer;
    public $user_answer = null;
    public $isCorrect = false;

    public $correctCount = 0;
    public $requiredCorrect = 3;

    public $shouldRedirect = false;
    public $questionKey = null;

    public $usedQuestionIds = [];
    public $questionId = null;


    protected $listeners = ['photoTaken' => 'handlePhoto', 'refreshTable' => 'refresh'];

    public function mount()
    {
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }

        $this->userName = Auth::user()->name ?? 'Guest';

        $userId = Auth::user()->id;
        $karyawanId = M_DataKaryawan::where('user_id', $userId)->value('id');

        $tanggal = Carbon::now();
        $hari = 'd' . $tanggal->day;
        $bulanTahun = $tanggal->format('Y-m');

        $jadwal = M_Jadwal::where('karyawan_id', $karyawanId)
            ->where('bulan_tahun', $bulanTahun)
            ->first();

        $shiftId = $jadwal?->{$hari};
        $shift = M_JadwalShift::find($shiftId);

        $this->jamMasuk = $shift?->jam_masuk ?? '00:00:00';
        $this->jamKeluar = $shift?->jam_pulang ?? '00:00:00';

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // 🔹 Cek presensi hari ini
        $presensiToday = M_Presensi::where('user_id', $karyawanId)
            ->where('tanggal', $today)
            ->first();

        if ($presensiToday && $presensiToday->clock_in !== '00:00:00' && $presensiToday->clock_out === '00:00:00') {
            // Hari ini sudah clock-in tapi belum clock-out
            $this->hasClockedIn = true;
            $this->hasClockedOut = false;
            $this->hasPendingClockOut = false;
        } else {
            // 🔹 Cek presensi kemarin yang belum clock-out
            $presensiYesterday = M_Presensi::where('user_id', $karyawanId)
                ->where('tanggal', $yesterday)
                ->where('clock_in', '!=', '00:00:00')
                ->where('clock_out', '00:00:00')
                ->first();

            if ($presensiYesterday) {
                $this->hasClockedIn = true;
                $this->hasClockedOut = false;
                $this->hasPendingClockOut = true; // tampil tombol "Clock Out Tertunda"
            } else {
                $this->hasClockedIn  = $presensiToday?->clock_in && $presensiToday?->clock_in !== '00:00:00';
                $this->hasClockedOut = $presensiToday?->clock_out && $presensiToday?->clock_out !== '00:00:00';
                $this->hasPendingClockOut = false;
            }
        }

        // 🔹 Setelah logika presensi selesai, baru beri notifikasi jika jadwal libur
        if ($this->jamMasuk === '00:00:00' && $this->jamKeluar === '00:00:00') {
            session()->flash('error', 'Jadwal hari ini libur.');
        }
    }



    public function showCamera()
    {
        // Emit event untuk membuka kamera
        // $this->dispatch('cameraModal', action: 'show');
        return redirect()->route('clock-in-selfie');
    }

    #[On('photoTaken')]
    public function handlePhotoTaken($photo)
    {
        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $photo);
        $image = base64_decode($base64Image);

        $filename = 'selfie_' . Str::uuid() . '.png';
        Storage::disk('public')->put('selfies/' . $filename, $image);

        $this->photo = 'selfies/' . $filename;
        // dd($path);

        // Redirect ke halaman clock-in-selfie dengan query param
        return redirect()->to('/clock-in-selfie')->with('selfie_path', $this->photo);
    }

    public function loadRandomQuestion()
    {
        $query = M_ListQuestion::with('answers');
        if (!empty($this->usedQuestionIds)) {
            $query->whereNotIn('id', $this->usedQuestionIds);
        }

        $q = $query->inRandomOrder()->first();

        if (!$q) {
            $this->usedQuestionIds = [];
            $q = M_ListQuestion::with('answers')->inRandomOrder()->first();
        }

        if (!$q) {
            $this->questionId = null;
            $this->question = 'Belum ada pertanyaan di database.';
            $this->options = [];
            $this->correct_answer = null;
            $this->user_answer = null;
            $this->questionKey = (string) now()->timestamp . '-' . Str::random(5);
            return;
        }

        $this->questionId = $q->id;
        $this->question = $q->name;

        $shuffledAnswers = $q->answers->shuffle();
        $this->options = $shuffledAnswers->pluck('name')->values()->toArray();

        $correct = $q->answers->firstWhere('is_correct', 1);
        $this->correct_answer = $correct?->name;

        $this->user_answer = null;

        $this->questionKey = $this->questionId . '-' . Str::random(6);

        $this->usedQuestionIds[] = $this->questionId;
    }

    public function nextQuestion()
    {
        if (empty($this->user_answer)) {
            session()->flash('error', 'Jawab pertanyaan terlebih dahulu.');
            return;
        }

        if ($this->user_answer === $this->correct_answer) {
            $this->correctCount++;
        } else {
        }

        $this->user_answer = null;
        if ($this->correctCount < $this->requiredCorrect) {
            $this->loadRandomQuestion();
        }
    }

    public function showClockOutModal()
    {
        $this->loadRandomQuestion();

        $this->dispatch('clockOutModal', action: 'show');
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c; // hasil jarak dalam kilometer
    }

    // public function clockOut()
    // {
    //     $user = Auth::user()->id;
    //     $karyawanId = M_DataKaryawan::where('user_id', $user)->value('id');
    //     $tanggal = now()->toDateString();
    //     $clockOutTime = now()->toTimeString();

    //     // Validasi koordinat
    //     if (!$this->latitude || !$this->longitude) {
    //         session()->flash('error', 'Lokasi tidak tersedia. Aktifkan GPS.');
    //         return;
    //     }

    //     // Ambil data presensi hari ini
    //     $presensi = M_Presensi::where('user_id', $karyawanId)
    //         ->where('clock_out', '00:00:00')
    //         ->orderByDesc('tanggal')
    //         ->first();
    //     // dd($presensi);
    //     if (!$presensi) {
    //         session()->flash('error', 'Data presensi tidak ditemukan.');
    //         return;
    //     }

    //     if ($presensi->clock_out !== '00:00:00') {
    //         session()->flash('error', 'Anda sudah melakukan clock-out.');
    //         return;
    //     }

    //     // Ambil role lokasi
    //     // $roleLokasi = RoleLokasiModel::where('karyawan_id', $karyawanId)
    //     //     ->first();
    //     $roleLokasis = RoleLokasiModel::where('karyawan_id', $karyawanId)->get();

    //     $lock = $roleLokasis->first()->lock ?? 1;
    //     if ($lock == 1) {
    //         // Ambil lokasi dari data presensi (yang disimpan saat clock-in)
    //         $lokasiIds = $roleLokasis->pluck('lokasi_presensi')->flatten()->unique()->values()->all();

    //         if (empty($lokasiIds)) {
    //             session()->flash('error', 'Lokasi presensi tidak ditemukan.');
    //             return;
    //         }

    //         // Ambil data lokasi dari database
    //         $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();

    //         if ($lokasis->isEmpty()) {
    //             session()->flash('error', 'Data lokasi tidak ditemukan.');
    //             return;
    //         }

    //         // Cek apakah user masih dalam radius yang diizinkan
    //         $radiusMaks = 0.04; // 40 meter
    //         $dalamRadius = false;

    //         foreach ($lokasis as $lokasi) {
    //             if (!$lokasi->koordinat) continue;

    //             [$latDb, $lngDb] = explode(',', $lokasi->koordinat);
    //             $latDb = floatval($latDb);
    //             $lngDb = floatval($lngDb);

    //             $distance = $this->calculateDistance($this->latitude, $this->longitude, $latDb, $lngDb);
    //             if ($distance <= $radiusMaks) {
    //                 $dalamRadius = true;
    //                 $lokasiIdTerdekat = $lokasi->id;
    //                 break;
    //             }
    //         }

    //         if (!$dalamRadius) {
    //             session()->flash('error', 'Anda berada di luar radius lokasi yang diizinkan (maks 40 meter).');
    //             return;
    //         }
    //     } else {
    //         $lokasiIdTerdekat = $this->latitude . ', ' . $this->longitude;
    //         // dd($lokasiIdTerdekat);
    //     }

    //     // Update clock-out
    //     $presensi->update([
    //         'clock_out' => $clockOutTime,
    //         'lokasi_clock_out' => $lokasiIdTerdekat
    //     ]);

    //     session()->flash('success', 'Clock-out berhasil.');
    //     return redirect()->route('clock-in');
    // }

    protected function getRandomSwalMessage(): array
    {
        $messages = [
            [
                'title' => 'Kerja Bagus Hari Ini! 🎉',
                'text'  => 'Saatnya istirahat. Sampai jumpa besok!',
                'icon'  => 'success',
            ],
            [
                'title' => 'Clock-Out Berhasil ✔',
                'text'  => 'Terima kasih atas kontribusi Anda hari ini.',
                'icon'  => 'success',
            ],
            [
                'title' => 'Good Job! ✨',
                'text'  => 'Istirahat yang cukup, ya!',
                'icon'  => 'success',
            ],
            [
                'title' => 'Selesai! 💪',
                'text'  => 'Satu langkah lebih dekat menuju tujuan Anda.',
                'icon'  => 'success',
            ],
            [
                'title' => 'Done ✔',
                'text'  => 'Sampai ketemu besok, tetap semangat!',
                'icon'  => 'success',
            ],
            [
                'title' => 'Clock-Out! 😎',
                'text'  => 'Sekarang waktunya rileks sejenak.',
                'icon'  => 'success',
            ],
        ];

        return $messages[array_rand($messages)];
    }

    public function clockOut()
    {
        if ($this->correctCount < $this->requiredCorrect) {

            if (is_null($this->user_answer)) {
                session()->flash('error', 'Jawab pertanyaan terlebih dahulu sebelum clock-out.');
            }

            return;
        }
        $userId = Auth::user()->id;
        $karyawanId = M_DataKaryawan::where('user_id', $userId)->value('id');

        $now = \Carbon\Carbon::now();
        $tanggalSekarang = $now->toDateString();
        $clockOutTime = $now->toTimeString();
        $yesterday = $now->copy()->subDay()->toDateString();

        if (!$this->latitude || !$this->longitude) {
            session()->flash('error', 'Lokasi tidak tersedia. Aktifkan GPS.');
            return;
        }

        $presensiToday = M_Presensi::where('user_id', $karyawanId)
            ->where('tanggal', $tanggalSekarang)
            ->first();

        $presensiToUpdate = null;
        $isPending = false;

        if ($presensiToday && $presensiToday->clock_in !== '00:00:00' && $presensiToday->clock_out === '00:00:00') {
            $presensiToUpdate = $presensiToday;
        } else {
            $presensiYesterday = M_Presensi::where('user_id', $karyawanId)
                ->where('tanggal', $yesterday)
                ->where('clock_in', '!=', '00:00:00')
                ->where('clock_out', '00:00:00')
                ->first();

            if ($presensiYesterday) {
                $presensiToUpdate = $presensiYesterday;
                $isPending = true;
            }
        }

        if (!$presensiToUpdate) {
            session()->flash('error', 'Tidak ada presensi yang dapat di-clock-out (hari ini atau kemarin).');
            return;
        }

        // Pastikan belum di-update oleh proses lain (safety)
        if ($presensiToUpdate->clock_out !== '00:00:00') {
            session()->flash('error', 'Presensi sudah di-clock-out sebelumnya.');
            return;
        }

        // CEK JAM PULANG
        $tanggalPresensi = \Carbon\Carbon::parse($presensiToUpdate->tanggal);
        $days = 'd' . $tanggalPresensi->day;
        $bulanTahun = $tanggalPresensi->format('Y-m');

        $jadwal = M_Jadwal::where('karyawan_id', $karyawanId)
            ->where('bulan_tahun', $bulanTahun)
            ->first();

        $shiftId = $jadwal?->{$days};
        $shift = $shiftId ? M_JadwalShift::find($shiftId) : null;

        if (!$isPending) {
            if ($shift && $shift->jam_pulang) {
                $jamPulangShift = \Carbon\Carbon::parse($shift->jam_pulang);
                $jamSekarang = \Carbon\Carbon::now();

                if ($jamSekarang->lt($jamPulangShift)) {
                    session()->flash('error', 'Belum waktu pulang. Anda baru bisa clock-out setelah jam ' . $jamPulangShift->format('H:i') . '.');
                    return;
                }
            }
        } else {
            $selisihHari = $tanggalPresensi->startOfDay()->diffInDays(now()->startOfDay());
            // dd($selisihHari);
            if ($selisihHari > 1) {
                session()->flash('error', 'Clock-out tertunda maksimal 1 hari setelah tanggal presensi.');
                return;
            }
        }

        // Ambil role lokasi dan cek radius
        $roleLokasis = RoleLokasiModel::where('karyawan_id', $karyawanId)->get();
        $lock = $roleLokasis->first()?->lock ?? 1;

        if ($lock == 1) {
            $lokasiIds = $roleLokasis->pluck('lokasi_presensi')->flatten()->unique()->values()->all();

            if (empty($lokasiIds)) {
                session()->flash('error', 'Lokasi presensi tidak ditemukan.');
                return;
            }

            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
            if ($lokasis->isEmpty()) {
                session()->flash('error', 'Data lokasi tidak ditemukan.');
                return;
            }

            $radiusMaks = 0.04; // 40 meter
            $dalamRadius = false;
            $lokasiIdTerdekat = null;

            foreach ($lokasis as $lokasi) {
                if (!$lokasi->koordinat) continue;

                [$latDb, $lngDb] = array_map('trim', explode(',', $lokasi->koordinat));
                $latDb = floatval($latDb);
                $lngDb = floatval($lngDb);

                $distance = $this->calculateDistance($this->latitude, $this->longitude, $latDb, $lngDb);
                if ($distance <= $radiusMaks) {
                    $dalamRadius = true;
                    $lokasiIdTerdekat = $lokasi->id;
                    break;
                }
            }

            if (!$dalamRadius) {
                session()->flash('error', 'Anda berada di luar radius lokasi yang diizinkan (maks 40 meter).');
                return;
            }
        } else {
            $lokasiIdTerdekat = $this->latitude . ', ' . $this->longitude;
        }

        $presensiToUpdate->update([
            'clock_out' => $clockOutTime,
            'lokasi_clock_out' => $lokasiIdTerdekat,
        ]);

        $swal = $this->getRandomSwalMessage();

        $this->dispatch(
            'swal',
            title: $swal['title'],
            icon: $swal['icon'],
            text: $swal['text'],
            timer: 3000,
            showConfirmButton: false,
        );
    }


    public function render()
    {
        $datas = M_Presensi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('livewire.clock-in', [
            'datas' => $datas,
        ]);
    }
}
