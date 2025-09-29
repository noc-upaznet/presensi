<?php

namespace App\Livewire\Karyawan;

use App\Models\M_AdditionalDataEmployee;
use Livewire\Component;
use App\Models\M_DataKaryawan;
use App\Models\M_Dependents;
use App\Models\M_Education;
use App\Models\M_Family;
use App\Models\M_WorkExperience;
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
    public $workExperience;

    public $field;
    public $label;
    public $values = [];
    public $editing = [];
    public $edit_id;

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
        ];
        // dd($data);

        M_Education::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahPendidikan', action: 'hide');
    }

    public function showAddExperience()
    {
        $this->dispatch('modalTambahExperience', action: 'show');
    }

    public function saveExperience()
    {
        $data = [
            'karyawan_id' => $this->id,
            'company' => $this->company,
            'employment_period' => $this->employment_period,
        ];
        // dd($data);

        M_WorkExperience::create($data);

        $this->dispatch('swal', params: [
            'title' => 'Data Saved',
            'icon' => 'success',
            'text' => 'Data has been saved successfully'
        ]);

        // Tutup modal
        $this->dispatch('modalTambahExperience', action: 'hide');
    }

    public function showEditKeluarga($id)
    {
        $dataFamily = M_Family::findOrFail($id);
        $this->edit_id = $id;
        // dd($this->edit_id);

        $this->relationships = $dataFamily->relationships;
        $this->name = $dataFamily->name;
        $this->nik = $dataFamily->nik;
        $this->gender = $dataFamily->gender;
        $this->place_of_birth = $dataFamily->place_of_birth;
        $this->date_of_birth = $dataFamily->date_of_birth;
        $this->religion = $dataFamily->religion;
        $this->education = $dataFamily->education;
        $this->dispatch('modalEditKeluarga', action: 'show');
    }

    public function updateKeluarga()
    {
        if ($this->edit_id) {
            $dataFamily = M_Family::findOrFail($this->edit_id);
            $data = [
                'relationships' => $this->relationships,
                'name' => $this->name,
                'nik' => $this->nik,
                'gender' => $this->gender,
                'place_of_birth' => $this->place_of_birth,
                'date_of_birth' => $this->date_of_birth,
                'religion' => $this->religion,
                'education' => $this->education,
            ];
            // dd($data);
            $dataFamily->update($data);
        }

        $this->reset(['relationships', 'name', 'nik', 'gender', 'place_of_birth', 'date_of_birth', 'religion', 'education']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditKeluarga', action: 'hide');
    }

    public function showEditRelationship($id)
    {
        $dataFamily = M_Family::findOrFail($id);
        $this->edit_id = $id;
        // dd($this->edit_id);

        $this->marital_status = $dataFamily->marital_status;
        $this->wedding_date = $dataFamily->wedding_date;
        $this->relationship_in_family = $dataFamily->relationship_in_family;
        $this->citizenship = $dataFamily->citizenship;
        $this->father = $dataFamily->father;
        $this->mother = $dataFamily->mother;

        $this->dispatch('modalEditRelationship', action: 'show');
    }

    public function updateRelationship()
    {
        if ($this->edit_id) {
            $dataFamily = M_Family::findOrFail($this->edit_id);
            $data = [
                'marital_status' => $this->marital_status,
                'wedding_date' => $this->wedding_date,
                'relationship_in_family' => $this->relationship_in_family,
                'citizenship' => $this->citizenship,
                'father' => $this->father,
                'mother' => $this->mother,
            ];
            // dd($data);
            $dataFamily->update($data);
        }

        $this->reset(['marital_status', 'wedding_date', 'relationship_in_family', 'citizenship', 'father', 'mother']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditRelationship', action: 'hide');
    }

    public function showEditTanggungan($id)
    {
        $dataDependent = M_Dependents::findOrFail($id);
        $this->edit_id = $id;
        // dd($this->edit_id);

        $this->relationships = $dataDependent->relationships;
        $this->name = $dataDependent->name;
        $this->gender = $dataDependent->gender;
        $this->place_of_birth = $dataDependent->place_of_birth;
        $this->date_of_birth = $dataDependent->date_of_birth;
        $this->education = $dataDependent->education;
        $this->profession = $dataDependent->profession;
        $this->no_telp = $dataDependent->no_telp;

        $this->dispatch('modalEditTanggungan', action: 'show');
    }

    public function updateTanggungan()
    {
        if ($this->edit_id) {
            $modalEditTanggungan = M_Dependents::findOrFail($this->edit_id);
            $data = [
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
            $modalEditTanggungan->update($data);
        }

        $this->reset(['relationships', 'name', 'gender', 'place_of_birth', 'date_of_birth', 'profession', 'education', 'no_telp']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditTanggungan', action: 'hide');
    }

    public function showEditEducation($id)
    {
        $dataEducation = M_Education::findOrFail($id);
        $this->edit_id = $id;
        // dd($this->edit_id);

        $this->level_of_education = $dataEducation->level_of_education;
        $this->institution = $dataEducation->institution;
        $this->start_date = $dataEducation->start_date;
        $this->end_date = $dataEducation->end_date;
        $this->major = $dataEducation->major;
        $this->nilai = $dataEducation->nilai;

        $this->dispatch('modalEditPendidikan', action: 'show');
    }

    public function updateEducation()
    {
        if ($this->edit_id) {
            $dataEducation = M_Education::findOrFail($this->edit_id);
            $data = [
                'level_of_education' => $this->level_of_education,
                'institution' => $this->institution,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'major' => $this->major,
                'nilai' => $this->nilai,
            ];
            // dd($data);
            $dataEducation->update($data);
        }

        $this->reset(['level_of_education', 'institution', 'start_date', 'end_date', 'major', 'nilai']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditPendidikan', action: 'hide');
    }

    public function showEditExperience($id)
    {
        $dataExperience = M_WorkExperience::findOrFail($id);
        $this->edit_id = $id;
        // dd($this->edit_id);

        $this->company = $dataExperience->company;
        $this->employment_period = $dataExperience->employment_period;

        $this->dispatch('modalEditExperience', action: 'show');
    }

    public function updateExperience()
    {
        if ($this->edit_id) {
            $dataExperience = M_WorkExperience::findOrFail($this->edit_id);
            $data = [
                'company' => $this->company,
                'employment_period' => $this->employment_period,
            ];
            // dd($data);
            $dataExperience->update($data);
        }

        $this->reset(['company', 'employment_period']);

        $this->dispatch('swal', params: [
            'title' => 'Data Updated',
            'icon' => 'success',
            'text' => 'Data has been updated successfully'
        ]);

        $this->dispatch('modalEditExperience', action: 'hide');
    }

    public function render()
    {
        $this->familys = M_Family::where('karyawan_id', $this->id)->get();
        $this->dependents = M_Dependents::where('karyawan_id', $this->id)->get();
        $this->educations = M_Education::where('karyawan_id', $this->id)->get();
        $this->workExperience = M_WorkExperience::where('karyawan_id', $this->id)->get();
        return view('livewire.karyawan.detail-data-karyawan', [
            'dataFamilys' => $this->familys,
            'dataDependents' => $this->dependents,
            'dataEducations' => $this->educations,
            'dataWorkExperience' => $this->workExperience,
        ]);
    }
}
