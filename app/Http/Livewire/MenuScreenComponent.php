<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\WithPagination;
use App\Models\MenuScreen;
use Livewire\Component;
use App\Models\Screen;

class MenuScreenComponent extends Component
{
    use WithPagination;

    public $idMenuScreen;
    public $screen_id;
    public $menu_id;

    public $search;

    protected $paginationTheme = "bootstrap";
    protected $listeners = ['showScreens', 'deleteScreen' => 'delete'];

    public function rules()
    {
        return [
            'screen_id' => 'required',
        ];
    }

    private function data()
    {
        return [
            'screen_id' => $this->screen_id,
            'menu_id' => $this->menu_id,
        ];
    }

    public function showScreens($menu_id)
    {
        $this->menu_id = $menu_id;
        $this->screen_id = '';
        $this->emit('screen_id', $this->screen_id);
        $this->resetValidation();
        $this->emit('showPrimaryModalScreen');
    }

    public function store()
    {
        $this->validate();
        MenuScreen::create($this->data());
        $this->screen_id = '';
        $this->emit('screen_id', $this->screen_id);
        $this->emit("btnSave", "Success Create Data!");
    }

    public function delete($id)
    {
        MenuScreen::destroy($id);
        $this->emit("btnSave", "Success Delete Data!");
    }

    private function read()
    {
        if ($this->search) {
            return MenuScreen::where('user_id', 'like', '%' . $this->search . '%')
                ->orWhere('menu_id', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return MenuScreen::orderBy('id', 'desc')
                ->when($this->menu_id, function ($query, $menu_id) {
                    return $query->where('menu_id', $menu_id);
                })
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["menu_screens"] = $this->read();
        $data["count_data"] = $this->menu_id ? MenuScreen::where('menu_id', $this->menu_id)->count() : 0;
        return view('livewire.menu-screen-component', $data);
    }

    public function getScreen(Request $request)
    {
        $screenId = [];
        $screenInMenu = MenuScreen::where('menu_id', $request->menu_id)
            ->get();
        if ($screenInMenu) {
            foreach ($screenInMenu as $value) {
                $screenId[] = $value->screen_id;
            }
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
