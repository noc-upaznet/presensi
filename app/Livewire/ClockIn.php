<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Lokasi;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Presensi;
use Livewire\Attributes\On;
use App\Models\M_JadwalShift;
use App\Models\M_DataKaryawan;
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
    public float|null $latitude = null;
    public float|null $longitude = null;
    public $lokasis;
    public $lokasiId;

    public $shouldRedirect = false;

    protected $listeners = ['photoTaken' => 'handlePhoto', 'refreshTable' => 'refresh'];
    
    public function mount()
    {
        if (Auth::user()?->current_role !== 'user' && Auth::user()?->current_role !== 'hr' && Auth::user()?->current_role !== 'spv') {
            // Bisa redirect atau abort
            return redirect()->route('dashboard');
            // abort(403, 'Access Denied');
        }
        if (!Auth::check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->to(route('login'));
        }

        $this->userName = Auth::user()->name ?? 'Guest';

        $userId = Auth::user()->id;
        $karyawanId = M_DataKaryawan::where('user_id', $userId)->value('id');
        // dd($karyawanId);

        $tanggal = Carbon::now();
        $hari = 'd' . $tanggal->day; // contoh: d24
        $bulanTahun = $tanggal->format('Y-m');

        // ambil jadwal user
        $jadwal = M_Jadwal::where('karyawan_id', $karyawanId)
            ->where('bulan_tahun', $bulanTahun)
            ->first();

        $shiftId = $jadwal?->{$hari}; // ambil shift id hari ini

        $shift = M_JadwalShift::find($shiftId); // tabel shift berisi jam_masuk & jam_keluar
        // dd($shift);
        $this->jamMasuk = $shift?->jam_masuk ?? '00:00';
        $this->jamKeluar = $shift?->jam_pulang ?? '00:00';

        $user = Auth::user();
        $today = now()->toDateString();

        $presensi = M_Presensi::where('user_id', $karyawanId)
            ->where('tanggal', $today)
            ->first();
        // dd($presensi);

        if ($presensi) {
            $this->hasClockedIn = $presensi->clock_in !== '00:00:00';
            $this->hasClockedOut = $presensi->clock_out !== '00:00:00';
        }
    }

    public function showCamera()
    {
        // Emit event untuk membuka kamera
        $this->dispatch('cameraModal', action: 'show');
    }

    #[On('photoTaken')]
    public function handlePhotoTaken($photo)
    {
        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $photo);
        $image = base64_decode($base64Image);

        $filename = 'selfie_' . now()->timestamp . '.png';
        Storage::disk('public')->put('selfies/' . $filename, $image);

        $this->photo = 'selfies/' . $filename;
        // dd($path);

        // Redirect ke halaman clock-in-selfie dengan query param
        return redirect()->to('/clock-in-selfie')->with('selfie_path', $this->photo);
    }

    public function showClockOutModal()
    {
        // Emit event untuk membuka modal clock-out
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

    public function clockOut()
    {
        $user = Auth::user()->id;
        $karyawanId = M_DataKaryawan::where('user_id', $user)->value('id');
        $tanggal = now()->toDateString();
        $clockOutTime = now()->toTimeString();
    
        // Validasi koordinat
        if (!$this->latitude || !$this->longitude) {
            session()->flash('error', 'Lokasi tidak tersedia. Aktifkan GPS.');
            return;
        }
    
        // Ambil data presensi hari ini
        $presensi = M_Presensi::where('user_id', $karyawanId)
            ->where('tanggal', $tanggal)
            ->first();
    
        if (!$presensi) {
            session()->flash('error', 'Data presensi tidak ditemukan.');
            return;
        }
    
        if ($presensi->clock_out !== '00:00:00') {
            session()->flash('error', 'Anda sudah melakukan clock-out.');
            return;
        }
    
        // Ambil role lokasi
        $roleLokasi = RoleLokasiModel::where('karyawan_id', $karyawanId)
            ->first();
    
        $lock = $roleLokasi->lock ?? 1; // default ke 1 (aktifkan radius)
    
        if ($lock == 1) {
            // Ambil lokasi dari data presensi (yang disimpan saat clock-in)
            $lokasiIds = json_decode($presensi->lokasi, true) ?? [];
    
            if (empty($lokasiIds)) {
                session()->flash('error', 'Lokasi presensi tidak ditemukan.');
                return;
            }
    
            // Ambil data lokasi dari database
            $lokasis = Lokasi::whereIn('id', $lokasiIds)->get();
    
            if ($lokasis->isEmpty()) {
                session()->flash('error', 'Data lokasi tidak ditemukan.');
                return;
            }
    
            // Cek apakah user masih dalam radius yang diizinkan
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
                    break;
                }
            }
    
            if (!$dalamRadius) {
                session()->flash('error', 'Anda berada di luar radius lokasi yang diizinkan (maks 40 meter).');
                return;
            }
        }
    
        // Update clock-out
        $presensi->update([
            'clock_out' => $clockOutTime,
        ]);
    
        session()->flash('success', 'Clock-out berhasil.');
        return redirect()->route('clock-in');
    }
    
    public function render()
    {
        $datas = M_Presensi::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('livewire.clock-in', [
            'datas' => $datas,
        ]);
    }
}