<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/*use App\Models\User;
use App\Models\Reserva;
use App\Models\Exposicion;*/
use Illuminate\Pagination\LengthAwarePaginator;


class AdminController extends Controller
{
    public function index()
    {
        // Aquí podrías pasar datos como el total de obras, etc.
        return view('admin.dashboard');
    }
    
    public function exposiciones()
    {
        return view('admin.exposiciones', [
            'exposiciones' => collect([]),
            'horarios'     => collect([]),
        ]);
    }

    public function usuarios()
    {
        //Cuando ya estén listos los modelos, descomentar esto y eliminar el paginador vacío de abajo
        /*return view('admin.usuarios', [
        'usuarios' => User::withCount('reservas')->paginate(20),
        ]);*/
        $paginator = new LengthAwarePaginator([], 0, 20);

        return view('admin.usuarios', [
            'usuarios' => $paginator,
        ]);
    }

    public function ventas()
    {
        //Cuando ya estén listos los modelos, descomentar esto y eliminar el paginador vacío de abajo
        /*return view('admin.ventas', [
        'reservas'     => Reserva::paginate(20),
        'exposiciones' => Exposicion::all(),
        ]);*/
        $paginator = new LengthAwarePaginator([], 0, 20);

        return view('admin.ventas', [
            'reservas'     => $paginator,
            'exposiciones' => collect([]),
        ]);
    }

    //Esto va afuera para cuando tenga datos reales, por ahora solo dejo los métodos de vista
    // comentados para no tener errores de clases no encontradas
        
        // Usuarios
        //    public function usuarios()       { return view('admin.usuarios', ['usuarios' => User::withCount('reservas')->paginate(20)]); }
        //    public function usuariosStore(Request $request)  { /* validar y crear */ }
        //    public function usuariosDestroy($id)             { /* eliminar */ }

            // Exposiciones
        //    public function exposiciones()   { return view('admin.exposiciones', ['exposiciones' => Exposicion::all(), 'horarios' => Horario::all()]); }
        //    public function exposicionesStore(Request $request)   { /* crear */ }
        //    public function exposicionesUpdate(Request $request, $id) { /* editar */ }
        //    public function exposicionesDestroy($id)              { /* eliminar */ }

            // Horarios
        //    public function horariosStore(Request $request)    { /* crear */ }
        //    public function horariosUpdate(Request $request, $id) { /* editar */ }
        //    public function horariosDestroy($id)               { /* eliminar */ }

            // Ventas
        //    public function ventas()         { return view('admin.ventas', ['reservas' => Reserva::paginate(20), 'exposiciones' => Exposicion::all()]); }
        //    public function reservasCancelar($codigo)          { /* cambiar estado */ }
        //    public function ventasExportar() { /* generar CSV */ }
}