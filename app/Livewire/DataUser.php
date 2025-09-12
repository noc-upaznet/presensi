<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class DataUser extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $listeners = ['refreshTable' => '$refresh'];
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function togglePasswordExpired($userId)
    {
        $user = User::findOrFail($userId);
        $user->password_expired = !$user->password_expired;
        $user->save();
    }
    
    public function render()
    {
        $users = User::orderBy('id', 'asc')
            ->where('name', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view('livewire.data-user', [
            'users' => $users,
        ]);
    }
}
