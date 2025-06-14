<?php

namespace App\Livewire\Navigation;

use Livewire\Component;
use App\Models\M_Pengajuan;
use Illuminate\Support\Facades\Auth;

class SideNavigation extends Component
{
    public function render()
    {
        $user = Auth::user();
        $count = 0;

        if ($user) {
            if ($user->role === 'spv') {
                $count = M_Pengajuan::where('approve_spv', null)->where('status', 0)->count();
            } elseif ($user->role === 'hr') {
                $count = M_Pengajuan::where('approve_spv', 1)->where('approve_hr', null)->where('status', 0)->count();
            }
        }

        return view('livewire.navigation.side-navigation', [
            'pengajuanMenungguCount' => $count,
        ]);
    }
}
