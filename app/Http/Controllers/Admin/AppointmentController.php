<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function detail(Appointment $appointment)
    {
        $appointment->load(['student', 'tutor', 'session.materials', 'student.student.scholarGrade']);
        return view('admin.appointments.detail', compact('appointment'));
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function session(Appointment $appointment)
    {
        return view('admin.appointments.session', compact('appointment'));
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Cita eliminada correctamente.'
        ]);
        return redirect()->route('admin.appointments.index');
    }
}
