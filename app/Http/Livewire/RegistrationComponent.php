<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\Paramedic;
use App\Models\Patient;
use Livewire\Component;

class RegistrationComponent extends Component
{
    use WithPagination;

    public $idRegistration;
    public $patient_id;
    public $paramedic_id;
    public $registration_number;
    public $registration_date;
    public $registration_hour;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'patient_id' => 'required',
            'paramedic_id' => 'required',
        ];
    }

    private function data()
    {
        return [
            'patient_id' => $this->patient_id,
            'paramedic_id' => $this->paramedic_id,
            'registration_number' => $this->registration_number,
        ];
    }

    public function create()
    {
        $this->idRegistration = '';
        $this->patient_id = '';
        $this->paramedic_id = '';
        $this->registration_number = '';

        $this->isEdit = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Registration::find($id);
        $this->idRegistration = $id;
        $this->patient_id = $data->patient_id;
        $this->paramedic_id = $data->paramedic_id;
        $this->registration_number = $data->registration_number;

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
        $splitRegistrationDate = explode("/", $this->registration_date);
        $formatRegistrationDate = $splitRegistrationDate[2] . "-" . $splitRegistrationDate[1] . "-" . $splitRegistrationDate[0];
        $codeMax = Registration::select(DB::raw("MAX(RIGHT(registration_number, 4)) as number_max"))
            ->where('registration_date', $formatRegistrationDate)
            ->first();
        if ($codeMax) {
            $current_number = intval($codeMax->number_max) + 1;
            $this->code = "IPR/" . $formatRegistrationDate . "/" . sprintf("%04s", $current_number);
        } else {
            $this->code = "IPR/" . $formatRegistrationDate . "/0001";
        }
        $queueMax = Registration::max('queue_number')
            ->where('registration_date', $formatRegistrationDate)
            ->first();
        $data = $this->data();
        $data['user_id_created'] = auth()->user()->id;
        $data['registration_date'] = $formatRegistrationDate;
        $data['registration_hour'] = $formatRegistrationDate . " " . $this->registration_hour;
        $data['queue_number'] = $queueMax ? $queueMax->queue_number + 1 : 1;
        Registration::create($data);
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        Registration::find($this->idRegistration)->update($this->data());
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Registration::where('patient_id', 'like', '%' . $this->search . '%')
                ->orWhere('paramedic_id', 'like', '%' . $this->search . '%')
                ->orWhere('registration_number', 'like', '%' . $this->search . '%')

                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Registration::orderBy('id', 'desc')
                ->paginate(5);
        }
    }

    public function render()
    {
        $data["registrations"] = $this->read();
        $data["count_data"] = Registration::count();
        return view('livewire.registration-component', $data)->extends("layout.template");
    }

    public function getPatient(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $patients = Patient::limit(50)
                ->get();
        } else {
            $patients = Patient::where('fullname', 'like', '%' . $search . '%')
                ->where('medical_number', 'like', '%' . $search . '%')
                ->limit(50)
                ->get();
        }
        $response = [];
        if ($patients) {
            foreach ($patients as $patient) {
                $response[] = [
                    'id' => $patient->id,
                    'text' => $patient->full_name
                ];
            }
        }
        return response()->json($response);
    }

    public function getParamedic(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $paramedics = Paramedic::limit(50)
                ->get();
        } else {
            $paramedics = Paramedic::where('fullname', 'like', '%' . $search . '%')
                ->where('medical_number', 'like', '%' . $search . '%')
                ->limit(50)
                ->get();
        }
        $response = [];
        if ($paramedics) {
            foreach ($paramedics as $paramedic) {
                $response[] = [
                    'id' => $paramedic->id,
                    'text' => $paramedic->first_name . " " . $paramedic->last_name
                ];
            }
        }
        return response()->json($response);
    }
}
