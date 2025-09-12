<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{

    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $namePermission = '';

    public function store()
    {
        $this->validate([
            'namePermission' => 'required'
        ]);

        Permission::create(['name' => $this->namePermission]);

        $this->dispatch('update-permission');
        $this->namePermission = '';
        $this->dispatch('modal-permission', action: 'hide');
    }

    public function showEdit($id)
    {
        $this->namePermission = Permission::find(Crypt::decrypt($id))->name;

        $this->dispatch('modal-permission', action: 'show', method: 'edit', id: $id);
    }

    public function edit($id)
    {
        $this->validate([
            'namePermission' => 'required'
        ]);

        $permission = Permission::find(Crypt::decrypt($id));
        $permission->name = $this->namePermission;
        $permission->save();

        $this->dispatch('swal', params: [
            'title' => 'Success',
            'html' => 'Permission updated successfully',
            'icon' => 'success',
        ]);
        $this->namePermission = '';
        $this->dispatch('update-permission');
        $this->dispatch('modal-permission', action: 'hide');
    }

    public function delete($id)
    {
        Permission::find(Crypt::decrypt($id))->delete();
        $this->dispatch('swal', params: [
            'title' => 'Success',
            'html' => 'Permission deleted successfully',
            'icon' => 'success',
        ]);
        $this->dispatch('update-permission');
        $this->dispatch('modal-confirm-delete-permission', action: 'hide');
    }


    public function render()
    {
        return view('livewire.user.permissions', ['permissions' => Permission::orderBy('name', 'asc')->paginate(10)]);
    }
}