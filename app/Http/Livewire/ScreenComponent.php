<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Screen;
use Illuminate\Http\Request;

class ScreenComponent extends Component
{
    use WithPagination;

    public $idScreen;
    public $screen;
    public $url;
    public $icon;
    public $is_menu = 1;
    public $is_sub_menu = 0;
    public $screen_id;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'screen' => $this->isEdit ? 'required|unique:screens,screen,' . $this->idScreen : 'required|unique:screens,screen',
        ];
    }

    private function data()
    {
        return [
            'screen' => $this->screen,
            'url' => $this->url,
            'icon' => $this->icon,
            'is_menu' => $this->is_menu,
            'is_sub_menu' => $this->is_sub_menu,
            'screen_id' => $this->screen_id ? $this->screen_id : null,
        ];
    }

    public function create()
    {
        $this->reset();
        $this->emit('screen_id', [
            'id' => '',
            'screen' => ''
        ]);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Screen::find($id);
        $this->idScreen = $id;
        $this->screen = $data->screen;
        $this->url = $data->url;
        $this->icon = $data->icon;
        $this->screen_id = $data->screen_id;
        $this->is_menu = $data->is_menu;
        $this->is_sub_menu = $data->is_sub_menu;
        $parent = Screen::find($data->screen_id);

        $this->isEdit = true;
        $this->emit('screen_id', [
            'id' => $parent ? $parent->id : null,
            'screen' => $parent ? $parent->screen : null
        ]);
        $this->resetValidation();
    }

    public function buttonSave()
    {
        if ($this->isEdit == false) {
            $this->store();
        } else {
            $this->update();
        }
    }

    private function store()
    {
        $this->validate();
        Screen::create($this->data());
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        Screen::find($this->idScreen)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Screen::with(['parentScreen'])
                ->where('screen', 'like', '%' . $this->search . '%')
                ->orWhere('url', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(5);
        } else {
            return Screen::with(['parentScreen'])
                ->latest()
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["screens"] = $this->read();
        $data["count_data"] = Screen::count();
        return view('livewire.screen-component', $data)->extends("layout.template");
    }

    public function getScreen(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $screens = Screen::latest()
                ->limit(50)
                ->get();
        } else {
            $screens = Screen::where('screen', 'like', '%' . $search . '%')
                ->latest()
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
