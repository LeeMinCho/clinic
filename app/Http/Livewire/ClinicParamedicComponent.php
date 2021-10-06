<?php

namespace App\Http\Livewire;

use App\Models\ClinicParamedic;
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
    public $search;

    protected $paginationTheme = "bootstrap";
    protected $listeners = ['showModalParamedic', 'deleteParamedic'];

    public function showModalParamedic($clinic_id)
    {
        $this->clinic_id = $clinic_id;
        $this->emit('showModal', $clinic_id);
    }

    public function rules()
    {
        return ['paramedic_id' => 'required'];
    }

    public function store()
    {
        $this->validate();
        ClinicParamedic::create([
            'clinic_id' => $this->clinic_id,
            'paramedic_id' => $this->paramedic_id
        ]);
        $this->emit('btnSave', 'Success create data!');
        $this->emit('paramedic_id', '');
    }

    public function deleteParamedic($id)
    {
        ClinicParamedic::destroy($id);
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
                ->orderBy('id', 'desc')
                ->paginate(5);
        } else {
            return Paramedic::with(['clinics'])
                ->whereHas('clinics', function ($query) {
                    return $query->where('clinics.id', $this->clinic_id);
                })
                ->orderBy('id', 'desc')
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
        $paramedicInClinic = ClinicParamedic::where('clinic_id', $request->clinic_id)
            ->get();
        $paramedicId = [];
        if ($paramedicInClinic) {
            foreach ($paramedicInClinic as $value) {
                $paramedicId[] = $value->paramedic_id;
            }
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
