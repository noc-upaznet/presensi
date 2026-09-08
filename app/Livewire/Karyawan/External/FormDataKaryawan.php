<?php

namespace App\Livewire\Karyawan\External;

use App\Models\EmployeeDataLink;
use App\Models\M_DataKaryawan;
use App\Models\M_Dependents;
use App\Models\M_Education;
use App\Models\M_Family;
use App\Models\M_WorkExperience;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FormDataKaryawan extends Component
{
    public $token;
    public $karyawan;
    public $karyawanId;

    public $activeTab = 'family';
    public $familys = [];
    public $dependents = [];
    public $educations = [];
    public $workExperiences = [];

    public function mount($token)
    {
        $this->token = $token;

        $link = EmployeeDataLink::where('token', $token)
            ->where('is_active', true)
            ->first();

        if (!$link) {
            abort(404, 'Link pengisian data tidak ditemukan.');
        }

        if (
            $link->expires_at &&
            $link->expires_at->isPast()
        ) {
            abort(403, 'Link pengisian data sudah kedaluwarsa.');
        }

        $link->update([
            'last_accessed_at' => now(),
        ]);

        $this->karyawanId = $link->karyawan_id;

        $this->karyawan = M_DataKaryawan::find($this->karyawanId);

        if (!$this->karyawan) {
            abort(404, 'Data karyawan tidak ditemukan.');
        }

        $this->loadData();
    }

    public function loadData()
    {
        /*
        |--------------------------------------------------------------------------
        | FAMILY
        |--------------------------------------------------------------------------
        */

        $this->familys = M_Family::where(
            'karyawan_id',
            $this->karyawanId
        )
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'relationships' => $item->relationships,
                    'name' => $item->name,
                    'nik' => $item->nik,
                    'gender' => $item->gender,
                    'place_of_birth' => $item->place_of_birth,
                    'date_of_birth' => $item->date_of_birth,
                    'religion' => $item->religion,
                    'education' => $item->education,
                    'marital_status' => $item->marital_status,
                    'wedding_date' => $item->wedding_date,
                    'relationship_in_family' => $item->relationship_in_family,
                    'citizenship' => $item->citizenship,
                    'father' => $item->father,
                    'mother' => $item->mother,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | DEPENDENTS
        |--------------------------------------------------------------------------
        */

        $this->dependents = M_Dependents::where(
            'karyawan_id',
            $this->karyawanId
        )
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'relationships' => $item->relationships,
                    'name' => $item->name,
                    'gender' => $item->gender,
                    'place_of_birth' => $item->place_of_birth,
                    'date_of_birth' => $item->date_of_birth,
                    'education' => $item->education,
                    'profession' => $item->profession,
                    'no_telp' => $item->no_telp,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | EDUCATION
        |--------------------------------------------------------------------------
        */

        $this->educations = M_Education::where(
            'karyawan_id',
            $this->karyawanId
        )
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'level_of_education' => $item->level_of_education,
                    'institution' => $item->institution,
                    'start_date' => $item->start_date,
                    'end_date' => $item->end_date,
                    'major' => $item->major,
                    'nilai' => $item->nilai,
                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | WORK EXPERIENCE
        |--------------------------------------------------------------------------
        */

        $this->workExperiences = M_WorkExperience::where(
            'karyawan_id',
            $this->karyawanId
        )
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'company' => $item->company,
                    'employment_period' => $item->employment_period,
                ];
            })
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | TAB
    |--------------------------------------------------------------------------
    */

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    /*
    |--------------------------------------------------------------------------
    | ADD FAMILY
    |--------------------------------------------------------------------------
    */

    public function addFamily()
    {
        $this->familys[] = [
            'id' => null,
            'relationships' => '',
            'name' => '',
            'nik' => '',
            'gender' => '',
            'place_of_birth' => '',
            'date_of_birth' => '',
            'religion' => '',
            'education' => '',
            'marital_status' => '',
            'wedding_date' => '',
            'relationship_in_family' => '',
            'citizenship' => '',
            'father' => '',
            'mother' => '',
        ];
    }

    public function removeFamily($index)
    {
        if (
            isset($this->familys[$index]['id']) &&
            $this->familys[$index]['id']
        ) {
            M_Family::where('id', $this->familys[$index]['id'])
                ->where('karyawan_id', $this->karyawanId)
                ->delete();
        }

        unset($this->familys[$index]);

        $this->familys = array_values($this->familys);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD DEPENDENT
    |--------------------------------------------------------------------------
    */

    public function addDependent()
    {
        $this->dependents[] = [
            'id' => null,
            'relationships' => '',
            'name' => '',
            'gender' => '',
            'place_of_birth' => '',
            'date_of_birth' => '',
            'education' => '',
            'profession' => '',
            'no_telp' => '',
        ];
    }

    public function removeDependent($index)
    {
        if (
            isset($this->dependents[$index]['id']) &&
            $this->dependents[$index]['id']
        ) {
            M_Dependents::where(
                'id',
                $this->dependents[$index]['id']
            )
                ->where(
                    'karyawan_id',
                    $this->karyawanId
                )
                ->delete();
        }

        unset($this->dependents[$index]);

        $this->dependents = array_values($this->dependents);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD EDUCATION
    |--------------------------------------------------------------------------
    */

    public function addEducation()
    {
        $this->educations[] = [
            'id' => null,
            'level_of_education' => '',
            'institution' => '',
            'start_date' => '',
            'end_date' => '',
            'major' => '',
            'nilai' => '',
        ];
    }

    public function removeEducation($index)
    {
        if (
            isset($this->educations[$index]['id']) &&
            $this->educations[$index]['id']
        ) {
            M_Education::where(
                'id',
                $this->educations[$index]['id']
            )
                ->where(
                    'karyawan_id',
                    $this->karyawanId
                )
                ->delete();
        }

        unset($this->educations[$index]);

        $this->educations = array_values($this->educations);
    }

    /*
    |--------------------------------------------------------------------------
    | ADD WORK EXPERIENCE
    |--------------------------------------------------------------------------
    */

    public function addWorkExperience()
    {
        $this->workExperiences[] = [
            'id' => null,
            'company' => '',
            'employment_period' => '',
        ];
    }

    public function removeWorkExperience($index)
    {
        if (
            isset($this->workExperiences[$index]['id']) &&
            $this->workExperiences[$index]['id']
        ) {
            M_WorkExperience::where(
                'id',
                $this->workExperiences[$index]['id']
            )
                ->where(
                    'karyawan_id',
                    $this->karyawanId
                )
                ->delete();
        }

        unset($this->workExperiences[$index]);

        $this->workExperiences = array_values(
            $this->workExperiences
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE ALL
    |--------------------------------------------------------------------------
    */

    public function saveAll()
    {
        $this->validate([
            'familys.*.name' => 'nullable|string|max:255',
            'familys.*.nik' => 'nullable|string|max:50',
            'familys.*.date_of_birth' => 'nullable|date',
            'familys.*.wedding_date' => 'nullable|date',

            'dependents.*.name' => 'nullable|string|max:255',
            'dependents.*.date_of_birth' => 'nullable|date',

            'educations.*.institution' => 'nullable|string|max:255',
            'educations.*.start_date' => 'nullable',
            'educations.*.end_date' => 'nullable',
            'educations.*.nilai' => 'nullable',

            'workExperiences.*.company' => 'nullable|string|max:255',
            'workExperiences.*.employment_period' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | FAMILY
            |--------------------------------------------------------------------------
            */

            foreach ($this->familys as $family) {

                if (
                    empty($family['name']) &&
                    empty($family['relationships'])
                ) {
                    continue;
                }

                $data = [
                    'karyawan_id' => $this->karyawanId,
                    'relationships' => $family['relationships'],
                    'name' => $family['name'],
                    'nik' => $family['nik'],
                    'gender' => $family['gender'],
                    'place_of_birth' => $family['place_of_birth'],
                    'date_of_birth' => $family['date_of_birth'],
                    'religion' => $family['religion'],
                    'education' => $family['education'],
                    'marital_status' => $family['marital_status'],
                    'wedding_date' => $family['wedding_date'],
                    'relationship_in_family' => $family['relationship_in_family'],
                    'citizenship' => $family['citizenship'],
                    'father' => $family['father'],
                    'mother' => $family['mother'],
                ];

                if (!empty($family['id'])) {

                    M_Family::where('id', $family['id'])
                        ->where(
                            'karyawan_id',
                            $this->karyawanId
                        )
                        ->update($data);
                } else {

                    M_Family::create($data);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | DEPENDENTS
            |--------------------------------------------------------------------------
            */

            foreach ($this->dependents as $dependent) {

                if (
                    empty($dependent['name']) &&
                    empty($dependent['relationships'])
                ) {
                    continue;
                }

                $data = [
                    'karyawan_id' => $this->karyawanId,
                    'relationships' => $dependent['relationships'],
                    'name' => $dependent['name'],
                    'gender' => $dependent['gender'],
                    'place_of_birth' => $dependent['place_of_birth'],
                    'date_of_birth' => $dependent['date_of_birth'],
                    'education' => $dependent['education'],
                    'profession' => $dependent['profession'],
                    'no_telp' => $dependent['no_telp'],
                ];

                if (!empty($dependent['id'])) {

                    M_Dependents::where('id', $dependent['id'])
                        ->where(
                            'karyawan_id',
                            $this->karyawanId
                        )
                        ->update($data);
                } else {

                    M_Dependents::create($data);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | EDUCATION
            |--------------------------------------------------------------------------
            */

            foreach ($this->educations as $education) {

                if (
                    empty($education['institution']) &&
                    empty($education['level_of_education'])
                ) {
                    continue;
                }

                $data = [
                    'karyawan_id' => $this->karyawanId,
                    'level_of_education' => $education['level_of_education'],
                    'institution' => $education['institution'],
                    'start_date' => $education['start_date'],
                    'end_date' => $education['end_date'],
                    'major' => $education['major'],
                    'nilai' => $education['nilai'],
                ];

                if (!empty($education['id'])) {

                    M_Education::where('id', $education['id'])
                        ->where(
                            'karyawan_id',
                            $this->karyawanId
                        )
                        ->update($data);
                } else {

                    M_Education::create($data);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | WORK EXPERIENCE
            |--------------------------------------------------------------------------
            */

            foreach ($this->workExperiences as $experience) {

                if (empty($experience['company'])) {
                    continue;
                }

                $data = [
                    'karyawan_id' => $this->karyawanId,
                    'company' => $experience['company'],
                    'employment_period' => $experience['employment_period'],
                ];

                if (!empty($experience['id'])) {

                    M_WorkExperience::where(
                        'id',
                        $experience['id']
                    )
                        ->where(
                            'karyawan_id',
                            $this->karyawanId
                        )
                        ->update($data);
                } else {

                    M_WorkExperience::create($data);
                }
            }
        });

        $this->loadData();

        session()->flash(
            'success',
            'Data berhasil disimpan.'
        );
    }

    public function render()
    {
        return view('livewire.karyawan.external.form-data-karyawan')->layout('layouts.guest');
    }
}
