<?php

namespace App\Livewire\Admin\Estudiantes;

use Livewire\Component;
use App\Models\Student;
use App\Models\ScholarGrade;
use Livewire\WithFileUploads;

class StudentForm extends Component
{
    use WithFileUploads;

    public Student $student;
    
    // Student fields
    public $scholargrade_id;
    public $school_name;
    public $school_address;
    public $topics_needed;

    // Contacts
    public $contacts = [];

    // Images
    public $newImages = []; // Para subir nuevas imagenes
    public $existingImages = []; // Para mostrar imagenes existentes

    public function mount(Student $student)
    {
        $this->student = $student;
        $this->scholargrade_id = $student->scholargrade_id;
        $this->school_name = $student->school_name;
        $this->school_address = $student->school_address;
        $this->topics_needed = $student->topics_needed;

        // Cargar contactos
        foreach ($student->adultContacts as $contact) {
            $this->contacts[] = [
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->phone,
                'relationship' => $contact->relationship,
            ];
        }

        // Si no hay contactos, agregar uno vacío por defecto
        if (count($this->contacts) == 0) {
            $this->addContact();
        }

        // Cargar imagenes existentes
        $this->existingImages = $student->studentImages;
    }

    public function addContact()
    {
        $this->contacts[] = ['id' => null, 'name' => '', 'phone' => '', 'relationship' => ''];
    }

    public function removeContact($index)
    {
        // Si tiene ID, lo eliminamos de la base de datos inmediatamente o lo marcamos para borrar
        // Lo mas facil es borrarlo de inmediato
        if (!empty($this->contacts[$index]['id'])) {
            \App\Models\AdultContact::find($this->contacts[$index]['id'])->delete();
        }
        unset($this->contacts[$index]);
        $this->contacts = array_values($this->contacts); // Reindexar
    }

    public function removeImage($imageId)
    {
        $image = \App\Models\StudentImage::find($imageId);
        if ($image) {
            // Borrar archivo de storage si es necesario
            // \Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->existingImages = $this->student->studentImages()->get();
        }
    }

    public function save()
    {
        $this->validate([
            'scholargrade_id' => 'nullable|exists:scholar_grades,id',
            'school_name' => 'nullable|string|max:255',
            'school_address' => 'nullable|string|max:255',
            'topics_needed' => 'nullable|string',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.phone' => 'required|string|max:20',
            'contacts.*.relationship' => 'required|string|max:100',
            'newImages.*' => 'image|max:5120', // Max 5MB
        ]);

        // Guardar Estudiante
        $this->student->update([
            'scholargrade_id' => $this->scholargrade_id,
            'school_name' => $this->school_name,
            'school_address' => $this->school_address,
            'topics_needed' => $this->topics_needed,
        ]);

        // Guardar Contactos
        $keepContactIds = [];
        foreach ($this->contacts as $contactData) {
            if ($contactData['id']) {
                $contact = \App\Models\AdultContact::find($contactData['id']);
                $contact->update($contactData);
                $keepContactIds[] = $contact->id;
            } else {
                $newContact = $this->student->adultContacts()->create($contactData);
                $keepContactIds[] = $newContact->id;
            }
        }

        // Subir nuevas imagenes
        if ($this->newImages) {
            foreach ($this->newImages as $image) {
                $path = $image->store('student-images', 'public');
                $this->student->studentImages()->create([
                    'image_path' => $path
                ]);
            }
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Datos del estudiante guardados correctamente.'
        ]);

        return redirect()->route('admin.estudiantes.index');
    }

    public function render()
    {
        return view('livewire.admin.estudiantes.student-form', [
            'scholarGrades' => ScholarGrade::all()
        ]);
    }
}
