<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\M_Entitas;
use App\Models\M_Divisi;
use App\Models\M_Jabatan;
use Livewire\WithFileUploads;
use App\Models\M_DataKaryawan;
use App\Imports\KaryawanImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Livewire\Forms\TambahDataKaryawanForm;
use App\Models\Region\District;
use App\Models\Region\Province;
use App\Models\Region\Regency;
use App\Models\Region\Village;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;

class ModalKaryawan extends Component
{
    public TambahDataKaryawanForm $form;
    use WithFileUploads;

    public $file;

    public $karyawanId;
    public $entitas;
    public $divisi;
    public $jabatan;
    protected $listeners = ['edit-ticket' => 'loadTicketData'];

    public $provinces = [];
    public $regenciesKTP = [];
    public $districtsKTP = [];
    public $villagesKTP = [];

    public $regenciesDomisili = [];
    public $districtsDomisili = [];
    public $villagesDomisili = [];
    public bool $loadingAlamatEdit = false;

    public function mount()
    {
        $this->entitas = M_Entitas::all();
        $this->divisi = M_Divisi::all();
        $this->jabatan = M_Jabatan::all();
        // dd($this->jabatan);
        $this->provinces = Province::orderBy('name')->get();
    }

    public function updatedFormTotalUpah($value)
    {
        // dd($value);
        $value = (int) str_replace('.', '', $value);
        $this->form->gaji_pokok = $value * 0.75;
        $this->form->tunjangan_jabatan = $value * 0.25;
    }
    public function loadTicketData($data)
    {
        $this->karyawanId = $data['id'];
        $this->loadingAlamatEdit = true;

        $this->form->fill($data);

        $this->form->alamatKTP = $data['alamat_ktp'] ?? '';
        $this->form->alamatDomisili = $data['alamat_domisili'] ?? '';
        $this->form->nomorKTP = $data['nik'] ?? '';
        $this->form->nomorVISA = $data['visa'] ?? null;

        $this->regenciesKTP = collect();
        $this->districtsKTP = collect();
        $this->villagesKTP = collect();

        $this->regenciesDomisili = collect();
        $this->districtsDomisili = collect();
        $this->villagesDomisili = collect();

        $this->form->provinsiKTP = null;
        $this->form->kabupatenKTP = null;
        $this->form->kecamatanKTP = null;
        $this->form->desaKTP = null;

        $this->form->provinsiDomisili = null;
        $this->form->kabupatenDomisili = null;
        $this->form->kecamatanDomisili = null;
        $this->form->desaDomisili = null;

        $villageKTP = Village::find($data['village_id_ktp'] ?? null);

        if ($villageKTP) {

            $districtKTP = District::find($villageKTP->district_id);

            if ($districtKTP) {

                $regencyKTP = Regency::find($districtKTP->regency_id);

                if ($regencyKTP) {

                    $this->form->provinsiKTP = (string) $regencyKTP->province_id;
                    $this->form->kabupatenKTP = (string) $regencyKTP->id;
                    $this->form->kecamatanKTP = (string) $districtKTP->id;
                    $this->form->desaKTP = (string) $villageKTP->id;

                    $this->regenciesKTP = Regency::where(
                        'province_id',
                        $regencyKTP->province_id
                    )->orderBy('name')->get();

                    $this->districtsKTP = District::where(
                        'regency_id',
                        $regencyKTP->id
                    )->orderBy('name')->get();

                    $this->villagesKTP = Village::where(
                        'district_id',
                        $districtKTP->id
                    )->orderBy('name')->get();
                }
            }
        }

        $this->form->gunakanAlamatKTP =
            (bool) ($data['is_same_address'] ?? false);

        if ($this->form->gunakanAlamatKTP) {

            $this->form->provinsiDomisili = $this->form->provinsiKTP;
            $this->form->kabupatenDomisili = $this->form->kabupatenKTP;
            $this->form->kecamatanDomisili = $this->form->kecamatanKTP;
            $this->form->desaDomisili = $this->form->desaKTP;
            $this->form->alamatDomisili = $this->form->alamatKTP;

            $this->regenciesDomisili = $this->regenciesKTP;
            $this->districtsDomisili = $this->districtsKTP;
            $this->villagesDomisili = $this->villagesKTP;
        } else {

            $villageDomisili = Village::find(
                $data['village_id_domisili'] ?? null
            );

            if ($villageDomisili) {

                $districtDomisili = District::find(
                    $villageDomisili->district_id
                );

                if ($districtDomisili) {

                    $regencyDomisili = Regency::find(
                        $districtDomisili->regency_id
                    );

                    if ($regencyDomisili) {

                        $this->form->provinsiDomisili =
                            (string) $regencyDomisili->province_id;

                        $this->form->kabupatenDomisili =
                            (string) $regencyDomisili->id;

                        $this->form->kecamatanDomisili =
                            (string) $districtDomisili->id;

                        $this->form->desaDomisili =
                            (string) $villageDomisili->id;

                        $this->regenciesDomisili = Regency::where(
                            'province_id',
                            $regencyDomisili->province_id
                        )->orderBy('name')->get();

                        $this->districtsDomisili = District::where(
                            'regency_id',
                            $regencyDomisili->id
                        )->orderBy('name')->get();

                        $this->villagesDomisili = Village::where(
                            'district_id',
                            $districtDomisili->id
                        )->orderBy('name')->get();
                    }
                }
            }
        }

        $this->loadingAlamatEdit = false;

        $this->dispatch('alamat-edit-loaded');
    }

