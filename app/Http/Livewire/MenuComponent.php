<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Menu;

class MenuComponent extends Component
{
    use WithPagination;

    public $idMenu;
    public $menu;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'menu' => $this->isEdit ? 'required|unique:menus,menu,' . $this->idMenu : 'required|unique:menus,menu',
        ];
    }

    private function data()
    {
        return [
            'menu' => $this->menu,
        ];
    }

    public function create()
    {
        $this->idMenu = '';
        $this->menu = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Menu::find($id);
        $this->idMenu = $id;
        $this->menu = $data->menu;

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
        Menu::create($this->data());
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        Menu::find($this->idMenu)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Menu::where('menu', 'like', '%' . $this->search . '%')

                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Menu::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["menus"] = $this->read();
        $data["count_data"] = Menu::count();
        return view('livewire.menu-component', $data)->extends("layout.template");
    }
}
