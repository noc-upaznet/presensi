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
use Livewire\WithFileUploads;
use App\Models\M_DataKaryawan;
use App\Models\RoleLokasiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ClockInSelfie extends Component
{
    use WithFileUploads;
    public $photo;
    public $photo1;
    public $lokasis;
    public $clockInTime;
    public $tanggal;
    public $lokasiId;
    public float|null $latitude = null;
    public float|null $longitude = null;
    public $lokasisTerdekat = [];
    
    public function mount()
    {
        if (Auth::user()?->current_role !== 'user' && Auth::user()?->current_role !== 'har' && Auth::user()?->current_role !== 'spv') {
            // Bisa redirect atau abort
            return redirect()->route('dashboard');
            // abort(403, 'Access Denied');
        }
        $this->lokasisTerdekat = collect();
        $this->photo = session('selfie_path');
    }

    public function updatedLatitude()
    {
        $this->hitungLokasiTerdekat();
    }
    
    public function updatedLongitude()
    {
        $this->hitungLokasiTerdekat();
    }
    
    public function hitungLokasiTerdekat()
    {
        if (!$this->latitude || !$this->longitude) return;
    
        $userLat = $this->latitude;
        $userLng = $this->longitude;
    
        $semuaLokasi = Lokasi::all();
        $radiusMaks = 0.04; // 40 meter
    
        $lokasiDalamRadius = collect();
        $lokasiTerdekat = null;
        $jarakTerdekat = null;
    
        foreach ($semuaLokasi as $lokasi) {
            if (!$lokasi->koordinat) continue;
    
            [$latDb, $lngDb] = explode(',', $lokasi->koordinat);
            $distance = $this->calculateDistance($userLat, $userLng, floatval($latDb), floatval($lngDb));
    
            if ($distance <= $radiusMaks) {
                $lokasi->jarak = $distance;
                $lokasiDalamRadius->push($lokasi);
            }
    
            if (is_null($jarakTerdekat) || $distance < $jarakTerdekat) {
                $lokasiTerdekat = $lokasi;
                $lokasiTerdekat->jarak = $distance;
                $jarakTerdekat = $distance;
            }
        }
    
        $this->lokasisTerdekat = $lokasiDalamRadius->isNotEmpty()
            ? $lokasiDalamRadius
            : collect([$lokasiTerdekat]);
    }

    #[On('photoTaken')]
    public function handlePhotoTaken($photo)
    {
        // Bersihkan prefix dan decode
        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $photo);
        $image = base64_decode($base64Image);

        // Buat nama file unik
        $filename = 'selfie_' . now()->timestamp . '.png';

        // Simpan ke public disk
        Storage::disk('public')->put('selfies/' . $filename, $image);

        // Simpan path ke properti
        $this->photo = 'selfies/' . $filename;
    }

    public function removePhoto()
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
        Storage::disk('public')->delete($this->photo);
        }

        $this->photo = null;

        // Hapus session supaya foto sebelumnya gak muncul lagi
        session()->forget($this->photo);
    }

    public function clockIn()
    {
        $user = Auth::user()->id;
        $karyawanId = M_DataKaryawan::where('user_id', $user)->value('id');
        // dd($karyawanId);
        $tanggal = now()->toDateString();
        $clockInTime = now()->toTimeString();
    
        // Validasi foto
        if (!$this->photo) {
            session()->flash('error', 'Foto tidak valid atau kosong.');
            return;
        }
    
        // Validasi koordinat
        if (!$this->latitude || !$this->longitude) {
            session()->flash('error', 'Lokasi tidak tersedia. Aktifkan GPS.');
            return;
        }
    
        // Ambil data role_lokasi
        $roleLokasi = RoleLokasiModel::where('karyawan_id', $karyawanId)
            ->first();
            // dd($roleLokasi);
    
        if (!$roleLokasi) {
            session()->flash('error', 'Role lokasi Anda tidak ditemukan.');
            return;
        }
    
        $lokasiPresensi = $roleLokasi->lokasi_presensi ?? '[]';
        if (is_array($lokasiPresensi)) {
            $lokasiIds = collect($lokasiPresensi)
                ->filter()
                ->values()
                ->toArray();
        } else {
            $lokasiIds = collect(json_decode($lokasiPresensi, true))
                ->filter()
                ->values()
                ->toArray();
        }
    
        if (empty($lokasiIds)) {
            session()->flash('error', 'Anda belum memiliki lokasi presensi yang ditentukan.');
            return;
        }
    
        $lock = $roleLokasi->lock ?? 1;
        $lokasiIdTerdekat = null;
    
        if ($lock == 1) {
            // Jika lock = 1, validasi lokasi dengan radius
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
    
            if ($lokasis->isEmpty()) {
                session()->flash('error', 'Data lokasi tidak ditemukan.');
                return;
            }
    
            $radiusMaks = 0.04; // 40 meter
            $dalamRadius = false;
    
            foreach ($lokasis as $lokasi) {
                if (!$lokasi->koordinat) continue;
    
                [$latDb, $lngDb] = explode(',', $lokasi->koordinat);
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
            // Jika lock = 0, ambil salah satu lokasi saja untuk disimpan (misalnya pertama)
            $lokasiIdTerdekat = null;
        }
    
        // Ambil jadwal shift
        $hari = 'd' . now()->day;
        $bulanTahun = now()->format('Y-m');
        $jadwal = M_Jadwal::where('karyawan_id', $karyawanId)
            ->where('bulan_tahun', $bulanTahun)
            ->first();
    
        $shiftId = $jadwal?->{$hari};
        $shift = M_JadwalShift::find($shiftId);
    
        $status = 0;
        if ($shift && $shift->jam_masuk) {
            $jamMasukShift = Carbon::parse($shift->jam_masuk);
            $jamSekarang = Carbon::parse($clockInTime);
            if ($jamSekarang->gt($jamMasukShift)) {
                $status = 1;
            }
        }
    
        // Simpan presensi
        M_Presensi::create([
            'user_id'   => $karyawanId,
            'tanggal'   => $tanggal,
            'clock_in'  => $clockInTime,
            'clock_out' => "00:00:00",
            'lokasi'    => json_encode([$lokasiIdTerdekat]),
            'file'      => $this->photo,
            'status'    => $status,
        ]);
    
        $this->reset(['photo']);
        session()->flash('success', 'Clock-in berhasil.');
        return redirect()->route('clock-in');
    }


    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Jarak dalam kilometer
    }

    public function render()
    {
        return view('livewire.clock-in-selfie');
    }
}