    public function updatedFormProvinsiKTP($value)
    {
        if ($this->loadingAlamatEdit) {
            return;
        }

        $this->form->kabupatenKTP = null;
        $this->form->kecamatanKTP = null;
        $this->form->desaKTP = null;

        $this->regenciesKTP = [];
        $this->districtsKTP = [];
        $this->villagesKTP = [];

        if ($value) {
            $this->regenciesKTP = Regency::where(
                'province_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }
    public function updatedFormKabupatenKTP($value)
    {
        if ($this->loadingAlamatEdit) {
            return;
        }

        $this->form->kecamatanKTP = null;
        $this->form->desaKTP = null;

        $this->districtsKTP = [];
        $this->villagesKTP = [];

        if ($value) {
            $this->districtsKTP = District::where(
                'regency_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }
    public function updatedFormKecamatanKTP($value)
    {
        if ($this->loadingAlamatEdit) {
            return;
        }

        $this->form->desaKTP = null;

        $this->villagesKTP = [];

        if ($value) {
            $this->villagesKTP = Village::where(
                'district_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }

    public function toggleGunakanAlamatKTP()
    {
        $this->form->gunakanAlamatKTP = !$this->form->gunakanAlamatKTP;

        if (!$this->form->gunakanAlamatKTP) {
            return;
        }

        $provinsi = $this->form->provinsiKTP;
        $kabupaten = $this->form->kabupatenKTP;
        $kecamatan = $this->form->kecamatanKTP;
        $desa = $this->form->desaKTP;
        $alamat = $this->form->alamatKTP;

        $this->regenciesDomisili = collect();
        $this->districtsDomisili = collect();
        $this->villagesDomisili = collect();

        if ($provinsi) {
            $this->regenciesDomisili = Regency::where(
                'province_id',
                $provinsi
            )
                ->orderBy('name')
                ->get();
        }

        if ($kabupaten) {
            $this->districtsDomisili = District::where(
                'regency_id',
                $kabupaten
            )
                ->orderBy('name')
                ->get();
        }

        if ($kecamatan) {
            $this->villagesDomisili = Village::where(
                'district_id',
                $kecamatan
            )
                ->orderBy('name')
                ->get();
        }

        $this->form->provinsiDomisili = $provinsi;
        $this->form->kabupatenDomisili = $kabupaten;
        $this->form->kecamatanDomisili = $kecamatan;
        $this->form->desaDomisili = $desa;
        $this->form->alamatDomisili = $alamat;
    }

    public function updatedFormProvinsiDomisili($value)
    {
        if ($this->form->gunakanAlamatKTP) {
            return;
        }

        $this->form->kabupatenDomisili = null;
        $this->form->kecamatanDomisili = null;
        $this->form->desaDomisili = null;

        $this->regenciesDomisili = collect();
        $this->districtsDomisili = collect();
        $this->villagesDomisili = collect();

        if ($value) {
            $this->regenciesDomisili = Regency::where(
                'province_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }

    public function updatedFormKabupatenDomisili($value)
    {
        if ($this->form->gunakanAlamatKTP) {
            return;
        }

        $this->form->kecamatanDomisili = null;
        $this->form->desaDomisili = null;

        $this->districtsDomisili = collect();
        $this->villagesDomisili = collect();

        if ($value) {
            $this->districtsDomisili = District::where(
                'regency_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }

    public function updatedFormKecamatanDomisili($value)
    {
        if ($this->form->gunakanAlamatKTP) {
            return;
        }

        $this->form->desaDomisili = null;

        $this->villagesDomisili = collect();

        if ($value) {
            $this->villagesDomisili = Village::where(
                'district_id',
                $value
            )
                ->orderBy('name')
                ->get();
        }
    }

    public function saveEdit()
    {
        $ticket = M_DataKaryawan::find($this->karyawanId);
        // dd($ticket);
        if (!$ticket) {
            session()->flash('error', 'Data karyawan tidak ditemukan!');
            return;
        }

        $namaBank = $this->form->nama_bank === 'Lainnya'
            ? $this->form->nama_bank_lainnya
            : $this->form->nama_bank;

        $data = [
            'nama_karyawan' => $this->form->nama_karyawan,
            'email' => $this->form->email,
            'no_hp' => $this->form->no_hp,
            'tempat_lahir' => $this->form->tempat_lahir,
            'tanggal_lahir' => $this->form->tanggal_lahir,
            'jenis_kelamin' => $this->form->jenis_kelamin,
            'status_perkawinan' => $this->form->status_perkawinan,
            'gol_darah' => $this->form->gol_darah,
            'agama' => $this->form->agama,
            'jenis_identitas' => $this->form->jenis_identitas,
            'nik' => $this->form->nomorKTP,
            'visa' => $this->form->nomorVISA,
            'alamat_ktp' => $this->form->alamatKTP,
            'village_id_ktp' => $this->form->desaKTP,
            'alamat_domisili' => $this->form->alamatDomisili,
            'village_id_domisili' => $this->form->desaDomisili,
            'is_same_address' => $this->form->gunakanAlamatKTP,
            'nip_karyawan' => $this->form->nip_karyawan,
            'status_karyawan' => $this->form->status_karyawan,
            'tgl_masuk' => $this->form->tgl_masuk,
            'tgl_keluar' => $this->form->tgl_keluar,
            'entitas' => $this->form->entitas,
            'divisi' => $this->form->divisi,
            'jabatan' => $this->form->jabatan,
            'level' => $this->form->level,
            'sistem_kerja' => $this->form->sistem_kerja,
            // 'spv' => $this->form->spv,
            'total_upah' => $this->form->total_upah,
            'gaji_pokok' => $this->form->gaji_pokok,
            'tunjangan_jabatan' => $this->form->tunjangan_jabatan,
            'bonus' => $this->form->bonus,
            'inov_reward' => $this->form->inov_reward,
            'kasbon' => $this->form->kasbon,
            'voucher' => $this->form->voucher,
            'kebudayaan' => $this->form->kebudayaan,
            'transport' => $this->form->transport,
            'jenis_penggajian' => $this->form->jenis_penggajian,
            'nama_bank' => $namaBank,
            'no_rek' => $this->form->no_rek,
            'nama_pemilik_rekening' => $this->form->nama_pemilik_rekening,
            'no_bpjs_tk' => $this->form->no_bpjs_tk,
            'npp_bpjs_tk' => $this->form->npp_bpjs_tk,
            'tgl_aktif_bpjstk' => $this->form->tgl_aktif_bpjstk,
            'no_bpjs' => $this->form->no_bpjs,
            'anggota_bpjs' => $this->form->anggota_bpjs,
            'tgl_aktif_bpjs' => $this->form->tgl_aktif_bpjs,
            'penanggung' => $this->form->penanggung,
            'tax_status' => $this->form->tax_status,
            'tunjangan_coc' => $this->form->tunjangan_coc,
            'tunjangan_kinerja' => $this->form->tunjangan_kinerja,
            'insentif_tot' => $this->form->insentif_tot,
        ];
        // dd($data);
        User::where('id', $ticket->user_id)->update([
            'name' => $this->form->nama_karyawan,
            'email' => $this->form->email,
            'current_role' => strtolower($this->form->level) === 'staff' ? 'user' : strtolower($this->form->level),
        ]);
        $ticket->update($data);

        $this->form->reset();
        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modal-edit-data-karyawan', action: 'hide');
        $this->dispatch('refresh');
    }

    public function saveImport()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        // Ambil file dari temporary path
        $filePath = $this->file->getRealPath();

        // Import langsung dari file temporary
        Excel::import(new KaryawanImport, $filePath);
        $this->dispatch('swal', params: [
            'title' => 'Berhasil!',
            'text' => 'Data berhasil di-import.',
            'icon' => 'success'
        ]);

        $this->dispatch('modal-import', action: 'hide');
        $this->dispatch('refresh');
    }

    #[On('show-modal-edit-karyawan')]
    public function showEdit($id)
    {
        $dataKaryawan = M_DataKaryawan::find(Crypt::decrypt($id));

        if (!$dataKaryawan) {
            return;
        }

        $this->dispatch(
            'edit-ticket',
            data: $dataKaryawan->toArray()
        );

        $this->dispatch(
            'modal-edit-data-karyawan',
            action: 'show'
        );
    }

    public function render()
    {
        return view('livewire.karyawan.modal-karyawan');
    }
}
