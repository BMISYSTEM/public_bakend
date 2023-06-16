<?php
namespace App\Http\Controllers;
use App\Models\cliente;
use Dotenv\Store\File\Paths;
use Illuminate\Http\Request;
use App\Http\Requests\Clientes;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\clientesResource;
use App\Models\notificacion;

class ClienteController extends Controller
{
    public function create(Clientes $request){
        $vehiculo = $request->validated();
        $user= Auth::user()->id;
        // se debe cambiar el estado en el de una lista fija
        $creado = cliente::create([
            'nombre' => $vehiculo['nombre'],
            'apellido' => $vehiculo['apellido'],
            'cedula' => $vehiculo['cedula'],
            'date' => $vehiculo['data'],
            'telefono' => $vehiculo['telefono'],
            'email' => $vehiculo['email'],
            'estados'=>'1',
            'users_id'=> $user
        ]);
        return $creado;
    }

    public function index()
    {
        return new clientesResource(cliente::with('user')->with('estado')->with('vehiculo')->get());
    }

    public function descargasdoc(Request $request)
    {
        $documento = $request['documento'];
        $paht = storage_path('app/public/documentos/'.$documento);
        return  response()->download($paht);
        // return $paht;
        // return $request;
    }


    //actualizacionn de estado 

    public function updateEstados (Request $request)
    {
        $estado = $request['id'];
        $cliente = $request['cliente'];
        $update = cliente::where('id',$cliente)->get();
        $update->toQuery()->update([
            'estados' => $estado
        ]);

        $mensaje = 'El cliente '. $request['user_name']. ' cambio de estado a '.$request['nombre_estado'];
        $notificacion = notificacion::create([
            'user_id' => $request['user'],
            'mensaje' => $mensaje,
            'visto' =>'no'
        ]);
        return 'el cambio se realizo de forma correcta';
    }

    public function vendidos()
    {

        $vista = DB::select("select c.id, c.nombre, c.apellido, c.cedula,c.date, c.telefono, c.email, c.vfinanciar, c.ncuotas, c.valormensual, c.doccedula, c.docestratos, c.docdeclaracion, c.docsolicitud, c.created_at, c.updated_at, c.vehiculos, c.estados, c.users_id, c.tasa,
        u.name,u.rol,v.valor,v.placa
        from clientes as c
        inner join users u on c.users_id = u.id
        inner join vehiculos v on c.vehiculos = v.id
        where c.estados ='7'");
        return response()->json($vista);
    }
    public function pendientes()
    {

        $vista = DB::select("select c.id, c.nombre, c.apellido, c.cedula,c.date, c.telefono, c.email, c.vfinanciar, c.ncuotas, c.valormensual, c.doccedula, c.docestratos, c.docdeclaracion, c.docsolicitud, c.created_at, c.updated_at, c.vehiculos, c.estados, c.users_id, c.tasa,
        u.name,u.rol,v.valor,v.placa,e.estado as nombre_estado
        from clientes as c
        inner join users u on c.users_id = u.id
        inner join vehiculos v on c.vehiculos = v.id
        inner join estados e on c.estados = e.id
        where c.estados ='4'");
        return response()->json($vista);
    }
    public function aprobados()
    {

        $vista = DB::select("select c.id, c.nombre, c.apellido, c.cedula,c.date, c.telefono, c.email, c.vfinanciar, c.ncuotas, c.valormensual, c.doccedula, c.docestratos, c.docdeclaracion, c.docsolicitud, c.created_at, c.updated_at, c.vehiculos, c.estados, c.users_id, c.tasa,
        u.name,u.rol,v.valor,v.placa,e.estado as nombre_estado
        from clientes as c
        inner join users u on c.users_id = u.id
        inner join vehiculos v on c.vehiculos = v.id
        inner join estados e on c.estados = e.id
        where c.estados ='5'");
        return response()->json($vista);
    }
    public function seguimiento()
    {
        $vista = DB::select("
        select 
        c.id as cliente_id, 
        c.nombre, 
        c.apellido, 
        c.cedula, 
        c.date, 
        c.telefono,
        c.email,
        c.estados,
        c.users_id,
         e.id as estados_id, e.estado, e.created_at, e.updated_at  from clientes c 
        inner join estados e on e.id = c.estados");
        return response()->json($vista);
        
    }
 }

