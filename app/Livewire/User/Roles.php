<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    use WithPagination, WithoutUrlPagination;


    public $permissions;
    public $permission_categories;

    public $roleName = '';
    public $selectedPermissions = [];

    public function showAdd()
    {
        $this->roleName = '';
        $this->selectedPermissions = [];
        $this->dispatch('modal-role', action: 'show');
    }


    public function store()
    {
        $this->validate([
            'roleName' => 'required',
            'selectedPermissions' => 'required',
        ]);

        $role = \Spatie\Permission\Models\Role::create(['name' => $this->roleName]);

        $role->syncPermissions($this->selectedPermissions);


        $this->roleName = '';
        $this->selectedPermissions = [];
        $this->dispatch('update-role');
        $this->dispatch('modal-role', action: 'hide');
    }


    public function showEdit($id)
    {
        $role = \Spatie\Permission\Models\Role::find(Crypt::decrypt($id));
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->dispatch('modal-role', action: 'show', method: 'edit', id: $id);
    }

    public function edit($id)
    {
        $this->validate([
            'roleName' => 'required',
            'selectedPermissions' => 'required',
        ]);

        $role = \Spatie\Permission\Models\Role::find(Crypt::decrypt($id));
        $role->name = $this->roleName;
        $role->save();

        $role->syncPermissions($this->selectedPermissions);


        $this->roleName = '';
        $this->selectedPermissions = [];
        $this->dispatch('update-role');
        $this->dispatch('modal-role', action: 'hide');
    }

    public function delete($id)
    {
        $role = \Spatie\Permission\Models\Role::find(Crypt::decrypt($id));
        $role->delete();
        $this->dispatch('swal', params: [
            'title' => 'Role Deleted',
            'icon' => 'success',
            'text' => 'Role has been deleted successfully'
        ]);
        $this->dispatch('update-role');
        $this->dispatch('modal-confirm-delete-role', action: 'hide');
    }

    #[On('update-permission')]
    public function mount()
    {

        foreach (Permission::orderBy('name', 'asc')->get() as $key => $permission) {
            preg_match('/^([^-]*)-/', $permission->name, $output_array);
            $this->permission_categories[$key] = $output_array[1];
        }

        $this->permission_categories = array_unique($this->permission_categories);

        foreach ($this->permission_categories as $key => $category) {
            $this->permissions[$category] = Permission::orderBy('name', 'asc')->where('name', 'like', $category . '-%')->get();
        }
    }
    public function render()
    {
        return view('livewire.user.roles', ['roles' =>  \Spatie\Permission\Models\Role::orderBy('name', 'asc')->paginate(10)]);
    }
}