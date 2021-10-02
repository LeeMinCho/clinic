<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Paramedic;

class ParamedicComponent extends Component
{
    use WithPagination;

    public $idParamedic;
    public $first_name;
    public $last_name;
    public $paramedic_type;
    public $registration_number;
    public $phone_number;
    public $address;
    public $identity_type;
    public $identity_number;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'paramedic_type' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'identity_type' => 'required',
            'identity_number' => 'required',
        ];
    }

    private function data()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'paramedic_type' => $this->paramedic_type,
            'registration_number' => $this->registration_number,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'identity_type' => $this->identity_type,
            'identity_number' => $this->identity_number,
        ];
    }

    public function create()
    {
        $this->idParamedic = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->paramedic_type = '';
        $this->registration_number = '';
        $this->phone_number = '';
        $this->address = '';
        $this->identity_type = '';
        $this->identity_number = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Paramedic::find($id);
        $this->idParamedic = $id;
        $this->first_name = $data->first_name;
        $this->last_name = $data->last_name;
        $this->paramedic_type = $data->paramedic_type;
        $this->registration_number = $data->registration_number;
        $this->phone_number = $data->phone_number;
        $this->address = $data->address;
        $this->identity_type = $data->identity_type;
        $this->identity_number = $data->identity_number;

        $this->isEdit = true;
        $this->emit('paramedic_type', $this->paramedic_type);
        $this->emit('identity_type', $this->identity_type);
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
        Paramedic::create($this->data());
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        Paramedic::find($this->idParamedic)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Paramedic::where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('paramedic_type', 'like', '%' . $this->search . '%')
                ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%')
                ->orWhere('identity_type', 'like', '%' . $this->search . '%')
                ->orWhere('identity_number', 'like', '%' . $this->search . '%')

                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Paramedic::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["paramedics"] = $this->read();
        $data["count_data"] = Paramedic::count();
        return view('livewire.paramedic-component', $data)->extends("layout.template");
    }
}
