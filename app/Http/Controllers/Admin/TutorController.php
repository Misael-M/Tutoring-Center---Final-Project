<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutor;
use Illuminate\Http\Request;

class TutorController extends Controller
{
    /**
     * Muestra el listado de tutores.
     */
    public function index()
    {
        return view('admin.tutors.index');
    }

    /**
     * Muestra el formulario para editar datos del tutor.
     */
    public function edit(Tutor $tutor)
    {
        return view('admin.tutors.edit', compact('tutor'));
    }

    /**
     * Actualiza la especialidad.
     */
    public function update(Request $request, Tutor $tutor)
    {
        $validated = $request->validate([
            'specialty'      => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
        ]);

        $tutor->update($validated);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Tutor actualizado',
            'text'  => 'Los datos del tutor han sido actualizados exitosamente.',
        ]);

        return redirect()->route('admin.tutors.index');
    }

    /**
     * Muestra el gestor de horarios del tutor.
     */
    public function schedule(Tutor $tutor)
    {
        return view('admin.tutors.schedule', compact('tutor'));
    }
}
