<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Screen;

class ScreenComponent extends Component
{
    use WithPagination;

    public $idScreen;
    public $screen;
    public $url;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'screen' => $this->isEdit ? 'required|unique:screens,screen,' . $this->idScreen : 'required|unique:screens,screen',
            'url' => 'required',
        ];
    }

    private function data()
    {
        return [
            'screen' => $this->screen,
            'url' => $this->url,
        ];
    }

    public function create()
    {
        $this->idScreen = '';
        $this->screen = '';
        $this->url = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Screen::find($id);
        $this->idScreen = $id;
        $this->screen = $data->screen;
        $this->url = $data->url;

        $this->isEdit = true;
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
            return Screen::where('screen', 'like', '%' . $this->search . '%')
                ->orWhere('url', 'like', '%' . $this->search . '%')

                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Screen::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["screens"] = $this->read();
        $data["count_data"] = Screen::count();
        return view('livewire.screen-component', $data)->extends("layout.template");
    }
}
