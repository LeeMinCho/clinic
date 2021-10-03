<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use App\Models\MenuUser;
use Livewire\Component;
use App\Models\User;

class MenuUserComponent extends Component
{
    use WithPagination;

    public $idMenuUser;
    public $user_id;
    public $menu_id;

    public $search;

    protected $paginationTheme = "bootstrap";
    protected $listeners = ['showUsers', 'delete'];

    public function rules()
    {
        return [
            'user_id' => 'required',
        ];
    }

    public function showUsers($menu_id)
    {
        $this->menu_id = $menu_id;
        $this->user_id = '';
        $this->emit('user_id', $this->user_id);
        $this->resetValidation();
        $this->emit('showPrimaryModalUser', $this->dataUser());
    }

    private function data()
    {
        return [
            'user_id' => $this->user_id,
            'menu_id' => $this->menu_id,
        ];
    }

    public function store()
    {
        $this->validate();
        MenuUser::create($this->data());
        $this->user_id = '';
        $this->emit('user_id', $this->user_id);
        $this->emit("btnSave", "Success Create Data!");
    }

    public function delete($id)
    {
        MenuUser::destroy($id);
        $this->emit("btnSave", "Success Delete Data!");
    }

    private function read()
    {
        if ($this->search) {
            return MenuUser::where('user_id', 'like', '%' . $this->search . '%')
                ->orWhere('menu_id', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return MenuUser::orderBy('id', 'desc')
                ->when($this->menu_id, function ($query, $menu_id) {
                    return $query->where('menu_id', $menu_id);
                })
                ->paginate(5);
        }
    }

    private function dataUser()
    {
        if ($this->menu_id) {
            $userInMenu = MenuUser::where('menu_id', $this->menu_id)
                ->get();
            $userId = [];
            foreach ($userInMenu as $value) {
                $userId[] = $value->user_id;
            }
            return User::whereNotIn('id', $userId)
                ->get();
        } else {
            return [];
        }
    }

    public function render()
    {
        $data["menu_users"] = $this->read();
        $data["count_data"] = $this->menu_id ? MenuUser::where('menu_id', $this->menu_id)->count() : 0;
        $data['users'] = $this->dataUser();
        return view('livewire.menu-user-component', $data);
    }
}
