<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\User;

class UserComponent extends Component
{
    use WithPagination;

    public $idUser;
    	public $username;
	public $email;
	public $email_verified_at;
	public $password;
	public $remember_token;
	public $paramedic_id;
	public $fullname;
	
    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
			'username' => 'required',
			'email' => 'required',
			'email_verified_at' => 'required',
			'password' => 'required',
			'remember_token' => 'required',
			'paramedic_id' => 'required',
			'fullname' => 'required',
			];
    }

    private function data()
    {
        return [
			'username' => $this->username,
			'email' => $this->email,
			'email_verified_at' => $this->email_verified_at,
			'password' => $this->password,
			'remember_token' => $this->remember_token,
			'paramedic_id' => $this->paramedic_id,
			'fullname' => $this->fullname,
			];
    }

    public function create()
    {
        		$this->idUser = '';
		$this->username = '';
		$this->email = '';
		$this->email_verified_at = '';
		$this->password = '';
		$this->remember_token = '';
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
		$this->email = $data->email;
		$this->email_verified_at = $data->email_verified_at;
		$this->password = $data->password;
		$this->remember_token = $data->remember_token;
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
				->orWhere('email', 'like', '%' . $this->search . '%')
				->orWhere('email_verified_at', 'like', '%' . $this->search . '%')
				->orWhere('password', 'like', '%' . $this->search . '%')
				->orWhere('remember_token', 'like', '%' . $this->search . '%')
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
