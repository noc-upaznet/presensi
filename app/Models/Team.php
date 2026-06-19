<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custpanel';
    protected $table = 'teams';
    protected $fillable = [
        'branch_id',
        'name',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'team_has_users', 'team_id', 'user_id');
    }

    public static function getTeamByUser($user_id)
    {
        $hasTeam = DB::table('team_has_users')
            ->select('team_id')
            ->where('user_id', $user_id)
            ->first();
        if ($hasTeam) {
            return self::find($hasTeam->team_id);
        } else {
            return false;
        }
    }
}
