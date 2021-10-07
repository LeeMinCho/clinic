<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Screen;
use App\Models\Menu;

class MenuScreenComponent extends Component
{
    use WithPagination;

    public $idMenuScreen;
    public $screen_id;
    public $menu_id;
    public $menu_name;

    public $search;

    protected $paginationTheme = "bootstrap";
    protected $listeners = ['showScreens', 'deleteScreen' => 'delete'];

    public function rules()
    {
        return [
            'screen_id' => 'required',
        ];
    }

    public function showScreens($menu_id)
    {
        $menu = Menu::findOrFail($menu_id);
        $this->menu_name = $menu->menu;
        $this->menu_id = $menu_id;
        $this->screen_id = '';
        $this->emit('screen_id', $this->screen_id);
        $this->resetValidation();
        $this->emit('showPrimaryModalScreen');
    }

    public function store()
    {
        $this->validate();
        $menu = Menu::findOrFail($this->menu_id);
        $menu->screens()->attach($this->screen_id);
        $this->screen_id = '';
        $this->emit('screen_id', $this->screen_id);
        $this->emit("btnSave", "Success Create Data!");
    }

    public function delete($id)
    {
        $menu = Menu::findOrFail($this->menu_id);
        $menu->screens()->detach($id);
        $this->emit("btnSave", "Success Delete Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Screen::with(['menus'])
                ->whereHas('menus', function ($query) {
                    return $query->where('menus.id', $this->menu_id);
                })
                ->where('screen', $this->search)
                ->latest()
                ->paginate(5);
        } else {
            return Screen::with(['menus'])
                ->whereHas('menus', function ($query) {
                    return $query->where('menus.id', $this->menu_id);
                })
                ->latest()
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["screens"] = $this->read();
        $data["count_data"] = Screen::with(['menus'])
            ->whereHas('menus', function ($query) {
                return $query->where('menus.id', $this->menu_id);
            })
            ->count();
        return view('livewire.menu-screen-component', $data);
    }

    public function getScreen(Request $request)
    {
        $screenId = [];
        $menu = Menu::findOrFail($request->menu_id);
        foreach ($menu->screens as $screen) {
            $screenId[] = $screen->pivot->screen_id;
        }

        $search = $request->search;
        if ($search == '') {
            $screens = Screen::whereNotIn('id', $screenId)
                ->limit(50)
                ->get();
        } else {
            $screens = Screen::whereNotIn('id', $screenId)
                ->where('screen', 'like', '%' . $search . '%')
                ->limit(50)
                ->get();
        }
        $response = [];
        if ($screens) {
            foreach ($screens as $screen) {
                $response[] = [
                    'id' => $screen->id,
                    'text' => $screen->screen
                ];
            }
        }
        return response()->json($response);
    }
}
