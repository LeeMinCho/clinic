<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\User;

class UserComponent extends Component
{
    use WithPagination;

    public $idUser;
    public $username;
    public $password;
    public $password_confirmation;
    public $paramedic_id;
    public $fullname;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        $rules['username'] = $this->isEdit ? 'required|unique:users,username,' . $this->idUser : 'required|unique:users,username';
        if ($this->isEdit == false) {
            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required';
        }
        $rules['fullname'] = 'required';
        return $rules;
    }

    private function data()
    {
        $data["username"] = $this->username;
        if ($this->isEdit == false) {
            $data["password"] = Hash::make($this->password);
        }
        $data["paramedic_id"] = $this->paramedic_id;
        $data["fullname"] = $this->fullname;
        return $data;
    }

    public function create()
    {
        $this->idUser = '';
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->paramedic_id = '';
        $this->fullname = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = User::find($id);
        $this->idUser = $id;
        $this->username = $data->username;
        $this->password = '';
        $this->password_confirmation = '';
        $this->paramedic_id = $data->paramedic_id;
        $this->fullname = $data->fullname;

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
        User::create($this->data());
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        User::find($this->idUser)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return User::where('username', 'like', '%' . $this->search . '%')
                ->orWhere('paramedic_id', 'like', '%' . $this->search . '%')
                ->orWhere('fullname', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return User::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["users"] = $this->read();
        $data["count_data"] = User::count();
        return view('livewire.user-component', $data)->extends("layout.template");
    }
}
