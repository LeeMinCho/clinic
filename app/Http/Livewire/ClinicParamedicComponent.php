<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\Paramedic;
use Livewire\Component;
use App\Models\Clinic;

class ClinicParamedicComponent extends Component
{
    use WithPagination;

    public $idClinicParamedic;
    public $clinic_id;
    public $paramedic_id;
    public $clinic_name;
    public $search;

    protected $paginationTheme = "bootstrap";
    protected $listeners = ['showModalParamedic', 'deleteParamedic'];

    public function showModalParamedic($clinic_id)
    {
        $this->clinic_id = $clinic_id;
        $clinic = Clinic::find($clinic_id);
        $this->clinic_name = $clinic->name;
        $this->resetValidation();
        $this->emit('showModal', $clinic_id);
    }

    public function rules()
    {
        return ['paramedic_id' => 'required'];
    }

    public function store()
    {
        $this->validate();
        $clinic = Clinic::findOrFail($this->clinic_id);
        $clinic->paramedics()->attach($this->paramedic_id);
        $this->emit('btnSave', 'Success create data!');
        $this->paramedic_id = '';
        $this->emit('paramedic_id', $this->paramedic_id);
    }

    public function deleteParamedic($id)
    {
        $clinic = Clinic::findOrFail($this->clinic_id);
        $clinic->paramedics()->detach($id);
        $this->emit('btnSave', 'Success delete data!');
    }

    private function read()
    {
        if ($this->search) {
            return Paramedic::with(['clinics'])
                ->whereHas('clinics', function ($query) {
                    return $query->where('clinics.id', $this->clinic_id);
                })
                ->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->latest()
                ->paginate(5);
        } else {
            return Paramedic::with(['clinics'])
                ->whereHas('clinics', function ($query) {
                    return $query->where('clinics.id', $this->clinic_id);
                })
                ->latest()
                ->paginate(5);
        }
    }

    public function render()
    {
        $data['clinic_paramedics'] = $this->read();
        $data["count_data"] = Paramedic::with(['clinics'])
            ->whereHas('clinics', function ($query) {
                return $query->where('clinics.id', $this->clinic_id);
            })
            ->count();
        return view('livewire.clinic-paramedic-component', $data);
    }

    public function getParamedic(Request $request)
    {
        $search = $request->search;
        $clinic = Clinic::find($request->clinic_id);
        $paramedicId = [];
        foreach ($clinic->paramedics as $paramedic) {
            $paramedicId[] = $paramedic->pivot->paramedic_id;
        }
        if ($search == '') {
            $paramedics = Paramedic::whereNotIn('id', $paramedicId)
                ->limit(50)
                ->get();
        } else {
            $paramedics = Paramedic::whereNotIn('id', $paramedicId)
                ->where('first_name', 'like', '%' . $search . '%')
                ->where('last_name', 'like', '%' . $search . '%')
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
