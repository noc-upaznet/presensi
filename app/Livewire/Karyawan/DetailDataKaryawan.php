<?php

namespace App\Livewire\Karyawan;

use App\Models\M_AdditionalDataEmployee;
use Livewire\Component;
use App\Models\M_DataKaryawan;
use App\Models\M_Dependents;
use App\Models\M_EducationExperience;
use App\Models\M_Family;
use Illuminate\Support\Facades\Crypt;

class DetailDataKaryawan extends Component
{
    public $jml_poin;

    public $id;
    public $karyawan;
    public $familys;
    public $dependents;
    public $educations;
    public $relationships;
    public $name;
    public $nik;
    public $gender;
    public $place_of_birth;
    public $date_of_birth;
    public $religion;
    public $education;
    public $marital_status;
    public $wedding_date;
    public $relationship_in_family;
    public $citizenship;
    public $father;
    public $mother;

    public $no_telp;
    public $profession;

    public $level_of_education;
    public $institution;
    public $start_date;
    public $end_date;
    public $major;
    public $nilai;
    public $company;
    public $employment_period;

    public $field;
    public $label;
    public $values = [];
    public $editing = [];

    protected $rules = [
        'value' => 'nullable|string|max:255',
    ];

    public function mount($id)
    {
        $this->id = Crypt::decrypt($id);
        $this->karyawan = M_DataKaryawan::find($this->id);

        if (!$this->karyawan) {
            abort(404);
        }

        $data = M_AdditionalDataEmployee::where('karyawan_id', $this->id)->first();

        $this->values = [
            'dress_size'        => $data->dress_size ?? '',
            'shoe_size'         => $data->shoe_size ?? '',
            'height'            => $data->height ?? '',
            'weight'            => $data->weight ?? '',
            'nip'               => $data->nip ?? '',
            'start_date'        => $data->start_date ?? '',
            'personality'       => $data->personality ?? '',
            'iq'                => $data->iq ?? '',
            'parent_address'    => $data->parent_address ?? '',
            'inlaw_address'     => $data->inlaw_address ?? '',
            'history_of_illness'=> $data->history_of_illness ?? '',
            'name_father_in_law'=> $data->name_father_in_law ?? '',
            'name_mother_in_law'=> $data->name_mother_in_law ?? '',
        ];

        // set semua editing = false
        foreach ($this->values as $key => $val) {
            $this->editing[$key] = false;
        }
    }

    public function toggleEdit($field)
    {
        // kalau sedang edit → simpan dulu
        if ($this->editing[$field]) {
            M_AdditionalDataEmployee::updateOrCreate(
                ['karyawan_id' => $this->id],
                [$field => $this->values[$field]]
            );

            $this->dispatch('swal', params: [
                'title' => 'Tersimpan',
                'icon'  => 'success',
                'text'  => 'Data berhasil disimpan'
            ]);
        }

        $this->editing[$field] = ! $this->editing[$field];
    }
    
    public function updateGamifikasi()
    {
        $this->validate([
            'jml_poin' => 'required|numeric|min:0',
        ]);

        // Ambil poin lama lalu tambahkan
        $poinLama = $this->karyawan->poin ?? 0;
        $poinBaru = $poinLama + $this->jml_poin;

        $this->karyawan->update([
            'poin' => $poinBaru,
        ]);
        $this->reset('jml_poin');

        $this->dispatch('swal', params: [
            'title' => 'Poin Berhasil Ditambahkan',
            'icon' => 'success',
            'text' => "Poin bertambah, Total sekarang: $poinBaru"
        ]);
    }

    public function showAdd()
    {
        $this->dispatch('modalTambahKeluarga', action: 'show');
    }

    public function saveKeluarga()
    {
        $data = [
            'karyawan_id' => $this->id,
            'relationships' => $this->relationships,
            'name' => $this->name,
            'nik' => $this->nik,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => $this->date_of_birth,
            'religion' => $this->religion,
            'education' => $this->education,
            'marital_status' => $this->marital_status,
            'wedding_date' => $this->wedding_date,
            'relationship_in_family' => $this->relationship_in_family,
            'citizenship' => $this->citizenship,
            'father' => $this->father,
            'mother' => $this->mother,
        ];
        // dd($data);

        M_Family::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahKeluarga', action: 'hide');
    }

    public function showAddTanggungan()
    {
        $this->dispatch('modalTambahTanggungan', action: 'show');
    }

    public function saveTanggungan()
    {
        $data = [
            'karyawan_id' => $this->id,
            'relationships' => $this->relationships,
            'name' => $this->name,
            'gender' => $this->gender,
            'place_of_birth' => $this->place_of_birth,
            'date_of_birth' => $this->date_of_birth,
            'education' => $this->education,
            'profession' => $this->profession,
            'no_telp' => $this->no_telp,
        ];
        // dd($data);

        M_Dependents::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahTanggungan', action: 'hide');
    }

    public function showAddPendidikan()
    {
        $this->dispatch('modalTambahPendidikan', action: 'show');
    }

    public function savePendidikan()
    {
        $data = [
            'karyawan_id' => $this->id,
            'level_of_education' => $this->level_of_education,
            'institution' => $this->institution,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'major' => $this->major,
            'nilai' => $this->nilai,
            'company' => $this->company,
            'employment_period' => $this->employment_period,
        ];
        // dd($data);

        M_EducationExperience::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPendidikan', action: 'hide');
    }



    public function render()
    {
        $this->familys = M_Family::where('karyawan_id', $this->id)->get();
        $this->dependents = M_Dependents::where('karyawan_id', $this->id)->get();
        $this->educations = M_EducationExperience::where('karyawan_id', $this->id)->get();
        return view('livewire.karyawan.detail-data-karyawan', [
            'dataFamilys' => $this->familys,
            'dataDependents' => $this->dependents,
            'dataEducations' => $this->educations,
        ]);
    }
}
