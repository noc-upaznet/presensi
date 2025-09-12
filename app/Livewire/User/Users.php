<?php

namespace App\Livewire\User;

use App\Livewire\Forms\UserForm;
use App\Models\Branch;
use App\Models\M_Entitas;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Users extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public UserForm $form;
    public $user_roles;
    public $user_permissions;
    public $user_branches;

    public $tableLength = 25;
    public $tableSearch = '';


    public function showCreate()
    {
        $this->form->reset();
        $this->form->resetValidation();
        $this->dispatch('modal-add-user', action: 'show');
    }

    public function store()
    {
        $this->form->validate();
        $data = [
            'name' => $this->form->name,
            'email' => $this->form->email,
            'password' => bcrypt($this->form->password),
            'entitas_id' => M_Entitas::where('nama', session('selected_entitas', 'UHO'))->value('id')
        ];
        // dd($data);
        // dd([
        //     'data_user'      => $data,
        //     'roles_dari_form' => $this->form->user_roles,
        //     'permissions_dari_form' => $this->form->user_permissions,
        //     'branches_dari_form' => $this->form->user_branches,
        // ]);
        $user = User::create($data);

        $user->assignRole($this->form->user_roles);
        $user->syncPermissions($this->form->user_permissions);
        $user->assignBranch($this->form->user_branches);

        $this->form->reset();
        $this->dispatch('swal', params: [
            'title' => 'User Created',
            'icon' => 'success',
            'text' => 'User has been created successfully'
        ]);
        $this->dispatch('modal-add-user', action: 'hide');
    }

    public function showEdit($id)
    {
        $this->form->resetValidation();
        $this->form->fill(User::find(Crypt::decrypt($id))->toArray());
        $this->form->user_roles = User::find(Crypt::decrypt($id))->getRoleNames();
        $this->form->user_permissions = User::find(Crypt::decrypt($id))->getPermissionNames();
        $this->form->user_branches = User::find(Crypt::decrypt($id))->branches()->pluck('id');
        // dd($this->form->user_branches);
        $this->dispatch('modal-edit-user', action: 'show', user_roles: $this->form->user_roles, user_branches: $this->form->user_branches);
    }

    public function edit()
    {
        $this->form->validate([
            'name' => 'required',
            'email' => 'required|email',
            'user_roles' => 'required',
            'user_branches' => 'required'
        ]);

        $user = User::find($this->form->id);
        $user->update([
            'name' => $this->form->name,
            'email' => $this->form->email,
        ]);

        $user->syncRoles($this->form->user_roles);
        $user->syncPermissions($this->form->user_permissions);
        $user->assignBranch($this->form->user_branches);
        $this->form->reset();
        $this->dispatch('swal', params: [
            'title' => 'User Updated',
            'icon' => 'success',
            'text' => 'User has been updated successfully'
        ]);
        $this->dispatch('modal-edit-user', action: 'hide');
    }

    public function showEditPassword($id)
    {
        $this->form->resetValidation();
        $this->form->fill(User::find(Crypt::decrypt($id))->toArray());
        $this->dispatch('modal-edit-password', action: 'show');
    }

    public function editPassword()
    {
        $this->form->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::find($this->form->id);
        $user->update([
            'password' => bcrypt($this->form->password),
        ]);

        $this->form->reset();
        $this->dispatch('swal', params: [
            'title' => 'Password Updated',
            'icon' => 'success',
            'text' => 'Password has been updated successfully'
        ]);
        $this->dispatch('modal-edit-password', action: 'hide');
    }

    public function delete($id)
    {
        User::find(Crypt::decrypt($id))->delete();
        $this->dispatch('swal', params: [
            'title' => 'User Deleted',
            'icon' => 'success',
            'text' => 'User has been deleted successfully'
        ]);
        $this->dispatch('modal-confirm-delete', action: 'hide');
    }

    #[On('update-role')]
    public function mount()
    {
        $this->user_roles = Role::pluck('name', 'name')->all();
        $this->user_permissions = Permission::pluck('name', 'name')->all();
        $this->user_branches = M_Entitas::pluck('nama', 'id')->all();
        // session('selected_entitas', 'UHO');
    }

    public function render()
    {
        $entitas = session('selected_entitas', 'UHO');
        $this->user_branches = M_Entitas::all();
        // dd($this->user_branches);
        $users = User::where('entitas_id', M_Entitas::where('nama', $entitas)->value('id'))
            ->whereAny([
                'name'
            ], 'like', '%' . $this->tableSearch . '%')
            ->paginate($this->tableLength);
        // dd($users);

        return view('livewire.user.users', ['users' => $users]);
    }
}
