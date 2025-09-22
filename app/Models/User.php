<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_role',
        'password_expired',
        'entitas_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function karyawan()
    {
        return $this->hasOne(M_DataKaryawan::class, 'user_id', 'id');
        // atau: return $this->belongsTo(DataKaryawan::class, 'karyawan_id');
    }

    public function entitas()
    {
        return $this->belongsTo(M_Entitas::class);
    }

    public function getPresensi()
    {
        return $this->hasMany(M_Presensi::class);
    }

    // public function roles()
    // {
    //     return $this->hasMany(UserRole::class);
    // }

    // public function hasRole($role)
    // {
    //     return $this->roles->pluck('role')->contains($role);
    // }

    public function branch()
    {
        return $this->belongsTo(M_Entitas::class);
    }

    public function branch_ids()
    {
        $branch_ids = DB::table('user_has_branches')->where('user_id', $this->id)->pluck('branch_id')->toArray();
        return $branch_ids;
    }
    public function branches()
    {
        $branch_ids = DB::table('user_has_branches')
        ->where('user_id', $this->id)
        ->pluck('branch_id')
        ->toArray();
        // dd([
        //     'user_id'    => $this->id,
        //     'branch_id' => $branch_ids,
        // ]);

        $branchs = M_Entitas::whereIn('id', $branch_ids)->get();
        // dd($branchs);
        return $branchs;
    }

    public function assignBranch($branch_ids)
    {
        DB::table('user_has_branches')->where('user_id', $this->id)->delete();
        $model = DB::table('user_has_branches');

        foreach ($branch_ids as $branch_id) {
            $model->updateOrInsert(['user_id' => $this->id, 'branch_id' => $branch_id]);
        }
    }
}