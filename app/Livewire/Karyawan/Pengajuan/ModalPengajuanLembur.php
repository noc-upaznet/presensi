<?php

namespace App\Livewire\Karyawan\Pengajuan;

use Livewire\Component;
use App\Models\M_Lembur;
use Livewire\WithFileUploads;
use App\Models\M_DataKaryawan;
use Illuminate\Support\Carbon;
use App\Livewire\Forms\LemburForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class ModalPengajuanLembur extends Component
{
    use WithFileUploads;
    public LemburForm $form;

    public $karyawans;
    public $file_bukti;
    protected $listeners = ['refreshTable' => 'refresh', 'edit-pengajuan' => 'loadData'];
    public $pengajuanId;
    public $existingFile;


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['form.waktu_mulai', 'form.waktu_akhir'])) {
            $this->hitungJamLembur();
        }
    }

    public function hitungJamLembur()
    {
        $mulai = strtotime($this->form->waktu_mulai);
        $akhir = strtotime($this->form->waktu_akhir);
        if ($mulai !== false && $akhir !== false) {
            $hasilLembur = ($akhir - $mulai) / 3600; // hasil dalam jam
            if ($hasilLembur < 0) {
                $hasilLembur += 24;
            }
        } else {
            $hasilLembur = 0;
        }
        $this->form->total_jam = $hasilLembur;
    }

    public function store()
    {
        $this->form->validate();

        $this->validate([
            'file_bukti' => 'required',
        ], [
            'file_bukti.required' => 'File bukti harus diunggah.',
            'file_bukti.max' => 'Ukuran file maksimal 2MB.',
            'file_bukti.mimes' => 'Format file harus JPG, JPEG, PNG.',
        ]);

        $path = null;
        // if ($this->file_bukti) {
        //     $filename = md5(uniqid()) . '.' . $this->file_bukti->extension();
        //     $path = $this->file_bukti->storeAs('presensi/file-lembur', $filename, 's3');
        // }
        if ($this->file_bukti) {
            try {
                $filename = md5(uniqid()) . '.' . $this->file_bukti->extension();
                $destination = 'presensi/file-lembur/' . $filename;

                // Baca file tmp dari S3
                $tmpPath = $this->file_bukti->getRealPath();
                // dd($tmpPath);
                $fileContents = Storage::disk('s3')->get($tmpPath);

                if (!$fileContents) {
                    $this->addError('file_bukti', 'File tmp tidak ditemukan di storage.');
                    return;
                }

                // Tulis ke path final di S3
                $uploaded = Storage::disk('s3')->put($destination, $fileContents);

                if (!$uploaded) {
                    $this->addError('file_bukti', 'Gagal mengunggah file.');
                    return;
                }

                // Hapus tmp
                Storage::disk('s3')->delete($tmpPath);

                $path = $destination;
            } catch (\Exception $e) {
                $this->addError('file_bukti', 'Gagal mengunggah file: ' . $e->getMessage());
                return;
            }
        }

        $data = [
            'karyawan_id' => M_DataKaryawan::where('user_id', Auth::id())->value('id'),
            'tanggal' => $this->form->tanggal,
            'jenis' => $this->form->jenis,
            'keterangan' => $this->form->keterangan,
            'waktu_mulai' => $this->form->waktu_mulai,
            'waktu_akhir' => $this->form->waktu_akhir,
            'total_jam' => round($this->form->total_jam, 2),
            'file_bukti' => $path,
            'satatus' => 0,
        ];
        // dd($data);

        // Simpan data ke database
        // M_Pengajuan::create($data);
        $pengajuan = M_Lembur::create($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Baru',
            'icon' => 'success',
            'text' => 'Pengajuan baru telah diajukan.'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPengajuanLembur', action: 'hide');
        $this->dispatch('refresh');
    }

    public function loadData($data)
    {
        // dd($data['id']);
        $this->pengajuanId = $data['id'];
        $this->form->fill($data);
        $this->existingFile = $data['file_bukti'];
        $this->form->tanggal = $data['tanggal'];
        $this->form->waktu_mulai = $data['waktu_mulai'] ? Carbon::parse($data['waktu_mulai'])->format('H:i') : null;
        $this->form->waktu_akhir = $data['waktu_akhir'] ? Carbon::parse($data['waktu_akhir'])->format('H:i') : null;
        $this->form->total_jam = $data['total_jam'] ? $data['total_jam'] : 0;
        $this->form->jenis = $data['jenis'] ?? 'Lembur';
        $this->form->keterangan = $data['keterangan'] ?? '';
        // $this->file_bukti = isset($data['file_bukti']) ? str_replace('storage/', '', $data['file_bukti']) : null;
    }

    public function saveEdit()
    {
        $dataPengajuan = M_Lembur::find($this->pengajuanId);
        // dd($dataPengajuan);
        if (!$dataPengajuan) {
            session()->flash('error', 'Data pengajuan tidak ditemukan!');
            return;
        }
        $this->form->validate();

        $this->validate([
            'file_bukti' => $this->existingFile ? 'nullable' : 'required',
        ], [
            'file_bukti.required' => 'File bukti harus diunggah.',
        ]);


        $path = null;
        // if ($this->file_bukti && is_object($this->file_bukti)) {
        //     $filename = md5(uniqid()) . '.' . $this->file_bukti->extension();
        //     $path = $this->file_bukti->storeAs('presensi/file-lembur', $filename, 's3');
        // }

        if ($this->file_bukti && is_object($this->file_bukti)) {
            try {
                $filename = md5(uniqid()) . '.' . $this->file_bukti->extension();
                $destination = 'presensi/file-lembur/' . $filename;

                $tmpPath = $this->file_bukti->getRealPath();
                $fileContents = Storage::disk('s3')->get($tmpPath);

                if (!$fileContents) {
                    $this->addError('file_bukti', 'File tmp tidak ditemukan di storage.');
                    return;
                }

                $uploaded = Storage::disk('s3')->put($destination, $fileContents);

                if (!$uploaded) {
                    $this->addError('file_bukti', 'Gagal mengunggah file.');
                    return;
                }

                Storage::disk('s3')->delete($tmpPath);

                $path = $destination;
            } catch (\Exception $e) {
                $this->addError('file_bukti', 'Gagal mengunggah file: ' . $e->getMessage());
                return;
            }
        }


        $data = [
            'tanggal' => $this->form->tanggal,
            'jenis' => $this->form->jenis,
            'keterangan' => $this->form->keterangan,
            'waktu_mulai' => $this->form->waktu_mulai,
            'waktu_akhir' => $this->form->waktu_akhir,
            'total_jam' => round($this->form->total_jam, 2),
            'file_bukti' => $path ?? ($this->existingFile ?? null),
        ];
        // dd($data);

        $dataPengajuan->update($data);

        // Reset input
        $this->form->reset();

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalEditPengajuanLembur', action: 'hide');
        $this->dispatch('refresh');
    }

    public function removeFile()
    {
        $this->file_bukti = null;
        $this->existingFile = null;
    }

    public function delete($id)
    {
        $pengajuan = M_Lembur::findOrFail(Crypt::decrypt($id));
        // dd($pengajuan);
        if (!$pengajuan) return;

        $pengajuan->delete();

        $this->dispatch('swal', params: [
            'title' => 'Pengajuan Dihapus',
            'icon' => 'success',
            'text' => 'Data pengajuan dihapus.'
        ]);

        $this->dispatch('modal-confirm-delete', action: 'hide');
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('livewire.karyawan.pengajuan.modal-pengajuan-lembur');
    }
}
