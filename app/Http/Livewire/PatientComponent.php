<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use App\Models\Patient;

class PatientComponent extends Component
{
	use WithPagination;

	public $idPatient;
	public $medical_number;
	public $full_name;
	public $place_of_birth;
	public $date_of_birth;
	public $address;
	public $phone_number;
	public $email;
	public $identity_type;
	public $identity_number;

	public $isEdit = false;
	public $search;

	protected $paginationTheme = "bootstrap";

	public function rules()
	{
		return [
			'medical_number' => $this->isEdit ? 'required|unique:patients,medical_number,' . $this->idPatient : 'required|unique:patients,medical_number',
			'full_name' => 'required',
			'place_of_birth' => 'required',
			'date_of_birth' => 'required',
			'address' => 'required',
			'phone_number' => 'required',
			'email' => 'required|email',
			'identity_type' => 'required',
			'identity_number' => 'required',
		];
	}

	private function data()
	{
		$splitDOB = explode('/', $this->date_of_birth);
		return [
			'medical_number' => $this->medical_number,
			'full_name' => $this->full_name,
			'place_of_birth' => $this->place_of_birth,
			'date_of_birth' => $splitDOB[2] . '-' . $splitDOB[1] . '-' . $splitDOB[0],
			'address' => $this->address,
			'phone_number' => $this->phone_number,
			'email' => $this->email,
			'identity_type' => $this->identity_type,
			'identity_number' => $this->identity_number,
		];
	}

	public function create()
	{
		$this->idPatient = '';
		$this->medical_number = '';
		$this->full_name = '';
		$this->place_of_birth = '';
		$this->date_of_birth = '';
		$this->address = '';
		$this->phone_number = '';
		$this->email = '';
		$this->identity_type = '';
		$this->identity_number = '';

		$this->isEdit = false;
		$this->emit('date_of_birth', $this->date_of_birth);
		$this->emit('identity_type', $this->identity_type);
		$this->resetValidation();
	}

	public function edit($id)
	{
		$data = Patient::find($id);
		$this->idPatient = $id;
		$this->medical_number = $data->medical_number;
		$this->full_name = $data->full_name;
		$this->place_of_birth = $data->place_of_birth;
		$this->date_of_birth = $data->date_of_birth;
		$this->address = $data->address;
		$this->phone_number = $data->phone_number;
		$this->email = $data->email;
		$this->identity_type = $data->identity_type;
		$this->identity_number = $data->identity_number;

		$this->isEdit = true;
		$this->emit('date_of_birth', date('d/m/Y', strtotime($this->date_of_birth)));
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
		$data = $this->data();
		$data['user_id_created'] = auth()->user()->id;
		Patient::create($data);
		$this->emit("btnSave", "Success Create Data!");
	}

	private function update()
	{
		$this->validate();
		$data = $this->data();
		$data['user_id_updated'] = auth()->user()->id;
		Patient::find($this->idPatient)->update($data);
		$allData = $this->read();
		$this->gotoPage($allData->currentPage());
		$this->emit("btnSave", "Success Update Data!");
	}

	private function read()
	{
		if ($this->search) {
			return Patient::where('medical_number', 'like', '%' . $this->search . '%')
				->orWhere('full_name', 'like', '%' . $this->search . '%')
				->orWhere('place_of_birth', 'like', '%' . $this->search . '%')
				->orWhere('date_of_birth', 'like', '%' . $this->search . '%')
				->orWhere('address', 'like', '%' . $this->search . '%')
				->orWhere('phone_number', 'like', '%' . $this->search . '%')
				->orWhere('email', 'like', '%' . $this->search . '%')
				->orWhere('identity_type', 'like', '%' . $this->search . '%')
				->orWhere('identity_number', 'like', '%' . $this->search . '%')
				->orWhere('user_id_created', 'like', '%' . $this->search . '%')
				->orWhere('user_id_updated', 'like', '%' . $this->search . '%')
				->orWhere('user_id_deleted', 'like', '%' . $this->search . '%')

				->orderBy('id', 'desc')
				->paginate(5);
		} else {
			return Patient::orderBy('id', 'desc')
				->paginate(5);
		}
	}

	public function render()
	{
		$data["patients"] = $this->read();
		$data["count_data"] = Patient::count();
		return view('livewire.patient-component', $data)->extends("layout.template");
	}
}
