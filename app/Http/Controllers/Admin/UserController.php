<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        return view('admin.usuarios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar que se cree bien
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|unique:users,id_number',
            'country_code' => 'required|string|max:4',
            'phone_number' => 'required|string|min:10',
            'address' => 'required|string|max:250',
            'role_id' => 'required|exists:roles,id'
        ]);

        // Si pasa la validación, crea el usuario
        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Contraseña segura
            'id_number' => $request->id_number,
            'country_code' => $request->country_code,
            'phone' => ltrim($request->phone_number, '0'), 
            'address' => $request->address,
        ]);

        // 1. Buscamos el rol exacto por su ID de forma segura
        $role = Role::findById($request->role_id);
        
        // 2. Le asignamos ese rol al nuevo usuario
        $usuario->assignRole($role);

        // 3. Crear el perfil respectivo según el rol
        if ($usuario->hasRole('Paciente')) {
            $usuario->patient()->create([]);
        } elseif ($usuario->hasRole('Doctor')) {
            $usuario->doctor()->create([]);
        }

        // Confirmación de operación exitosa
        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente',
        ]);

        // Redireccionará a la tabla principal
        return redirect(route('admin.usuarios.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {  
        $roles = Role::all(); // Línea para obtener todos los roles
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        // Validación que excluye la fila que se edita para permitir cambios individuales
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'id_number' => 'required|string|min:5|max:20|unique:users,id_number,' . $usuario->id,
            'country_code' => 'required|string|max:4',
            'phone_number' => 'required|string|min:10',
            'address' => 'required|string|max:250',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed' // La contraseña es opcional al editar
        ]);

        // Si pasa la validación, edita los datos básicos
        $usuario->update([
            'name' => $request->name,
            'email' => $request->email,
            'id_number' => $request->id_number,
            'country_code' => $request->country_code,
            'phone' => ltrim($request->phone_number, '0'), 
            'address' => $request->address,
        ]);

        // Actualizar contraseña solo si el usuario escribió una nueva
        if($request->filled('password')){
            $usuario->password = bcrypt($request->password);
            $usuario->save();
        }

        // Actualizar rol usando Spatie
        $role = Role::findById($request->role_id);
        $usuario->syncRoles($role);

        // Configuración de operación exitosa
        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido modificado correctamente',
        ]);

        // Si el usuario actualizado es un paciente, maneja su perfil médico
        if($usuario->hasRole('Paciente')){
            if (!$usuario->patient) {
                $patient = $usuario->patient()->create([]);
                return redirect()->route('admin.patients.edit', $patient);
            }
            return redirect()->route('admin.patients.edit', $usuario->patient);
        }

        // Si el usuario actualizado es un doctor, maneja su perfil médico
        if($usuario->hasRole('Doctor')){
            if (!$usuario->doctor) {
                $doctor = $usuario->doctor()->create([]);
                return redirect()->route('admin.doctors.edit', $doctor);
            }
            return redirect()->route('admin.doctors.edit', $usuario->doctor);
        }

        // Redireccionado a la tabla de usuarios
        return redirect(route('admin.usuarios.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // 1. EL CANDADO (Va primero). Verificamos si el ID del usuario coincide con el logueado.
        if ($usuario->id === Auth::id()) {
            abort(403, 'No puedes borrar tu propio usuario');
        }

        // 2. Si el código llega hasta aquí, significa que NO es él mismo. Procedemos a borrarlo.
        $usuario->delete();

        // 3. Mostramos la alerta de éxito
        session()->flash('swal', [
            'icon' => 'success', 
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido borrado correctamente',
        ]);

        // 4. Redireccionamos a la tabla
        return redirect(route('admin.usuarios.index'));
    }
}