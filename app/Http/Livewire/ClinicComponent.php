<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Clinic;

class ClinicComponent extends Component
{
    use WithPagination;

    public $idClinic;
    public $name;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }

    private function data()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function create()
    {
        $this->idClinic = '';
        $this->name = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Clinic::find($id);
        $this->idClinic = $id;
        $this->name = $data->name;

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
        Clinic::create($this->data());
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        Clinic::find($this->idClinic)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Clinic::where('name', 'like', '%' . $this->search . '%')

                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Clinic::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["clinics"] = $this->read();
        $data["count_data"] = Clinic::count();
        return view('livewire.clinic-component', $data)->extends("layout.template");
    }
}
