<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\M_Jadwal;
use App\Models\M_Pengajuan;
use App\Models\M_JadwalShift;
use Livewire\WithFileUploads;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\PengajuanForm;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ModalPengajuan extends Component
{
    use WithFileUploads;
    public PengajuanForm $form;
    public $karyawans;
    public $shifts;
    public $nama_karyawan;
    public $pengajuan;
    public $tanggal;
    public $keterangan;
    public $file;
    public $detail;
    public $pengajuanId;
    public $existingFile;

    protected $listeners = ['refreshTable' => 'refresh', 'edit-pengajuan' => 'loadData'];

    public function mount()
    {
        $this->shifts = M_JadwalShift::whereIn(
            'nama_shift',
            [
                'Izin',
                'Cuti',
                // 'Izin Setengah Hari',
                'Izin Setengah Hari (Masuk Pagi)',
                'Izin Setengah Hari (Masuk Siang)',
                '(Konter) Izin Setengah Hari Masuk Pagi',
            ]
        )->orderBy('nama_shift')->get();

        $this->form->dates = [
            ['tanggal' => null]
        ];
    }

    public function addDate()
    {
        $this->form->dates[] = [
            'tanggal' => ''
        ];
    }
    public function removeDate($index)
    {
        unset($this->form->dates[$index]);

        $this->form->dates = array_values($this->form->dates);
    }

    public function loadData($data)
    {
        // dd($data['id']);

        $this->pengajuanId = $data['id'];
        $this->existingFile = $data['file'];
        $this->form->fill($data);
        $this->form->pengajuan = $data['shift_id'];
        $this->form->tanggal = $data['tanggal'];
        $this->form->keterangan = $data['keterangan'];
        // $this->file = isset($data['file']) ? str_replace('storage/', '', $data['file']) : null;
    }

    public function saveEdit()
    {
        $dataPengajuan = M_Pengajuan::find($this->pengajuanId);
        // dd($dataPengajuan);
        if (!$dataPengajuan) {
            session()->flash('error', 'Data pengajuan tidak ditemukan!');
            return;
        }

        if ($this->file) {
            // dd($this->file);
            $this->validate([
                'file' => 'nullable',
            ], [
                'file.max' => 'Ukuran file maksimal 2MB.',
            ]);
        }

        $path = null;
        // if ($this->file && is_object($this->file)) {
        //     $filename = md5(uniqid()) . '.' . $this->file->extension();
        //     $path = $this->file->storeAs('presensi/file-pengajuan', $filename, 's3');
        // }
        if ($this->file && is_object($this->file)) {
            try {
                $filename = md5(uniqid()) . '.' . $this->file->extension();
                $destination = 'presensi/file-pengajuan/' . $filename;

                $tmpPath = $this->file->getRealPath();
                $fileContents = Storage::disk('s3')->get($tmpPath);

                if (!$fileContents) {
                    $this->addError('file', 'File tmp tidak ditemukan di storage.');
                    return;
                }

                $uploaded = Storage::disk('s3')->put($destination, $fileContents);

                if (!$uploaded) {
                    $this->addError('file', 'Gagal mengunggah file.');
                    return;
                }

                Storage::disk('s3')->delete($tmpPath);

                $path = $destination;
            } catch (\Exception $e) {
                $this->addError('file', 'Gagal mengunggah file: ' . $e->getMessage());
                return;
            }
        }

        $data = [
            'shift_id' => $this->form->pengajuan,
            'tanggal' => $this->form->tanggal,
            'keterangan' => $this->form->keterangan,
            'file' => $path ?? ($this->existingFile ?? null),
        ];
        // dd($data);

        $dataPengajuan->update($data);
        // M_Pengajuan::where('id', Crypt::decrypt($this->form->id))->update($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalEditPengajuan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function removeFile()
    {
        $this->file = null;
        $this->existingFile = null;
    }

    public function store()
    {
        $this->form->validate();
        if ($this->file) {
            // dd($this->file);
            $this->validate([
                'file' => 'nullable',
            ], [
                'file.max' => 'Ukuran file maksimal 2MB.',
                'file.mimes' => 'Format file harus JPG, JPEG, PNG.',
            ]);
        }

        $path = null;

        // if ($this->file) {
        //     $filename = md5(uniqid()) . '.' . $this->file->extension();

        //     $path = $this->file->storeAs('presensi/file-pengajuan', $filename, 's3');
        // }
        if ($this->file) {
            try {
                $filename = md5(uniqid()) . '.' . $this->file->extension();
                $destination = 'presensi/file-pengajuan/' . $filename;

                // Baca file tmp dari S3
                $tmpPath = $this->file->getRealPath();
                // dd($tmpPath);
                $fileContents = Storage::disk('s3')->get($tmpPath);

                if (!$fileContents) {
                    $this->addError('file', 'File tmp tidak ditemukan di storage.');
                    return;
                }

                // Tulis ke path final di S3
                $uploaded = Storage::disk('s3')->put($destination, $fileContents);

                if (!$uploaded) {
                    $this->addError('file', 'Gagal mengunggah file.');
                    return;
                }

                // Hapus tmp
                Storage::disk('s3')->delete($tmpPath);

                $path = $destination;
            } catch (\Exception $e) {
                $this->addError('file', 'Gagal mengunggah file: ' . $e->getMessage());
                return;
            }
        }

        $karyawanId = M_DataKaryawan::where('user_id', Auth::id())
            ->value('id');

        foreach ($this->form->dates as $item) {

            $tanggal = $item['tanggal'];

            if (!$tanggal) {
                continue;
            }

            $exists = M_Pengajuan::where('karyawan_id', $karyawanId)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if ($exists) {
                continue;
            }

            M_Pengajuan::create([
                'karyawan_id' => $karyawanId,
                'shift_id'    => $this->form->pengajuan,
                'tanggal'     => $tanggal,
                'keterangan'  => $this->form->keterangan,
                'file'        => $path,
                'status'      => 0,
            ]);
        }

        $this->form->reset();
        $this->reset('file');

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon'  => 'success',
            'text'  => 'Pengajuan berhasil disimpan'
        ]);

        $this->dispatch('modalTambahPengajuan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function delete($id)
    {
        $pengajuan = M_Pengajuan::findOrFail(Crypt::decrypt($id));
        // dd($pengajuan);
        if (!$pengajuan) return;

        if ($pengajuan->status === 1) {
            $tanggal = \Carbon\Carbon::parse($pengajuan->tanggal);
            $hari = 'd' . $tanggal->day;
            $bulanTahun = $tanggal->format('Y-m');

            $jadwal = M_Jadwal::where('karyawan_id', $pengajuan->karyawan_id)
                ->where('bulan_tahun', $bulanTahun)
                ->first();

            if ($jadwal) {
                $jadwal->$hari = $pengajuan->jadwal_sebelumnya;
                $jadwal->save();
            }
        }

        $pengajuan->delete();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Dihapus',
            'icon' => 'success',
            'text' => 'Data pengajuan dihapus dan jadwal dikembalikan.'
        ]);

        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.karyawan.pengajuan.modal-pengajuan');
    }
}
