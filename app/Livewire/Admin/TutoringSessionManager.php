<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Appointment;
use App\Models\TutoringSession;
use App\Models\TutoringMaterial;
use Illuminate\Support\Facades\Storage;

class TutoringSessionManager extends Component
{
    use WithFileUploads;

    public $appointment;
    public $session;
    public $tab = 'session';

    // Session fields
    public $student_performance;
    public $topics_to_improve;
    public $notes;

    // Materials
    public $newMaterials = [];
    public $existingMaterials = [];

    // Modal
    public $showPreviousAppointmentsModal = false;
    public $previousAppointments = [];

    // Custom Spanish validation messages
    protected $messages = [
        'student_performance.required' => 'El desempeño del estudiante es obligatorio.',
        'student_performance.string' => 'El desempeño del estudiante debe ser texto.',
        'topics_to_improve.required' => 'Los temas a mejorar son obligatorios.',
        'topics_to_improve.string' => 'Los temas a mejorar deben ser texto.',
        'notes.string' => 'Las notas deben ser texto.',
        'newMaterials.*.image' => 'El archivo debe ser una imagen.',
        'newMaterials.*.max' => 'La imagen no debe pesar más de 5MB.',
    ];

    public function openPreviousAppointments()
    {
        $this->previousAppointments = Appointment::with(['tutor', 'session'])
            ->where('student_id', $this->appointment->student_id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
            
        $this->showPreviousAppointmentsModal = true;
    }
    
    public function closePreviousAppointments()
    {
        $this->showPreviousAppointmentsModal = false;
    }

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->session = $appointment->session()->firstOrCreate([
            'appointment_id' => $appointment->id
        ]);

        $this->student_performance = $this->session->student_performance;
        $this->topics_to_improve = $this->session->topics_to_improve;
        $this->notes = $this->session->notes;

        $this->loadMaterials();
    }

    public function loadMaterials()
    {
        $this->existingMaterials = $this->session->materials()->get();
    }

    public function saveSession()
    {
        $this->validate([
            'student_performance' => 'required|string',
            'topics_to_improve' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $this->session->update([
            'student_performance' => $this->student_performance,
            'topics_to_improve' => $this->topics_to_improve,
            'notes' => $this->notes,
        ]);

        $this->appointment->update(['status' => 'Completada']);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Sesión guardada correctamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function uploadMaterials()
    {
        $this->validate([
            'newMaterials.*' => 'image|max:5120', // 5MB Max
        ]);

        foreach ($this->newMaterials as $material) {
            $path = $material->store('tutoring_materials', 'public');
            $this->session->materials()->create([
                'file_path' => $path,
                'original_name' => $material->getClientOriginalName()
            ]);
        }

        $this->newMaterials = [];
        $this->loadMaterials();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Materiales subidos correctamente.'
        ]);
    }

    public function deleteMaterial($id)
    {
        $material = TutoringMaterial::find($id);
        if ($material) {
            Storage::disk('public')->delete($material->file_path);
            $material->delete();
            $this->loadMaterials();
            
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Material eliminado correctamente.'
            ]);
        }
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }

    public function removeNewMaterial($index)
    {
        unset($this->newMaterials[$index]);
        $this->newMaterials = array_values($this->newMaterials);
    }

    public function render()
    {
        return view('livewire.admin.tutoring-session-manager');
    }
}
