<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Menu;
use App\Models\User;

class MenuUserComponent extends Component
{
    use WithPagination;

    public $idMenuUser;
    public $user_id;
    public $menu_id;
    public $menu_name;

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
        $menu = Menu::findOrFail($menu_id);
        $this->menu_name = $menu->menu;
        $this->menu_id = $menu_id;
        $this->user_id = '';
        $this->emit('user_id', $this->user_id);
        $this->resetValidation();
        $this->emit('showPrimaryModalUser');
    }

    public function store()
    {
        $this->validate();
        $menu = Menu::findOrFail($this->menu_id);
        $menu->users()->attach($this->user_id);
        $this->user_id = '';
        $this->emit('user_id', $this->user_id);
        $this->emit("btnSave", "Success Create Data!");
    }

    public function delete($id)
    {
        $menu = Menu::findOrFail($this->menu_id);
        $menu->users()->detach($id);
        $this->emit("btnSave", "Success Delete Data!");
    }

    private function read()
    {
        if ($this->search) {
            return User::with(['menus'])
                ->whereHas('menus', function ($query) {
                    return $query->where('menus.id', $this->menu_id);
                })
                ->where('username', 'like', '%' . $this->search . '%')
                ->orWhere('fullname', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(5);
        } else {
            return User::with(['menus'])
                ->whereHas('menus', function ($query) {
                    return $query->where('menus.id', $this->menu_id);
                })
                ->latest()
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["users"] = $this->read();
        $data["count_data"] = User::with(['menus'])
            ->whereHas('menus', function ($query) {
                return $query->where('menus.id', $this->menu_id);
            })
            ->count();
        return view('livewire.menu-user-component', $data);
    }

    public function getUser(Request $request)
    {
        $userId = [];
        $menu = Menu::findOrFail($request->menu_id);
        foreach ($menu->users as $user) {
            $userId[] = $user->pivot->user_id;
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
