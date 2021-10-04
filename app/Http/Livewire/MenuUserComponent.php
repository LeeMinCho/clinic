<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
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
    protected $listeners = ['showUsers', 'deleteUser' => 'delete'];

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
        $this->emit('showPrimaryModalUser');
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

    public function render()
    {
        $data["menu_users"] = $this->read();
        $data["count_data"] = $this->menu_id ? MenuUser::where('menu_id', $this->menu_id)->count() : 0;
        return view('livewire.menu-user-component', $data);
    }

    public function getUser(Request $request)
    {
        $userId = [];
        $userInMenu = MenuUser::where('menu_id', $request->menu_id)
            ->get();
        if ($userInMenu) {
            foreach ($userInMenu as $value) {
                $userId[] = $value->user_id;
            }
        }

        $search = $request->search;
        if ($search == '') {
            $users = User::whereNotIn('id', $userId)
                ->limit(50)
                ->get();
        } else {
            $users = User::whereNotIn('id', $userId)
                ->where('screen', 'like', '%' . $search . '%')
                ->limit(50)
                ->get();
        }
        $response = [];
        if ($users) {
            foreach ($users as $user) {
                $response[] = [
                    'id' => $user->id,
                    'text' => $user->fullname
                ];
            }
        }
        return response()->json($response);
    }
}
