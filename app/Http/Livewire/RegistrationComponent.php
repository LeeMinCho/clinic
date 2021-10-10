<?php

namespace App\Http\Livewire;

use App\Models\Clinic;
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
    public $clinic_id;

    public $isEdit = false;
    public $search;

    protected $paginationTheme = "bootstrap";

    public function rules()
    {
        return [
            'patient_id' => 'required',
            'paramedic_id' => 'required',
            'clinic_id' => 'required',
        ];
    }

    private function data()
    {
        return [
            'patient_id' => $this->patient_id,
            'clinic_id' => $this->clinic_id,
            'paramedic_id' => $this->paramedic_id,
            'registration_number' => $this->registration_number,
        ];
    }

    public function create()
    {
        $this->reset();

        $this->isEdit = false;
        $this->emit('registration_date', $this->registration_date);
        $this->emit('registration_hour', $this->registration_hour);
        $this->emit('patient_id', null);
        $this->emit('clinic_id', null);
        $this->emit('paramedic_id', null);
        $this->resetValidation();
    }

    public function edit($id)
    {
        $data = Registration::find($id);
        $this->idRegistration = $id;
        $this->patient_id = $data->patient_id;
        $this->paramedic_id = $data->paramedic_id;
        $this->registration_number = $data->registration_number;

        $patient = Patient::findOrFail($data->patient_id);
        $clinic = Clinic::findOrFail($data->clinic_id);
        $paramedic = Paramedic::findOrFail($data->paramedic_id);
        $this->isEdit = true;
        $this->emit('registration_date', date('d/m/Y', strtotime($data->registration_date)));
        $this->emit('registration_hour', date('H:i', strtotime($data->registration_hour)));
        $this->emit('patient_id', $patient);
        $this->emit('clinic_id', $clinic);
        $this->emit('paramedic_id', $paramedic);
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
        $dateInRegistrationNumber = $splitRegistrationDate[2] . $splitRegistrationDate[1] . $splitRegistrationDate[0];
        $codeMax = Registration::select(DB::raw("MAX(RIGHT(registration_number, 4)) as number_max"))
            ->where('registration_date', $formatRegistrationDate)
            ->first();
        if ($codeMax) {
            $current_number = intval($codeMax->number_max) + 1;
            $this->registration_number = "IPR/" . $dateInRegistrationNumber . "/" . sprintf("%04s", $current_number);
        } else {
            $this->registration_number = "IPR/" . $dateInRegistrationNumber . "/0001";
        }
        $queueMax = Registration::select(DB::raw('MAX(queue_number) as queue_max'))
            ->where('registration_date', $formatRegistrationDate)
            ->first();
        $data = $this->data();
        $data['user_id_created'] = auth()->user()->id;
        $data['registration_date'] = $formatRegistrationDate;
        $data['registration_hour'] = $formatRegistrationDate . " " . $this->registration_hour;
        $data['queue_number'] = $queueMax->count() > 0 ? $queueMax->queue_max + 1 : 1;
        Registration::create($data);
        $this->emit("btnSave", "Success Create Data!");
    }

    private function update()
    {
        $this->validate();
        $data = $this->data();
        $data['user_id_updated'] = auth()->user()->id;
        Registration::find($this->idRegistration)->update($data);
        $allData = $this->read();
        $this->gotoPage($allData->currentPage());
        $this->emit("btnSave", "Success Update Data!");
    }

    private function read()
    {
        if ($this->search) {
            return Registration::with(['patient', 'paramedic', 'clinic', 'userCreated', 'userUpdated'])
                ->whereHas('patient', function ($query) {
                    return $query->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->whereHas('paramedic', function ($query) {
                    return $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                ->orderBy('registration_date', 'desc')
                ->orderBy('queue_number', 'asc')
                ->paginate(5);
        } else {
            return Registration::with(['patient', 'paramedic', 'clinic', 'userCreated', 'userUpdated'])
                ->whereHas('patient', function ($query) {
                    return $query->where('full_name', 'like', '%' . $this->search . '%');
                })
                ->whereHas('paramedic', function ($query) {
                    return $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('registration_number', 'like', '%' . $this->search . '%')
                ->orderBy('registration_date', 'desc')
                ->orderBy('queue_number', 'asc')
                ->paginate(5);
        }
    }

    private function readDetailRegistration()
    {
        return Registration::with(['userCreated', 'userUpdated'])
            ->find($this->idRegistration);
    }

    public function render()
    {
        $data["registrations"] = $this->read();
        $data["count_data"] = Registration::count();
        $data["detail_registration"] = $this->readDetailRegistration();
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
        $clinic_id = $request->clinic_id;
        $paramedics = [];
        if ($clinic_id != '') {
            if ($search == '') {
                $paramedics = Paramedic::with(['clinics'])
                    ->whereHas('clinics', function ($query) use ($clinic_id) {
                        return $query->where('clinics.id', $clinic_id);
                    })
                    ->limit(50)
                    ->get();
            } else {
                $paramedics = Paramedic::with(['clinics'])
                    ->whereHas('clinics', function ($query) use ($clinic_id) {
                        return $query->where('clinics.id', $clinic_id);
                    })
                    ->where('fullname', 'like', '%' . $search . '%')
                    ->where('medical_number', 'like', '%' . $search . '%')
                    ->limit(50)
                    ->get();
            }
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

    public function getClinic(Request $request)
    {
        $search = $request->search;
        if ($search == '') {
            $clinics = Clinic::limit(50)
                ->get();
        } else {
            $clinics = Clinic::where('name', 'like', '%' . $search . '%')
                ->limit(50)
                ->get();
        }
        $response = [];
        if ($clinics) {
            foreach ($clinics as $clinic) {
                $response[] = [
                    'id' => $clinic->id,
                    'text' => $clinic->name
                ];
            }
        }
        return response()->json($response);
    }
}
